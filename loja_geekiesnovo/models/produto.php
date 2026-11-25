<?php

require_once __DIR__ . '/../config/db.php';

class Produto
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista produtos com categoria e estoque
    public function listarComCategoria(
        bool $somenteAtivos = false
    ): array {

        $sql = "
            SELECT
                p.id_produto,
                p.id_categoria,
                p.nome,
                p.descricao,
                p.preco,
                p.ativo,
                c.nome AS categoria_nome,
                COALESCE(e.quantidade, 0) AS estoque,
                COALESCE(e.minimo, 5) AS minimo

            FROM produto p

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            LEFT JOIN estoque e
                ON e.id_produto = p.id_produto
        ";

        if ($somenteAtivos) {

            $sql .= "
                WHERE p.ativo = 1
            ";
        }

        $sql .= "
            ORDER BY c.nome ASC, p.nome ASC
        ";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um produto pelo ID
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                p.id_produto,
                p.id_categoria,
                p.nome,
                p.descricao,
                p.preco,
                p.ativo,
                c.nome AS categoria_nome,
                COALESCE(e.quantidade, 0) AS estoque,
                COALESCE(e.minimo, 5) AS minimo

            FROM produto p

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            LEFT JOIN estoque e
                ON e.id_produto = p.id_produto

            WHERE p.id_produto = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Verifica se a categoria existe
    public function categoriaExiste(int $id): bool
    {
        $stmt = $this->conn->prepare("
            SELECT id_categoria

            FROM categoria

            WHERE id_categoria = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetchColumn() !== false;
    }

    // Cadastra produto e estoque inicial
    public function inserir(
        int $categoriaId,
        string $nome,
        ?string $descricao,
        float $preco,
        int $estoque = 0,
        int $minimo = 5
    ): int {

        try {

            $this->conn->beginTransaction();

            // Insere o produto
            $stmt = $this->conn->prepare("
                INSERT INTO produto (
                    id_categoria,
                    nome,
                    descricao,
                    preco,
                    ativo
                )

                VALUES (
                    :id_categoria,
                    :nome,
                    :descricao,
                    :preco,
                    1
                )
            ");

            $stmt->execute([
                ':id_categoria' => $categoriaId,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco
            ]);

            // Recupera o ID do produto cadastrado
            $produtoId = (int) $this->conn->lastInsertId();

            // Cria o estoque do produto
            $stmtEstoque = $this->conn->prepare("
                INSERT INTO estoque (
                    id_produto,
                    quantidade,
                    minimo
                )

                VALUES (
                    :id_produto,
                    :quantidade,
                    :minimo
                )
            ");

            $stmtEstoque->execute([
                ':id_produto' => $produtoId,
                ':quantidade' => $estoque,
                ':minimo' => $minimo
            ]);

            $this->conn->commit();

            return $produtoId;

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Atualiza os dados cadastrais do produto
    public function atualizar(
        int $id,
        int $categoriaId,
        string $nome,
        ?string $descricao,
        float $preco
    ): void {

        $stmt = $this->conn->prepare("
            UPDATE produto

            SET
                id_categoria = :id_categoria,
                nome = :nome,
                descricao = :descricao,
                preco = :preco

            WHERE id_produto = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':id_categoria' => $categoriaId,
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco
        ]);
    }

    // Ativa ou inativa um produto
    public function setAtivo(
        int $id,
        bool $ativo
    ): void {

        $stmt = $this->conn->prepare("
            UPDATE produto

            SET ativo = :ativo

            WHERE id_produto = :id
        ");

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':ativo',
            $ativo ? 1 : 0,
            PDO::PARAM_INT
        );

        $stmt->execute();
    }

    // Exclui o produto e seu estoque
    public function deletar(int $id): bool
    {
        try {

            $this->conn->beginTransaction();

            // Exclui o registro de estoque
            $stmtEstoque = $this->conn->prepare("
                DELETE FROM estoque

                WHERE id_produto = :id
            ");

            $stmtEstoque->execute([
                ':id' => $id
            ]);

            // Exclui o produto
            $stmtProduto = $this->conn->prepare("
                DELETE FROM produto

                WHERE id_produto = :id
            ");

            $stmtProduto->execute([
                ':id' => $id
            ]);

            // Verifica se o produto foi realmente excluído
            if ($stmtProduto->rowCount() === 0) {

                $this->conn->rollBack();

                return false;
            }

            $this->conn->commit();

            return true;

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }
}