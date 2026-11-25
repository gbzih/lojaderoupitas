<?php

require_once __DIR__ . '/../config/db.php';

class Pagamento
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista os pagamentos registrados
    public function listarTodos(string $status = ''): array
    {
        $sql = "
            SELECT
                pg.id_pagamento,
                pg.id_pedido,
                pg.forma_pagamento,
                pg.valor,
                pg.status_pagamento,
                pg.data_pagamento,
                p.status AS status_pedido,
                p.data_pedido,
                u.nome AS cliente_nome,
                u.email AS cliente_email

            FROM pagamento pg

            INNER JOIN pedido p
                ON p.id_pedido = pg.id_pedido

            INNER JOIN cliente c
                ON c.id_cliente = p.id_cliente

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario
        ";

        $parametros = [];

        if ($status !== '') {
            $sql .= "
                WHERE pg.status_pagamento = :status
            ";

            $parametros[':status'] = $status;
        }

        $sql .= "
            ORDER BY pg.id_pagamento DESC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um pagamento pelo ID
    public function buscarPorId(int $idPagamento): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                pg.*,
                p.status AS status_pedido,
                p.valor_total AS valor_pedido,
                p.data_pedido,
                u.nome AS cliente_nome,
                u.email AS cliente_email

            FROM pagamento pg

            INNER JOIN pedido p
                ON p.id_pedido = pg.id_pedido

            INNER JOIN cliente c
                ON c.id_cliente = p.id_cliente

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario

            WHERE pg.id_pagamento = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idPagamento
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Lista os pagamentos de um pedido
    public function listarPorPedido(int $idPedido): array
    {
        $stmt = $this->conn->prepare("
            SELECT
                id_pagamento,
                id_pedido,
                forma_pagamento,
                valor,
                status_pagamento,
                data_pagamento

            FROM pagamento

            WHERE id_pedido = :id

            ORDER BY id_pagamento DESC
        ");

        $stmt->execute([
            ':id' => $idPedido
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total de pagamentos registrados
    public function totalPagamentos(): int
    {
        return (int) $this->conn
            ->query("SELECT COUNT(*) FROM pagamento")
            ->fetchColumn();
    }

    // Total de pagamentos por situação
    public function totalPorStatus(string $status): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*)

            FROM pagamento

            WHERE status_pagamento = :status
        ");

        $stmt->execute([
            ':status' => $status
        ]);

        return (int) $stmt->fetchColumn();
    }

    // Valor total dos pagamentos aprovados
    public function totalRecebido(): float
    {
        $sql = "
            SELECT COALESCE(SUM(valor), 0)

            FROM pagamento

            WHERE status_pagamento = 'aprovado'
        ";

        return (float) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Valor total dos pagamentos pendentes
    public function totalPendente(): float
    {
        $sql = "
            SELECT COALESCE(SUM(valor), 0)

            FROM pagamento

            WHERE status_pagamento = 'pendente'
        ";

        return (float) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Resumo dos pagamentos por forma
    public function resumoPorForma(): array
    {
        $sql = "
            SELECT
                forma_pagamento,
                COUNT(*) AS quantidade,
                SUM(valor) AS total

            FROM pagamento

            WHERE status_pagamento = 'aprovado'

            GROUP BY forma_pagamento

            ORDER BY total DESC
        ";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registra um pagamento pendente
    public function registrar(
        int $idPedido,
        int $idCliente,
        string $formaPagamento
    ): int {

        $formasPermitidas = [
            'pix',
            'cartao',
            'boleto'
        ];

        if (!in_array(
            $formaPagamento,
            $formasPermitidas,
            true
        )) {
            throw new RuntimeException(
                'Forma de pagamento inválida.'
            );
        }

        try {

            $this->conn->beginTransaction();

            // Bloqueia e verifica o pedido
            $stmt = $this->conn->prepare("
                SELECT
                    id_pedido,
                    id_cliente,
                    valor_total,
                    status

                FROM pedido

                WHERE id_pedido = :id

                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$pedido ||
                (int) $pedido['id_cliente'] !== $idCliente
            ) {
                throw new RuntimeException(
                    'Pedido não encontrado.'
                );
            }

            if ($pedido['status'] !== 'pendente') {
                throw new RuntimeException(
                    'Este pedido não permite novos pagamentos.'
                );
            }

            // Verifica pagamentos existentes
            $stmt = $this->conn->prepare("
                SELECT
                    id_pagamento,
                    status_pagamento

                FROM pagamento

                WHERE id_pedido = :id

                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            $existentes = $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );

            foreach ($existentes as $pagamento) {

                if (in_array(
                    $pagamento['status_pagamento'],
                    ['pendente', 'aprovado'],
                    true
                )) {
                    throw new RuntimeException(
                        'Este pedido já possui um pagamento ativo.'
                    );
                }
            }

            // O valor vem do pedido, não do formulário
            $stmt = $this->conn->prepare("
                INSERT INTO pagamento (
                    id_pedido,
                    forma_pagamento,
                    valor,
                    status_pagamento,
                    data_pagamento
                )

                VALUES (
                    :id_pedido,
                    :forma,
                    :valor,
                    'pendente',
                    NULL
                )
            ");

            $stmt->execute([
                ':id_pedido' => $idPedido,
                ':forma' => $formaPagamento,
                ':valor' => $pedido['valor_total']
            ]);

            $idPagamento = (int)
                $this->conn->lastInsertId();

            $this->conn->commit();

            return $idPagamento;

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Aprova um pagamento exclusivamente simulado
    public function aprovarSimulado(
        int $idPagamento,
        int $idCliente
    ): void {

        try {

            $this->conn->beginTransaction();

            // Identifica o pedido relacionado
            $stmt = $this->conn->prepare("
                SELECT id_pedido

                FROM pagamento

                WHERE id_pagamento = :id
            ");

            $stmt->execute([
                ':id' => $idPagamento
            ]);

            $idPedido = $stmt->fetchColumn();

            if (!$idPedido) {
                throw new RuntimeException(
                    'Pagamento não encontrado.'
                );
            }

            // Bloqueia primeiro o pedido
            $stmt = $this->conn->prepare("
                SELECT
                    id_pedido,
                    id_cliente,
                    status,
                    valor_total

                FROM pedido

                WHERE id_pedido = :id

                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$pedido ||
                (int) $pedido['id_cliente'] !== $idCliente
            ) {
                throw new RuntimeException(
                    'Pedido não encontrado.'
                );
            }

            // Impede aprovação duplicada
            if ($pedido['status'] !== 'pendente') {
                throw new RuntimeException(
                    'Este pedido não está aguardando pagamento.'
                );
            }

            // Bloqueia os pagamentos do pedido
            $stmt = $this->conn->prepare("
                SELECT
                    id_pagamento,
                    id_pedido,
                    valor,
                    status_pagamento

                FROM pagamento

                WHERE id_pedido = :id

                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            $pagamentos = $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );

            $pagamentoSelecionado = null;

            foreach ($pagamentos as $pagamento) {

                if (
                    $pagamento['status_pagamento']
                    === 'aprovado'
                ) {
                    throw new RuntimeException(
                        'Este pedido já possui pagamento aprovado.'
                    );
                }

                if (
                    (int) $pagamento['id_pagamento']
                    === $idPagamento
                ) {
                    $pagamentoSelecionado = $pagamento;
                }
            }

            if (
                !$pagamentoSelecionado ||
                $pagamentoSelecionado['status_pagamento']
                    !== 'pendente'
            ) {
                throw new RuntimeException(
                    'Pagamento indisponível para aprovação.'
                );
            }

            // Confere o valor do pagamento
            if (
                (string) $pagamentoSelecionado['valor']
                !== (string) $pedido['valor_total']
            ) {
                throw new RuntimeException(
                    'O valor do pagamento não corresponde ao pedido.'
                );
            }

            // Busca os produtos comprados
            $stmt = $this->conn->prepare("
                SELECT
                    id_item_pedido,
                    id_produto,
                    quantidade,
                    preco_unitario,

                    (quantidade * preco_unitario)
                        AS subtotal

                FROM item_pedido

                WHERE id_pedido = :id

                ORDER BY
                    id_produto ASC,
                    id_item_pedido ASC

                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($itens)) {
                throw new RuntimeException(
                    'O pedido não possui produtos.'
                );
            }

            // Confere o total dos itens usando DECIMAL
            $stmt = $this->conn->prepare("
                SELECT
                    CASE
                        WHEN SUM(
                            quantidade * preco_unitario
                        ) = :total
                        THEN 1
                        ELSE 0
                    END

                FROM item_pedido

                WHERE id_pedido = :id
            ");

            $stmt->execute([
                ':total' => $pedido['valor_total'],
                ':id' => $idPedido
            ]);

            if (!(int) $stmt->fetchColumn()) {
                throw new RuntimeException(
                    'O total dos itens não corresponde ao pedido.'
                );
            }

            // Agrupa quantidades por produto
            $quantidades = [];

            foreach ($itens as $item) {

                $idProduto = (int) $item['id_produto'];

                $quantidade = (int) $item['quantidade'];

                if (
                    $quantidade <= 0 ||
                    (float) $item['preco_unitario'] < 0
                ) {
                    throw new RuntimeException(
                        'O pedido possui itens inválidos.'
                    );
                }

                if (!isset($quantidades[$idProduto])) {
                    $quantidades[$idProduto] = 0;
                }

                $quantidades[$idProduto] += $quantidade;
            }

            ksort($quantidades);

            // Confere e desconta o estoque
            foreach (
                $quantidades as $idProduto => $quantidade
            ) {

                // Bloqueia o produto
                $stmt = $this->conn->prepare("
                    SELECT
                        id_produto,
                        nome,
                        ativo

                    FROM produto

                    WHERE id_produto = :id

                    FOR UPDATE
                ");

                $stmt->execute([
                    ':id' => $idProduto
                ]);

                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                if (
                    !$produto ||
                    (int) $produto['ativo'] !== 1
                ) {
                    throw new RuntimeException(
                        'Um dos produtos não está disponível.'
                    );
                }

                // Bloqueia o registro de estoque
                $stmt = $this->conn->prepare("
                    SELECT
                        id_estoque,
                        quantidade

                    FROM estoque

                    WHERE id_produto = :id

                    FOR UPDATE
                ");

                $stmt->execute([
                    ':id' => $idProduto
                ]);

                $estoques = $stmt->fetchAll(
                    PDO::FETCH_ASSOC
                );

                if (count($estoques) !== 1) {
                    throw new RuntimeException(
                        'Registro de estoque ausente ou duplicado para: '
                        . $produto['nome']
                    );
                }

                $estoque = $estoques[0];

                if (
                    (int) $estoque['quantidade']
                    < $quantidade
                ) {
                    throw new RuntimeException(
                        'Estoque insuficiente para: '
                        . $produto['nome']
                    );
                }

                // Desconta as unidades compradas
                $stmt = $this->conn->prepare("
                    UPDATE estoque

                    SET quantidade =
                        quantidade - :quantidade

                    WHERE id_estoque = :id

                        AND quantidade >= :saldo_minimo
                ");

                $stmt->execute([
                    ':quantidade' => $quantidade,
                    ':id' => $estoque['id_estoque'],
                    ':saldo_minimo' => $quantidade
                ]);

                if ($stmt->rowCount() !== 1) {
                    throw new RuntimeException(
                        'Não foi possível atualizar o estoque.'
                    );
                }
            }

            // Registra cada item na tabela venda
            $stmtVenda = $this->conn->prepare("
                INSERT INTO venda (
                    id_produto,
                    quantidade,
                    valor_total,
                    data_venda
                )

                VALUES (
                    :id_produto,
                    :quantidade,
                    :valor_total,
                    NOW()
                )
            ");

            foreach ($itens as $item) {

                $stmtVenda->execute([
                    ':id_produto' => $item['id_produto'],
                    ':quantidade' => $item['quantidade'],
                    ':valor_total' => $item['subtotal']
                ]);
            }

            // Aprova o pagamento selecionado
            $stmt = $this->conn->prepare("
                UPDATE pagamento

                SET
                    status_pagamento = 'aprovado',
                    data_pagamento = CURDATE()

                WHERE id_pagamento = :id

                    AND status_pagamento = 'pendente'
            ");

            $stmt->execute([
                ':id' => $idPagamento
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new RuntimeException(
                    'Não foi possível aprovar o pagamento.'
                );
            }

            // Cancela outros pagamentos pendentes
            $stmt = $this->conn->prepare("
                UPDATE pagamento

                SET status_pagamento = 'cancelado'

                WHERE id_pedido = :id_pedido

                    AND id_pagamento <> :id_pagamento

                    AND status_pagamento = 'pendente'
            ");

            $stmt->execute([
                ':id_pedido' => $idPedido,
                ':id_pagamento' => $idPagamento
            ]);

            // Atualiza o pedido
            $stmt = $this->conn->prepare("
                UPDATE pedido

                SET status = 'pago'

                WHERE id_pedido = :id

                    AND status = 'pendente'
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new RuntimeException(
                    'Não foi possível atualizar o pedido.'
                );
            }

            // Confirma todas as alterações
            $this->conn->commit();

        } catch (Throwable $e) {

            // Desfaz todas as alterações em caso de erro
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }
}