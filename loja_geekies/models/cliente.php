<?php

require_once __DIR__ . '/../config/db.php';

class Cliente
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // ==========================================
    // GERENCIAMENTO ADMINISTRATIVO DE USUÁRIOS
    // ==========================================

    public function listarUsuarios(string $busca = ''): array
    {
        $sql = "
            SELECT
                u.id_usuario,
                u.nome,
                u.email,

                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM administrador a
                        WHERE a.id_usuario = u.id_usuario
                    ) THEN 'Administrador'

                    WHEN EXISTS (
                        SELECT 1
                        FROM cliente c
                        WHERE c.id_usuario = u.id_usuario
                    ) THEN 'Cliente'

                    ELSE 'Sem perfil'
                END AS tipo

            FROM usuario u
        ";

        $parametros = [];

        if ($busca !== '') {

            $sql .= "
                WHERE u.nome LIKE :nome
                OR u.email LIKE :email
            ";

            $parametros = [
                ':nome' => '%' . $busca . '%',
                ':email' => '%' . $busca . '%'
            ];
        }

        $sql .= " ORDER BY u.id_usuario ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um usuário pelo ID
    public function buscarUsuario(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT id_usuario, nome, email
            FROM usuario
            WHERE id_usuario = :id
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Verifica se o usuário é administrador
    public function ehAdministrador(int $id): bool
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*)
            FROM administrador
            WHERE id_usuario = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Verifica se o e-mail pertence a outro usuário
    public function emailExiste(
        string $email,
        int $id
    ): bool
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*)
            FROM usuario
            WHERE email = :email
            AND id_usuario <> :id
        ");

        $stmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Atualiza nome e e-mail
    public function atualizarUsuario(
        int $id,
        string $nome,
        string $email
    ): void
    {
        $stmt = $this->conn->prepare("
            UPDATE usuario

            SET nome = :nome,
                email = :email

            WHERE id_usuario = :id
        ");

        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':id' => $id
        ]);
    }

    // Exclui um usuário sem comprometer o histórico
    public function excluirUsuario(int $id): void
    {
        try {

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                SELECT id_usuario
                FROM usuario
                WHERE id_usuario = :id
                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            if (!$stmt->fetchColumn()) {
                throw new RuntimeException(
                    'Usuário não encontrado.'
                );
            }

            if ($this->ehAdministrador($id)) {
                throw new RuntimeException(
                    'Administradores não podem ser excluídos.'
                );
            }

            // Bloqueia os registros de cliente vinculados
            $stmt = $this->conn->prepare("
                SELECT id_cliente
                FROM cliente
                WHERE id_usuario = :id
                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verifica se existem pedidos
            $stmt = $this->conn->prepare("
                SELECT COUNT(*)

                FROM pedido p

                INNER JOIN cliente c
                    ON c.id_cliente = p.id_cliente

                WHERE c.id_usuario = :id
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            if ((int) $stmt->fetchColumn() > 0) {
                throw new RuntimeException(
                    'Este usuário possui pedidos e não pode ser excluído.'
                );
            }

            // Verifica se existe carrinho
            $stmt = $this->conn->prepare("
                SELECT COUNT(*)

                FROM carrinho ca

                INNER JOIN cliente c
                    ON c.id_cliente = ca.id_cliente

                WHERE c.id_usuario = :id
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            if ((int) $stmt->fetchColumn() > 0) {
                throw new RuntimeException(
                    'Este usuário possui um carrinho vinculado.'
                );
            }

            // Exclui o perfil de cliente
            $stmt = $this->conn->prepare("
                DELETE FROM cliente
                WHERE id_usuario = :id
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            // Exclui o usuário
            $stmt = $this->conn->prepare("
                DELETE FROM usuario
                WHERE id_usuario = :id
            ");

            $stmt->execute([
                ':id' => $id
            ]);

            $this->conn->commit();

        } catch (Throwable $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // ==========================================
    // FUNCIONALIDADES DOS CLIENTES
    // ==========================================

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

    // Busca cliente pelo ID
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

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Busca cliente pelo ID do usuário
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

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Busca cliente pelo CPF
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

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Total de clientes
    public function totalClientes(): int
    {
        return (int) $this->conn
            ->query("SELECT COUNT(*) FROM cliente")
            ->fetchColumn();
    }

    // Total de clientes com pedidos
    public function totalComPedidos(): int
    {
        return (int) $this->conn
            ->query("
                SELECT COUNT(DISTINCT id_cliente)
                FROM pedido
            ")
            ->fetchColumn();
    }

    // Total de clientes com compras pagas
    public function totalComCompras(): int
    {
        return (int) $this->conn
            ->query("
                SELECT COUNT(DISTINCT id_cliente)

                FROM pedido

                WHERE status IN (
                    'pago',
                    'enviado',
                    'entregue'
                )
            ")
            ->fetchColumn();
    }

    // Resumo das compras
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