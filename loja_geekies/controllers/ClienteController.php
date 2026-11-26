<?php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/AuthController.php';

class ClienteController
{
    // Exibe a lista de usuários
    public function index(): void
    {
        $this->onlyAdmin();

        $model = new Cliente();

        $busca = $_GET['busca'] ?? '';

        if (!is_string($busca)) {
            $busca = '';
        }

        $busca = trim($busca);

        $erro = $_SESSION['usuario_erro'] ?? null;
        $sucesso = $_SESSION['usuario_sucesso'] ?? null;

        unset(
            $_SESSION['usuario_erro'],
            $_SESSION['usuario_sucesso']
        );

        if (mb_strlen($busca) > 150) {
            $busca = '';
            $erro = 'Pesquisa muito longa.';
        }

        $usuarios = $model->listarUsuarios($busca);

        $editar = null;

        // Seleciona um usuário para edição
        if (isset($_GET['id'])) {

            $id = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($id === false) {

                $erro = 'ID inválido.';

            } else {

                $editar = $model->buscarUsuario($id);

                if (!$editar) {

                    $erro = 'Usuário não encontrado.';

                } elseif (
                    $model->ehAdministrador($id) &&
                    $id !== (int) $_SESSION['usuario_id']
                ) {

                    $editar = null;

                    $erro = 'Não é permitido editar outro administrador.';
                }
            }
        }

        // Token de segurança
        if (empty($_SESSION['usuario_csrf'])) {

            $_SESSION['usuario_csrf'] =
                bin2hex(random_bytes(32));
        }

        $csrf = $_SESSION['usuario_csrf'];

        require_once __DIR__ . '/../views/clientes.php';
    }

    // Salva a edição
    public function salvar(): void
    {
        $this->onlyAdmin();
        $this->validarPost();

        $id = filter_var(
            $_POST['id_usuario'] ?? '',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';

        if (!is_string($nome) || !is_string($email)) {
            $this->erro('Dados inválidos.');
        }

        $nome = trim($nome);
        $email = trim($email);

        if ($id === false) {
            $this->erro('ID inválido.');
        }

        if ($nome === '' || mb_strlen($nome) > 100) {
            $this->erro('Informe um nome válido.', $id);
        }

        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            mb_strlen($email) > 100
        ) {
            $this->erro('Informe um e-mail válido.', $id);
        }

        $model = new Cliente();

        if (!$model->buscarUsuario($id)) {
            $this->erro('Usuário não encontrado.');
        }

        if (
            $model->ehAdministrador($id) &&
            $id !== (int) $_SESSION['usuario_id']
        ) {
            $this->erro(
                'Não é permitido editar outro administrador.'
            );
        }

        if ($model->emailExiste($email, $id)) {

            $this->erro(
                'Este e-mail já está sendo utilizado.',
                $id
            );
        }

        try {

            $model->atualizarUsuario(
                $id,
                $nome,
                $email
            );

            if ($id === (int) $_SESSION['usuario_id']) {
                $_SESSION['usuario_nome'] = $nome;
            }

            $_SESSION['usuario_sucesso'] =
                'Usuário atualizado com sucesso.';

        } catch (PDOException $e) {

            error_log($e->getMessage());

            $this->erro(
                'Não foi possível atualizar o usuário.',
                $id
            );
        }

        $this->redirecionar();
    }

    // Exclui um usuário
    public function excluir(): void
    {
        $this->onlyAdmin();
        $this->validarPost();

        $id = filter_var(
            $_POST['id_usuario'] ?? '',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($id === false) {
            $this->erro('ID inválido.');
        }

        if ($id === (int) $_SESSION['usuario_id']) {

            $this->erro(
                'Você não pode excluir sua própria conta.'
            );
        }

        $model = new Cliente();

        try {

            $model->excluirUsuario($id);

            $_SESSION['usuario_sucesso'] =
                'Usuário excluído com sucesso.';

        } catch (PDOException $e) {

            error_log($e->getMessage());

            $this->erro(
                'Não foi possível excluir o usuário.'
            );

        } catch (RuntimeException $e) {

            $this->erro($e->getMessage());
        }

        $this->redirecionar();
    }

    // Protege o acesso administrativo
    private function onlyAdmin(): void
    {
        $auth = new AuthController();
        $auth->onlyAdmin();
    }

    // Valida o formulário
    private function validarPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            exit('Método não permitido.');
        }

        $token = $_POST['csrf'] ?? '';
        $tokenSessao = $_SESSION['usuario_csrf'] ?? '';

        if (
            !is_string($token) ||
            $tokenSessao === '' ||
            !hash_equals($tokenSessao, $token)
        ) {
            http_response_code(403);

            exit('Solicitação inválida.');
        }
    }

    // Mensagem de erro
    private function erro(
        string $mensagem,
        int $id = 0
    ): void
    {
        $_SESSION['usuario_erro'] = $mensagem;

        $this->redirecionar($id);
    }

    // Redirecionamento
    private function redirecionar(int $id = 0): void
    {
        $url = 'index.php?controller=cliente&action=index';

        if ($id > 0) {
            $url .= '&id=' . $id;
        }

        header('Location: ' . $url);

        exit;
    }
}