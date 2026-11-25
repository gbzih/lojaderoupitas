<?php

require_once __DIR__ . '/../config/db.php';

class Dashboard
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Retorna o total de produtos ativos
    public function totalProdutos(): int
    {
        $sql = "SELECT COUNT(*)
                FROM produto
                WHERE ativo = 1";

        $stmt = $this->conn->query($sql);

        return (int) $stmt->fetchColumn();
    }

    // Retorna o total de produtos com estoque baixo
    public function totalEstoqueBaixo(): int
    {
        $sql = "SELECT COUNT(DISTINCT p.id_produto)
                FROM estoque e
                INNER JOIN produto p
                    ON p.id_produto = e.id_produto
                WHERE e.quantidade <= e.minimo
                    AND p.ativo = 1";

        $stmt = $this->conn->query($sql);

        return (int) $stmt->fetchColumn();
    }
}