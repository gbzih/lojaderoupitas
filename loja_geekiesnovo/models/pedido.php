<?php

require_once __DIR__ . '/../config/db.php';

class Pedido
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista todos os pedidos
    public function listarTodos(
        string $status = ''
    ): array {

        $sql = "
            SELECT
                p.id_pedido,
                p.id_cliente,
                p.data_pedido,
                p.status,
                p.valor_total,
                u.nome AS cliente_nome,
                u.email AS cliente_email

            FROM pedido p

            INNER JOIN cliente c
                ON c.id_cliente = p.id_cliente

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario
        ";

        $parametros = [];

        if ($status !== '') {

            $sql .= " WHERE p.status = :status ";

            $parametros[':status'] = $status;
        }

        $sql .= "
            ORDER BY
                p.data_pedido DESC,
                p.id_pedido DESC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca os dados de um pedido específico
    public function buscarPorId(int $idPedido): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                p.id_pedido,
                p.id_cliente,
                p.data_pedido,
                p.status,
                p.valor_total,
                u.nome AS cliente_nome,
                u.email AS cliente_email,
                c.telefone

            FROM pedido p

            INNER JOIN cliente c
                ON c.id_cliente = p.id_cliente

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario

            WHERE p.id_pedido = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idPedido
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Lista os produtos pertencentes ao pedido
    public function listarItens(int $idPedido): array
    {
        $stmt = $this->conn->prepare("
            SELECT
                i.id_item_pedido,
                i.id_pedido,
                i.id_produto,
                i.quantidade,
                i.preco_unitario,
                p.nome AS produto_nome,

                (i.quantidade * i.preco_unitario)
                    AS subtotal

            FROM item_pedido i

            INNER JOIN produto p
                ON p.id_produto = i.id_produto

            WHERE i.id_pedido = :id

            ORDER BY i.id_item_pedido ASC
        ");

        $stmt->execute([
            ':id' => $idPedido
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Consulta os pagamentos relacionados ao pedido
    public function listarPagamentos(int $idPedido): array
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

    // Lista os pedidos de um cliente específico
    public function listarPorCliente(int $idCliente): array
    {
        $stmt = $this->conn->prepare("
            SELECT
                id_pedido,
                id_cliente,
                data_pedido,
                status,
                valor_total

            FROM pedido

            WHERE id_cliente = :id_cliente

            ORDER BY
                data_pedido DESC,
                id_pedido DESC
        ");

        $stmt->execute([
            ':id_cliente' => $idCliente
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um pedido pertencente a determinado cliente
    public function buscarPorCliente(
        int $idPedido,
        int $idCliente
    ): ?array {

        $stmt = $this->conn->prepare("
            SELECT
                id_pedido,
                id_cliente,
                data_pedido,
                status,
                valor_total

            FROM pedido

            WHERE id_pedido = :id_pedido

                AND id_cliente = :id_cliente

            LIMIT 1
        ");

        $stmt->execute([
            ':id_pedido' => $idPedido,
            ':id_cliente' => $idCliente
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Total geral de pedidos
    public function totalPedidos(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM pedido
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Total de pedidos por situação
    public function totalPorStatus(string $status): int
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*)

            FROM pedido

            WHERE status = :status
        ");

        $stmt->execute([
            ':status' => $status
        ]);

        return (int) $stmt->fetchColumn();
    }

    // Atualiza a situação do pedido
    public function atualizarStatus(
        int $idPedido,
        string $novoStatus
    ): void {

        // Transições permitidas pelo administrador
        $transicoes = [
            'pendente' => ['cancelado'],
            'pago' => ['enviado'],
            'enviado' => ['entregue'],
            'entregue' => [],
            'cancelado' => []
        ];

        try {

            $this->conn->beginTransaction();

            // Bloqueia o pedido durante a atualização
            $stmt = $this->conn->prepare("
                SELECT
                    id_pedido,
                    status

                FROM pedido

                WHERE id_pedido = :id

                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $idPedido
            ]);

            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {

                throw new RuntimeException(
                    'Pedido não encontrado.'
                );
            }

            $statusAtual = $pedido['status'];

            if (
                !isset($transicoes[$statusAtual]) ||
                !in_array(
                    $novoStatus,
                    $transicoes[$statusAtual],
                    true
                )
            ) {

                throw new RuntimeException(
                    'Alteração de status não permitida.'
                );
            }

            // Tratamento do cancelamento
            if ($novoStatus === 'cancelado') {

                // Verifica os pagamentos existentes
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

                $pagamentos = $stmt->fetchAll(
                    PDO::FETCH_ASSOC
                );

                foreach ($pagamentos as $pagamento) {

                    if (
                        !in_array(
                            $pagamento['status_pagamento'],
                            ['pendente', 'cancelado', 'recusado'],
                            true
                        )
                    ) {

                        throw new RuntimeException(
                            'O pedido possui um pagamento que impede o cancelamento.'
                        );
                    }
                }

                // Cancela pagamentos pendentes
                $stmt = $this->conn->prepare("
                    UPDATE pagamento

                    SET status_pagamento = 'cancelado'

                    WHERE id_pedido = :id

                        AND status_pagamento = 'pendente'
                ");

                $stmt->execute([
                    ':id' => $idPedido
                ]);
            }

            // Atualiza o status do pedido
            $stmt = $this->conn->prepare("
                UPDATE pedido

                SET status = :status

                WHERE id_pedido = :id
            ");

            $stmt->execute([
                ':status' => $novoStatus,
                ':id' => $idPedido
            ]);

            $this->conn->commit();

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {

                $this->conn->rollBack();
            }

            throw $e;
        }
    }
}