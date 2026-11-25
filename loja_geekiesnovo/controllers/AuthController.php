<?php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Dashboard.php';

class AuthController
{
    // Exibe o login administrativo
    public function form(): void
    {
        $this->iniciarSessao();

        if (
            ($_SESSION['perfil'] ?? '') === 'admin' &&
            !empty($_SESSION['usuario_id']) &&
            !empty($_SESSION['administrador_id'])
        ) {
            header(
                'Location: index.php?controller=auth&action=dashboard'
            );
            exit;
        }

        require_once __DIR__ . '/../views/login.php';
    }

    // Realiza exclusivamente o login administrativo
    public function login(): void
    {
        $this->iniciarSessao();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: index.php?controller=auth&action=form'
            );
            exit;
        }

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (!is_string($email) || !is_string($senha)) {
            http_response_code(400);
            exit('Dados inválidos.');
        }

        $email = trim($email);

        // Valida os campos obrigatórios
        if ($email === '' || $senha === '') {
            $erro = 'Preencha todos os campos.';

            require __DIR__ . '/../views/login.php';
            return;
        }

        // Valida o e-mail
        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            mb_strlen($email) > 100
        ) {
            $erro = 'Informe um e-mail válido.';

            require __DIR__ . '/../views/login.php';
            return;
        }

        $usuarioModel = new Usuario();

        // Busca o usuário
        $user = $usuarioModel->buscarPorEmail($email);

        // Verifica a senha
        if (!$user || !password_verify($senha, $user['senha'])) {

            $erro = 'E-mail ou senha incorretos.';

            require __DIR__ . '/../views/login.php';
            return;
        }

        $idUsuario = (int) $user['id_usuario'];

        // Verifica se possui perfil administrativo
        $admin = $usuarioModel->buscarAdministrador(
            $idUsuario
        );

        $cliente = $usuarioModel->buscarCliente(
            $idUsuario
        );

        // Impede o acesso de clientes e perfis conflitantes
        if (!$admin || $cliente) {

            $erro = 'Esta conta não possui acesso à área administrativa.';

            require __DIR__ . '/../views/login.php';
            return;
        }

        // Renova a sessão por segurança
        session_regenerate_id(true);

        $_SESSION = [];

        // Armazena os dados do administrador
        $_SESSION['usuario_id'] = $idUsuario;

        $_SESSION['usuario_nome'] = $user['nome'];

        $_SESSION['perfil'] = 'admin';

        $_SESSION['administrador_id'] =
            (int) $admin['id_administrador'];

        $_SESSION['nivel_acesso'] =
            $admin['nivelAcesso'];

        // Redireciona para o dashboard
        header(
            'Location: index.php?controller=auth&action=dashboard'
        );

        exit;
    }

    // Exibe o painel administrativo
    public function dashboard(): void
    {
        $this->onlyAdmin();

        $totalProdutos = null;
        $totalEstoqueBaixo = null;

        try {

            $dashboardModel = new Dashboard();

            $totalProdutos =
                $dashboardModel->totalProdutos();

            $totalEstoqueBaixo =
                $dashboardModel->totalEstoqueBaixo();

        } catch (Exception $e) {

            $erro = 'Não foi possível carregar os indicadores do painel.';

            error_log(
                'Erro no dashboard: ' . $e->getMessage()
            );
        }

        require_once __DIR__ . '/../views/dashboard.php';
    }

    // Encerra a sessão administrativa
    public function logout(): void
    {
        $this->iniciarSessao();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header(
            'Location: index.php?controller=auth&action=form'
        );

        exit;
    }

    // Verifica se existe uma sessão autenticada
    public function check(): void
    {
        $this->iniciarSessao();

        if (empty($_SESSION['usuario_id'])) {

            header(
                'Location: index.php?controller=auth&action=form'
            );

            exit;
        }
    }

    // Permite acesso somente ao administrador
    public function onlyAdmin(): void
    {
        $this->check();

        if (
            ($_SESSION['perfil'] ?? '') !== 'admin' ||
            empty($_SESSION['administrador_id'])
        ) {
            http_response_code(403);

            exit('Acesso negado.');
        }
    }

    // Reservado para a futura loja virtual
    public function onlyCliente(): void
    {
        $this->iniciarSessao();

        if (
            empty($_SESSION['usuario_id']) ||
            ($_SESSION['perfil'] ?? '') !== 'cliente' ||
            empty($_SESSION['cliente_id'])
        ) {
            http_response_code(403);

            exit('Acesso negado.');
        }
    }

    // Inicia a sessão
    private function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}