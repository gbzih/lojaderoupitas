<?php
require_once __DIR__ . '/../config/db.php';

class Produto
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

   public function listarComCategoria(bool $somenteAtivos = false): array
{
    // Removido p.ativo do SELECT
    $sql = "
        SELECT p.id_produto, p.categoria_id, p.nome, p.descricao, p.preco, p.estoque,
               c.nome AS categoria_nome
        FROM produto p
        INNER JOIN categoria c ON c.id_categoria = p.categoria_id
    ";

    // Se a tabela categoria tiver a coluna 'ativo', mantemos apenas a checagem dela
    if ($somenteAtivos) {
        $sql .= " WHERE c.ativo = 1 ";
    }

    $sql .= " ORDER BY p.id_produto DESC";
    return $this->conn->query($sql)->fetchAll();
}

    public function buscarPorId(int $id): ?array
    {
        // Ajustado de id = :id para id_produto = :id
        $stmt = $this->conn->prepare("SELECT * FROM produto WHERE id_produto = :id");
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function inserir(int $categoriaId, string $nome, ?string $descricao): int
    {
        // Removido o campo 'ativo' do INSERT
        $stmt = $this->conn->prepare("
            INSERT INTO produto (categoria_id, nome, descricao)
            VALUES (:categoria_id, :nome, :descricao)
        ");
        $stmt->execute([
            ':categoria_id' => $categoriaId,
            ':nome' => $nome,
            ':descricao' => $descricao
        ]);
        return (int)$this->conn->lastInsertId();
    }

    // O método setAtivo foi removido/comentado pois a coluna não existe no banco

    public function atualizar(int $id, int $categoriaId, string $nome, ?string $descricao): void
    {
        // Ajustado os campos id e categoria_id para o padrão com "id_" na frente
        $stmt = $this->conn->prepare("
            UPDATE produto
            SET id_categoria = :categoria_id, nome = :nome, descricao = :descricao
            WHERE id_produto = :id
        ");
        $stmt->execute([
            ':id' => $id,
            ':categoria_id' => $categoriaId,
            ':nome' => $nome,
            ':descricao' => $descricao
        ]);
    }

    public function setAtivo(int $id, bool $ativo): void
    {
        // Ajustado de id = :id para id_produto = :id
        $stmt = $this->conn->prepare("UPDATE produto SET ativo = :ativo WHERE id_produto = :id");
        $stmt->execute([
            ':id' => $id,
            ':ativo' => $ativo ? 1 : 0
        ]);
    }

    public function deletar(int $id): bool
    {
        // Ajustado de id = :id para id_produto = :id
        $stmt = $this->conn->prepare("DELETE FROM produto WHERE id_produto = :id");
        return $stmt->execute([':id' => $id]);
    }
}