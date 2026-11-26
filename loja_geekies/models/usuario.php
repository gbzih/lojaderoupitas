<?php

require_once __DIR__ . '/../config/db.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Busca um usuário pelo e-mail
    public function buscarPorEmail($email)
    {
        $sql = "SELECT id_usuario, nome, email, senha
                FROM usuario
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca um usuário pelo ID
    public function buscarPorId($idUsuario)
    {
        $sql = "SELECT id_usuario, nome, email
                FROM usuario
                WHERE id_usuario = :id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verifica se o usuário é administrador
    public function buscarAdministrador($idUsuario)
    {
        $sql = "SELECT id_administrador, id_usuario, nivelAcesso
                FROM administrador
                WHERE id_usuario = :id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verifica se o usuário é cliente
    public function buscarCliente($idUsuario)
    {
        $sql = "SELECT id_cliente, id_usuario, cpf, telefone
                FROM cliente
                WHERE id_usuario = :id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastra um usuário e, quando informado, seu perfil
    public function cadastrar(
        $nome,
        $email,
        $senha_hash,
        $perfil = null,
        $extras = []
    ) {
        try {
            // Valida o perfil informado
            if (!in_array($perfil, [null, 'cliente', 'admin'], true)) {
                return false;
            }

            // Verifica se o e-mail já existe
            if ($this->buscarPorEmail($email)) {
                return false;
            }

            // Valida os dados obrigatórios do cliente
            if ($perfil === 'cliente' && empty($extras['cpf'])) {
                return false;
            }

            $this->conn->beginTransaction();

            // Cadastro na tabela usuario
            $sql = "INSERT INTO usuario (nome, email, senha)
                    VALUES (:nome, :email, :senha)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':nome'  => $nome,
                ':email' => $email,
                ':senha' => $senha_hash
            ]);

            $idUsuario = (int) $this->conn->lastInsertId();

            // Cadastro na tabela administrador
            if ($perfil === 'admin') {
                $sql = "INSERT INTO administrador
                        (id_usuario, nivelAcesso)
                        VALUES (:id_usuario, :nivel)";

                $stmt = $this->conn->prepare($sql);

                $stmt->execute([
                    ':id_usuario' => $idUsuario,
                    ':nivel' => $extras['nivelAcesso'] ?? 'admin'
                ]);
            }

            // Cadastro na tabela cliente
            elseif ($perfil === 'cliente') {
                $sql = "INSERT INTO cliente
                        (id_usuario, cpf, telefone)
                        VALUES (:id_usuario, :cpf, :telefone)";

                $stmt = $this->conn->prepare($sql);

                $stmt->execute([
                    ':id_usuario' => $idUsuario,
                    ':cpf' => $extras['cpf'],
                    ':telefone' => $extras['telefone'] ?? null
                ]);
            }

            $this->conn->commit();

            return $idUsuario;

        } catch (Exception $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            error_log(
                'Erro ao cadastrar usuário: ' . $e->getMessage()
            );

            return false;
        }
    }
}