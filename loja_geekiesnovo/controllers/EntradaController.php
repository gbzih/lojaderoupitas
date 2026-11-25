<?php

require_once __DIR__ . '/../models/Entrada.php';
require_once __DIR__ . '/AuthController.php';

class EntradaController
{
    // Exibe a página de entradas
    public function index(): void
    {
        $this->onlyAdmin();

        $entradaModel = new Entrada();

        $entradas = $entradaModel->listarTodas();

        $produtos = $entradaModel->listarProdutos();

        $totalEntradas = $entradaModel->totalEntradas();

        $totalUnidades = $entradaModel->totalUnidades();

        $erro = $_SESSION['entrada_erro'] ?? null;

        $sucesso = $_SESSION['entrada_sucesso'] ?? null;

        unset(
            $_SESSION['entrada_erro'],
            $_SESSION['entrada_sucesso']
        );

        if (empty($_SESSION['entrada_csrf'])) {

            $_SESSION['entrada_csrf'] =
                bin2hex(random_bytes(32));
        }

        $csrf = $_SESSION['entrada_csrf'];

        require_once __DIR__ . '/../views/entradas.php';
    }

    // Registra uma nova entrada
    public function salvar(): void
    {
        $this->onlyAdmin();

        $this->exigirPost();

        $token = $_POST['csrf'] ?? '';

        if (
            !is_string($token) ||
            empty($_SESSION['entrada_csrf']) ||
            !hash_equals($_SESSION['entrada_csrf'], $token)
        ) {

            http_response_code(403);

            exit('Requisição inválida.');
        }

        $idProduto = filter_var(
            $_POST['id_produto'] ?? null,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1,
                    'max_range' => 2147483647
                ]
            ]
        );

        $quantidade = filter_var(
            $_POST['quantidade'] ?? null,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1,
                    'max_range' => 2147483647
                ]
            ]
        );

        if (
            $idProduto === false ||
            $quantidade === false
        ) {

            $this->erro(
                'Selecione um produto e informe uma quantidade válida.'
            );
        }

        try {

            $entradaModel = new Entrada();

            $entradaModel->registrar(
                $idProduto,
                $quantidade
            );

        } catch (PDOException $e) {

            error_log(
                'Erro ao registrar entrada: ' .
                $e->getMessage()
            );

            $this->erro(
                'Não foi possível registrar a entrada.'
            );

        } catch (RuntimeException $e) {

            $this->erro(
                $e->getMessage()
            );
        }

        $_SESSION['entrada_sucesso'] =
            'Entrada registrada e estoque atualizado com sucesso!';

        $this->redirecionar();
    }

    // Restringe o acesso aos administradores
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

    // Armazena uma mensagem de erro
    private function erro(string $mensagem): void
    {
        $_SESSION['entrada_erro'] = $mensagem;

        $this->redirecionar();
    }

    // Redireciona para a página de entradas
    private function redirecionar(): void
    {
        header(
            'Location: index.php?controller=entrada&action=index'
        );

        exit;
    }
}