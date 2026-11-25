<?php

require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/AuthController.php';

class CategoriaController
{
    // Exibe a página administrativa de categorias
    public function index(): void
    {
        $this->onlyAdmin();

        $categoriaModel = new Categoria();

        $categorias = $categoriaModel->listarTodas();

        $editar = null;

        // Busca a categoria para edição
        if (isset($_GET['id'])) {

            $id = (int) $_GET['id'];

            if ($id > 0) {
                $editar = $categoriaModel->buscarPorId($id);
            }
        }

        // Recupera as mensagens da sessão
        $erro = $_SESSION['categoria_erro'] ?? null;

        $sucesso = $_SESSION['categoria_sucesso'] ?? null;

        unset(
            $_SESSION['categoria_erro'],
            $_SESSION['categoria_sucesso']
        );

        require_once __DIR__ . '/../views/categorias.php';
    }

    // Cadastra ou atualiza uma categoria
    public function salvar(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $id = (int) ($_POST['id'] ?? 0);

        $nome = trim($_POST['nome'] ?? '');

        // Validação dos dados
        if (
            $id < 0 ||
            $nome === '' ||
            mb_strlen($nome) > 100
        ) {
            $this->erro(
                'Informe um nome válido para a categoria.',
                $id
            );
        }

        $categoriaModel = new Categoria();

        try {

            // Verifica se a categoria existe durante a edição
            if ($id > 0) {

                if (!$categoriaModel->buscarPorId($id)) {

                    $this->erro('Categoria não encontrada.');
                }
            }

            // Impede categorias com nomes duplicados
            if ($categoriaModel->nomeExiste($nome, $id)) {

                $this->erro(
                    'Já existe uma categoria com esse nome.',
                    $id
                );
            }

            // Atualiza uma categoria existente
            if ($id > 0) {

                $categoriaModel->atualizar(
                    $id,
                    $nome
                );

                $_SESSION['categoria_sucesso'] =
                    'Categoria atualizada com sucesso!';

            } else {

                // Cadastra uma nova categoria
                $categoriaModel->inserir($nome);

                $_SESSION['categoria_sucesso'] =
                    'Categoria cadastrada com sucesso!';
            }

        } catch (PDOException $e) {

            error_log(
                'Erro ao salvar categoria: ' . $e->getMessage()
            );

            $this->erro(
                'Não foi possível salvar a categoria.',
                $id
            );
        }

        $this->redirecionar();
    }

    // Permite acesso somente ao administrador
    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }

    // Permite somente requisições POST
    private function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            exit('Método não permitido.');
        }
    }

    // Armazena a mensagem de erro
    private function erro(
        string $mensagem,
        int $id = 0
    ): void {

        $_SESSION['categoria_erro'] = $mensagem;

        $this->redirecionar($id);
    }

    // Redireciona para a página de categorias
    private function redirecionar(int $id = 0): void
    {
        $url = 'index.php?controller=categoria&action=index';

        if ($id > 0) {
            $url .= '&id=' . $id;
        }

        header('Location: ' . $url);

        exit;
    }
}