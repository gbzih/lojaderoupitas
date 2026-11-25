<?php

require_once __DIR__ . '/../config/db.php';

class Categoria
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Lista todas as categorias cadastradas
    public function listarTodas(): array
    {
        $sql = "
            SELECT
                id_categoria,
                nome

            FROM categoria

            ORDER BY nome ASC
        ";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mantém compatibilidade com outros arquivos
    public function listarAtivas(): array
    {
        return $this->listarTodas();
    }

    // Busca uma categoria pelo ID
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                id_categoria,
                nome

            FROM categoria

            WHERE id_categoria = :id

            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    // Verifica se já existe uma categoria com o mesmo nome
    public function nomeExiste(
        string $nome,
        int $ignorarId = 0
    ): bool {

        $stmt = $this->conn->prepare("
            SELECT COUNT(*)

            FROM categoria

            WHERE LOWER(nome) = LOWER(:nome)

                AND id_categoria <> :id
        ");

        $stmt->execute([
            ':nome' => $nome,
            ':id' => $ignorarId
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Cadastra uma nova categoria
    public function inserir(string $nome): int
    {
        $stmt = $this->conn->prepare("
            INSERT INTO categoria (
                nome
            )

            VALUES (
                :nome
            )
        ");

        $stmt->execute([
            ':nome' => $nome
        ]);

        return (int) $this->conn->lastInsertId();
    }

    // Atualiza uma categoria existente
    public function atualizar(
        int $id,
        string $nome
    ): void {

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