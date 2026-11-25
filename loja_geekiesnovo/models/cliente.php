<?php

require_once __DIR__ . '/../config/db.php';

class Cliente
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista os clientes cadastrados
    public function listarTodos(string $busca = ''): array
    {
        $sql = "
            SELECT
                c.id_cliente,
                c.id_usuario,
                c.cpf,
                c.telefone,
                u.nome,
                u.email,

                (
                    SELECT COUNT(*)
                    FROM pedido p
                    WHERE p.id_cliente = c.id_cliente
                ) AS total_pedidos,

                (
                    SELECT COALESCE(SUM(p.valor_total), 0)
                    FROM pedido p
                    WHERE p.id_cliente = c.id_cliente
                    AND p.status IN (
                        'pago',
                        'enviado',
                        'entregue'
                    )
                ) AS total_compras

            FROM cliente c

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario
        ";

        $parametros = [];

        // Pesquisa por nome, e-mail ou CPF
        if ($busca !== '') {

            $sql .= "
                WHERE (
                    u.nome LIKE :nome
                    OR u.email LIKE :email
                    OR c.cpf LIKE :cpf
                )
            ";

            $cpfBusca = preg_replace('/\D/', '', $busca);

            $parametros = [
                ':nome' => '%' . $busca . '%',
                ':email' => '%' . $busca . '%',
                ':cpf' => '%' .
                    ($cpfBusca !== '' ? $cpfBusca : $busca)
                    . '%'
            ];
        }

        $sql .= "
            ORDER BY u.nome ASC, c.id_cliente ASC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um cliente pelo ID
    public function buscarPorId(int $idCliente): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                c.id_cliente,
                c.id_usuario,
                c.cpf,
                c.telefone,
                u.nome,
                u.email

            FROM cliente c

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario

            WHERE c.id_cliente = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idCliente
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Busca um cliente pelo ID do usuário
    public function buscarPorUsuario(int $idUsuario): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                c.id_cliente,
                c.id_usuario,
                c.cpf,
                c.telefone,
                u.nome,
                u.email

            FROM cliente c

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario

            WHERE c.id_usuario = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $idUsuario
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Busca um cliente pelo CPF
    public function buscarPorCpf(string $cpf): ?array
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        $stmt = $this->conn->prepare("
            SELECT
                c.id_cliente,
                c.id_usuario,
                c.cpf,
                c.telefone,
                u.nome,
                u.email

            FROM cliente c

            INNER JOIN usuario u
                ON u.id_usuario = c.id_usuario

            WHERE c.cpf = :cpf

            LIMIT 1
        ");

        $stmt->execute([
            ':cpf' => $cpf
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Total de clientes cadastrados
    public function totalClientes(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM cliente
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Total de clientes que realizaram pedidos
    public function totalComPedidos(): int
    {
        $sql = "
            SELECT COUNT(DISTINCT id_cliente)
            FROM pedido
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Total de clientes com compras pagas
    public function totalComCompras(): int
    {
        $sql = "
            SELECT COUNT(DISTINCT id_cliente)

            FROM pedido

            WHERE status IN (
                'pago',
                'enviado',
                'entregue'
            )
        ";

        return (int) $this->conn
            ->query($sql)
            ->fetchColumn();
    }

    // Retorna um resumo dos pedidos do cliente
    public function resumoCompras(int $idCliente): array
    {
        $stmt = $this->conn->prepare("
            SELECT

                COUNT(*) AS total_pedidos,

                COALESCE(SUM(
                    CASE
                        WHEN status IN (
                            'pago',
                            'enviado',
                            'entregue'
                        )
                        THEN 1
                        ELSE 0
                    END
                ), 0) AS pedidos_pagos,

                COALESCE(SUM(
                    CASE
                        WHEN status = 'pendente'
                        THEN 1
                        ELSE 0
                    END
                ), 0) AS pedidos_pendentes,

                COALESCE(SUM(
                    CASE
                        WHEN status = 'cancelado'
                        THEN 1
                        ELSE 0
                    END
                ), 0) AS pedidos_cancelados,

                COALESCE(SUM(
                    CASE
                        WHEN status IN (
                            'pago',
                            'enviado',
                            'entregue'
                        )
                        THEN valor_total
                        ELSE 0
                    END
                ), 0) AS total_compras

            FROM pedido

            WHERE id_cliente = :id
        ");

        $stmt->execute([
            ':id' => $idCliente
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}