<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Estoque.php';

class Entrada
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista o histórico de entradas
    public function listarTodas(): array
    {
        $sql = "
            SELECT
                e.id_entrada,
                e.id_produto,
                e.quantidade,
                e.data_entrada,
                p.nome AS produto_nome,
                c.nome AS categoria_nome

            FROM entrada e

            INNER JOIN produto p
                ON p.id_produto = e.id_produto

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            ORDER BY
                e.data_entrada DESC,
                e.id_entrada DESC
        ";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lista produtos disponíveis para entrada
    public function listarProdutos(): array
    {
        $sql = "
            SELECT
                p.id_produto,
                p.nome,
                COALESCE(e.quantidade, 0) AS estoque

            FROM produto p

            LEFT JOIN estoque e
                ON e.id_produto = p.id_produto

            WHERE p.ativo = 1

            ORDER BY p.nome ASC
        ";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registra uma entrada e atualiza o estoque
    public function registrar(
        int $idProduto,
        int $quantidade
    ): void {

        if ($idProduto <= 0 || $quantidade <= 0) {

            throw new RuntimeException(
                'Dados inválidos para entrada.'
            );
        }

        $estoqueModel = new Estoque();

        $estoqueModel->movimentar(
            $idProduto,
            'entrada',
            $quantidade
        );
    }

    // Total de registros de entrada
    public function totalEntradas(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM entrada
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Total de unidades recebidas
    public function totalUnidades(): int
    {
        $sql = "
            SELECT COALESCE(SUM(quantidade), 0)
            FROM entrada
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }
}