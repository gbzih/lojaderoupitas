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
    // 🔥 CORREÇÃO: Adicionado p.ativo logo após p.estoque no SELECT
    $sql = "
        SELECT p.id_produto, p.categoria_id, p.nome, p.descricao, p.preco, p.estoque, p.ativo,
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
    // Ajustado de 'categoria_id' para bater com o banco se necessário, mantendo o padrão que funciona
    $stmt = $this->conn->prepare("
        INSERT INTO produto (categoria_id, nome, descricao, ativo)
        VALUES (:categoria_id, :nome, :descricao, 1)
    ");
    $stmt->execute([
        ':categoria_id' => $categoriaId,
        ':nome' => $nome,
        ':descricao' => $descricao
    ]);
    return (int)$this->conn->lastInsertId();
}

public function atualizar(int $id, int $categoriaId, string $nome, ?string $descricao): void
{
    // 🔥 CORREÇÃO AQUI: Mudado de 'SET id_categoria' para 'SET categoria_id'
    $stmt = $this->conn->prepare("
        UPDATE produto
        SET categoria_id = :categoria_id, nome = :nome, descricao = :descricao
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
    $stmt = $this->conn->prepare("UPDATE produto SET ativo = :ativo WHERE id_produto = :id");
    
    // Força o ID a ir como INTEIRO
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Força o ATIVO a ir como INTEIRO (1 ou 0)
    $stmt->bindValue(':ativo', $ativo ? 1 : 0, PDO::PARAM_INT);
    
    $stmt->execute();
}

    public function deletar(int $id): bool
    {
        // Ajustado de id = :id para id_produto = :id
        $stmt = $this->conn->prepare("DELETE FROM produto WHERE id_produto = :id");
        return $stmt->execute([':id' => $id]);
    }
}
