<?php

require_once __DIR__ . '/../config/db.php';

class Venda
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista todas as vendas registradas
    public function listarTodas(): array
    {
        $sql = "
            SELECT
                v.id_venda,
                v.id_produto,
                v.quantidade,
                v.valor_total,
                v.data_venda,
                p.nome AS produto_nome,
                c.nome AS categoria_nome

            FROM venda v

            INNER JOIN produto p
                ON p.id_produto = v.id_produto

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            ORDER BY
                v.data_venda DESC,
                v.id_venda DESC
        ";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca uma venda pelo ID
    public function buscarPorId(int $idVenda): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                v.id_venda,
                v.id_produto,
                v.quantidade,
                v.valor_total,
                v.data_venda,
                p.nome AS produto_nome,
                c.nome AS categoria_nome

            FROM venda v

            INNER JOIN produto p
                ON p.id_produto = v.id_produto

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            WHERE v.id_venda = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idVenda
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Lista vendas realizadas em determinado período
    public function listarPorPeriodo(
        string $inicio,
        string $fim
    ): array {

        $stmt = $this->conn->prepare("
            SELECT
                v.id_venda,
                v.id_produto,
                v.quantidade,
                v.valor_total,
                v.data_venda,
                p.nome AS produto_nome,
                c.nome AS categoria_nome

            FROM venda v

            INNER JOIN produto p
                ON p.id_produto = v.id_produto

            INNER JOIN categoria c
                ON c.id_categoria = p.id_categoria

            WHERE v.data_venda >= :inicio

                AND v.data_venda <
                    DATE_ADD(:fim, INTERVAL 1 DAY)

            ORDER BY
                v.data_venda DESC,
                v.id_venda DESC
        ");

        $stmt->execute([
            ':inicio' => $inicio,
            ':fim' => $fim
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total de registros de venda
    public function totalVendas(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM venda
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Total de unidades vendidas
    public function totalUnidades(): int
    {
        $sql = "
            SELECT COALESCE(SUM(quantidade), 0)
            FROM venda
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Faturamento total
    public function faturamentoTotal(): float
    {
        $sql = "
            SELECT COALESCE(SUM(valor_total), 0)
            FROM venda
        ";

        return (float) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Faturamento do dia atual
    public function faturamentoHoje(): float
    {
        $sql = "
            SELECT COALESCE(SUM(valor_total), 0)

            FROM venda

            WHERE data_venda >= CURDATE()

                AND data_venda <
                    DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        ";

        return (float) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Lista os produtos mais vendidos
    public function produtosMaisVendidos(): array
    {
        $sql = "
            SELECT
                p.id_produto,
                p.nome AS produto_nome,
                SUM(v.quantidade) AS total_vendido,
                SUM(v.valor_total) AS faturamento

            FROM venda v

            INNER JOIN produto p
                ON p.id_produto = v.id_produto

            GROUP BY
                p.id_produto,
                p.nome

            ORDER BY
                total_vendido DESC,
                p.id_produto ASC

            LIMIT 5
        ";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}