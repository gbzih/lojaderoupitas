<?php
require_once __DIR__ . '/../models/estoque.php';

class EstoqueController
{
    /**
     * Exibe a lista do estoque, alertas de reposição e item para edição (se informado id)
     */
    public function index(): void
    {
        $this->check();

        $estoqueModel = new estoque();
        
        // Busca a lista completa de posições de estoque vinculadas aos produtos
        $estoques = $estoqueModel->listarComProdutos();

        $editar = null;
        if (isset($_GET['id'])) {
            $editar = $estoqueModel->buscarPorId((int)$_GET['id']);
        }

        require_once __DIR__ . '/../views/estoques.php';
        // Caso sua view tenha outro nome, ajuste acima (ex: estoque.php ou estoque_view.php)
    }

    /**
     * Salva ou atualiza a quantidade em estoque e o estoque mínimo de um item
     */
    public function salvar(): void
    {
    $this->check();
    $this->onlyAdmin(); // Trava de segurança: apenas admin altera

    $idEstoque = (int)($_POST['id_estoque'] ?? 0);
    $quantidade = (int)($_POST['quantidade'] ?? 0);
    $minimo = (int)($_POST['minimo'] ?? 0);

    if ($idEstoque <= 0 || $quantidade < 0 || $minimo < 0) {
        die("Dados inválidos. A quantidade e o estoque mínimo não podem ser negativos.");
    }

    $estoqueModel = new Estoque();
    $estoqueModel->atualizar($idEstoque, $quantidade, $minimo);

    $db = Database::getConnection();
    $stmt = $db->prepare("UPDATE produto SET estoque = :qtd WHERE id_produto = :id");
    $stmt->execute([':qtd' => $quantidade, ':id' => $idEstoque]);

    header("Location: index.php?controller=estoque&action=index");
    exit;
    }

    /**
     * Ajuste rápido de entrada ou saída manual do estoque
     */
    public function movimentar(): void
    {
        $this->check();

        $idEstoque = (int)($_POST['id_estoque'] ?? 0);
        $tipo = $_POST['tipo'] ?? ''; // 'entrada' ou 'saida'
        $qtdMovimentada = (int)($_POST['quantidade'] ?? 0);

        if ($idEstoque <= 0 || $qtdMovimentada <= 0) {
            die("Dados inválidos para movimentação de estoque.");
        }

        $estoqueModel = new estoque();
        $itemAtual = $estoqueModel->buscarPorId($idEstoque);

        if (!$itemAtual) {
            die("Registro de estoque não encontrado.");
        }

        $novaQuantidade = $itemAtual['quantidade'];

        if ($tipo === 'entrada') {
            $novaQuantidade += $qtdMovimentada;
        } elseif ($tipo === 'saida') {
            $novaQuantidade -= $qtdMovimentada;
            if ($novaQuantidade < 0) {
                die("Erro: A saída informada resulta em quantidade negativa.");
            }
        } else {
            die("Tipo de movimentação inválido.");
        }

        $estoqueModel->atualizarQuantidade($idEstoque, $novaQuantidade);

        header("Location: index.php?controller=estoque&action=index");
        exit;
    }

    /**
     * Verifica se o usuário está autenticado na sessão
     */
    private function check(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=form");
            exit;
        }
    }

    /**
     * Restringe o acesso apenas para perfil admin (caso necessário em ações específicas)
     */
    private function onlyAdmin(): void
    {
        if (strtolower($_SESSION['perfil'] ?? '') !== 'admin') {
            die("Acesso negado.");
        }
    }
}