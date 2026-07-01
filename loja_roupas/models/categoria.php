<?php
require_once __DIR__ . '/../config/db.php';

class Categoria
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function listarTodas(): array
    {
        // Removido 'ativo' do SELECT
        $sql = "SELECT id_categoria, nome FROM categoria ORDER BY nome";
        return $this->conn->query($sql)->fetchAll();
    }

    public function listarAtivas(): array
    {
        $sql = "SELECT id_categoria, nome FROM categoria ORDER BY nome";
        return $this->conn->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        // Removido 'ativo' do SELECT
        $stmt = $this->conn->prepare("SELECT id_categoria, nome FROM categoria WHERE id_categoria = :id");
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function inserir(string $nome): int
    {
        // Removido 'ativo' do INSERT
        $stmt = $this->conn->prepare("
            INSERT INTO categoria (nome)
            VALUES (:nome)
        ");
        $stmt->execute([':nome' => $nome]);
        return (int)$this->conn->lastInsertId();
    }

    public function atualizar(int $id, string $nome): void
    {
        $stmt = $this->conn->prepare("
            UPDATE categoria
            SET nome = :nome
            WHERE id_categoria = :id
        ");
        $stmt->execute([
            ':id' => $id,
            ':nome' => $nome
        ]);
    }
}