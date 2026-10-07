<?php
require_once __DIR__ . '/../config/db.php';

class Estoque
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /**
     * Lista a quantidade em estoque e o limite mínimo de cada produto,
     * relacionando com os nomes dos produtos e categorias.
     */
    public function listarComProdutos(): array
    {
        $sql = "
            SELECT 
                COALESCE(e.id_estoque, p.id_produto) AS id_estoque,
                COALESCE(e.quantidade, p.estoque, 0) AS quantidade,
                COALESCE(e.minimo, 5) AS minimo,
                p.id_produto, 
                p.nome AS produto_nome, 
                p.preco, 
                p.ativo,
                c.nome AS categoria_nome
            FROM produto p
            INNER JOIN categoria c ON c.id_categoria = p.categoria_id
            LEFT JOIN estoque e ON e.id_estoque = p.id_produto
            ORDER BY p.nome ASC
        ";

        return $this->conn->query($sql)->fetchAll();
    }

    /**
     * Busca os dados de estoque de um item específico pelo ID
     */
    public function buscarPorId(int $idEstoque): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT 
                p.id_produto AS id_estoque,
                COALESCE(e.quantidade, p.estoque, 0) AS quantidade,
                IFNULL(e.minimo, 5) AS minimo,
                p.nome AS produto_nome
            FROM produto p
            LEFT JOIN estoque e ON e.id_estoque = p.id_produto
            WHERE p.id_produto = :id
        ");
        $stmt->execute([':id' => $idEstoque]);
        $r = $stmt->fetch();

        return $r ?: null;
    }

    /**
     * Atualiza a quantidade e o limite mínimo de estoque de um item
     */
    public function atualizar(int $idEstoque, int $quantidade, int $minimo): void
    {
        $stmt = $this->conn->prepare("
            INSERT INTO estoque (id_estoque, quantidade, minimo)
            VALUES (:id, :quantidade, :minimo)
            ON DUPLICATE KEY UPDATE quantidade = VALUES(quantidade), minimo = VALUES(minimo)
        ");
        $stmt->execute([
            ':id'         => $idEstoque,
            ':quantidade' => $quantidade,
            ':minimo'     => $minimo
        ]);
    }

    /**
     * Atualiza apenas a quantidade em estoque (para entradas e saídas rápidas)
     */
    public function atualizarQuantidade(int $idEstoque, int $novaQuantidade): void
    {
        $stmt = $this->conn->prepare("
            UPDATE estoque
            SET quantidade = :quantidade
            WHERE id_estoque = :id
        ");
        $stmt->execute([
            ':id'         => $idEstoque,
            ':quantidade' => $novaQuantidade
        ]);
    }

    /**
     * Lista apenas os itens que estão com saldo igual ou abaixo do estoque mínimo
     */
    public function listarEstoqueBaixo(): array
    {
        $sql = "
            SELECT e.id_estoque, e.quantidade, e.minimo,
                   p.nome AS produto_nome,
                   c.nome AS categoria_nome
            FROM estoque e
            INNER JOIN produto p ON p.id_produto = e.id_estoque
            INNER JOIN categoria c ON c.id_categoria = p.categoria_id
            WHERE e.quantidade <= e.minimo AND p.ativo = 1
            ORDER BY e.quantidade ASC
        ";

        return $this->conn->query($sql)->fetchAll();
    }
}