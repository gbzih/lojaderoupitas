<?php

require_once __DIR__ . '/../config/db.php';

class Estoque
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista todos os produtos com suas informações de estoque
    public function listarComProdutos(): array
    {
        $sql = "
            SELECT
                p.id_produto,
                e.id_estoque,
                COALESCE(e.quantidade, 0) AS quantidade,
                COALESCE(e.minimo, 5) AS minimo,
                p.nome AS produto_nome,
                p.preco,
                p.ativo,
                c.nome AS categoria_nome

            FROM produto p

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            LEFT JOIN estoque e
                ON e.id_produto = p.id_produto

            ORDER BY p.nome ASC
        ";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca o estoque pelo ID do produto
    public function buscarPorId(int $idProduto): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                p.id_produto,
                e.id_estoque,
                COALESCE(e.quantidade, 0) AS quantidade,
                COALESCE(e.minimo, 5) AS minimo,
                p.nome AS produto_nome,
                p.preco,
                p.ativo,
                c.nome AS categoria_nome

            FROM produto p

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            LEFT JOIN estoque e
                ON e.id_produto = p.id_produto

            WHERE p.id_produto = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idProduto
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Atualiza a quantidade e o estoque mínimo
    public function atualizar(
        int $idProduto,
        int $quantidade,
        int $minimo
    ): void {

        $this->validarValores($quantidade, $minimo);

        try {

            $this->conn->beginTransaction();

            $this->bloquearProduto($idProduto);

            $this->gravarEstoque(
                $idProduto,
                $quantidade,
                $minimo
            );

            $this->conn->commit();

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {

                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Atualiza somente a quantidade
    public function atualizarQuantidade(
        int $idProduto,
        int $novaQuantidade
    ): void {

        $this->validarValores($novaQuantidade, 0);

        try {

            $this->conn->beginTransaction();

            $this->bloquearProduto($idProduto);

            $estoqueAtual = $this->buscarPorId($idProduto);

            $minimo = (int) ($estoqueAtual['minimo'] ?? 5);

            $this->gravarEstoque(
                $idProduto,
                $novaQuantidade,
                $minimo
            );

            $this->conn->commit();

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {

                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Realiza uma entrada ou saída manual
    public function movimentar(
        int $idProduto,
        string $tipo,
        int $quantidade
    ): void {

        if (
            $quantidade <= 0 ||
            $quantidade > 2147483647 ||
            !in_array($tipo, ['entrada', 'saida'], true)
        ) {
            throw new RuntimeException(
                'Movimentação inválida.'
            );
        }

        try {

            $this->conn->beginTransaction();

            // Bloqueia o produto durante a movimentação
            $this->bloquearProduto($idProduto);

            $estoqueAtual = $this->buscarPorId($idProduto);

            if (!$estoqueAtual) {

                throw new RuntimeException(
                    'Produto não encontrado.'
                );
            }

            $quantidadeAtual =
                (int) $estoqueAtual['quantidade'];

            $minimo =
                (int) $estoqueAtual['minimo'];

            // Calcula o novo saldo
            if ($tipo === 'entrada') {

                $novaQuantidade =
                    $quantidadeAtual + $quantidade;

            } else {

                $novaQuantidade =
                    $quantidadeAtual - $quantidade;
            }

            // Impede estoque negativo
            if ($novaQuantidade < 0) {

                throw new RuntimeException(
                    'Quantidade insuficiente em estoque.'
                );
            }

            $this->validarValores(
                $novaQuantidade,
                $minimo
            );

            // Atualiza o saldo do estoque
            $this->gravarEstoque(
                $idProduto,
                $novaQuantidade,
                $minimo
            );

            // Registra o histórico de entradas
            if ($tipo === 'entrada') {

                $stmt = $this->conn->prepare("
                    INSERT INTO entrada (
                        id_produto,
                        quantidade,
                        data_entrada
                    )

                    VALUES (
                        :id_produto,
                        :quantidade,
                        NOW()
                    )
                ");

                $stmt->execute([
                    ':id_produto' => $idProduto,
                    ':quantidade' => $quantidade
                ]);
            }

            $this->conn->commit();

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {

                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Lista produtos ativos com estoque baixo
    public function listarEstoqueBaixo(): array
    {
        $sql = "
            SELECT
                p.id_produto,
                COALESCE(e.quantidade, 0) AS quantidade,
                COALESCE(e.minimo, 5) AS minimo,
                p.nome AS produto_nome,
                c.nome AS categoria_nome

            FROM produto p

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            LEFT JOIN estoque e
                ON e.id_produto = p.id_produto

            WHERE p.ativo = 1

                AND COALESCE(e.quantidade, 0)
                    <= COALESCE(e.minimo, 5)

            ORDER BY quantidade ASC, p.nome ASC
        ";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Grava ou cria o registro de estoque
    private function gravarEstoque(
        int $idProduto,
        int $quantidade,
        int $minimo
    ): void {

        if ($this->existe($idProduto)) {

            $stmt = $this->conn->prepare("
                UPDATE estoque

                SET
                    quantidade = :quantidade,
                    minimo = :minimo

                WHERE id_produto = :id
            ");

        } else {

            $stmt = $this->conn->prepare("
                INSERT INTO estoque (
                    id_produto,
                    quantidade,
                    minimo
                )

                VALUES (
                    :id,
                    :quantidade,
                    :minimo
                )
            ");
        }

        $stmt->execute([
            ':id' => $idProduto,
            ':quantidade' => $quantidade,
            ':minimo' => $minimo
        ]);
    }

    // Verifica se o produto possui registro de estoque
    private function existe(int $idProduto): bool
    {
        $stmt = $this->conn->prepare("
            SELECT 1

            FROM estoque

            WHERE id_produto = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idProduto
        ]);

        return $stmt->fetchColumn() !== false;
    }

    // Bloqueia o produto durante uma transação
    private function bloquearProduto(int $idProduto): void
    {
        $stmt = $this->conn->prepare("
            SELECT id_produto

            FROM produto

            WHERE id_produto = :id

            FOR UPDATE
        ");

        $stmt->execute([
            ':id' => $idProduto
        ]);

        if ($stmt->fetchColumn() === false) {

            throw new RuntimeException(
                'Produto não encontrado.'
            );
        }
    }

    // Valida os limites das quantidades
    private function validarValores(
        int $quantidade,
        int $minimo
    ): void {

        if (
            $quantidade < 0 ||
            $minimo < 0 ||
            $quantidade > 2147483647 ||
            $minimo > 2147483647
        ) {
            throw new RuntimeException(
                'Quantidade ou estoque mínimo inválido.'
            );
        }
    }
}