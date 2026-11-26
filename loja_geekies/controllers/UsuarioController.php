<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    // Exibe a página de cadastro
    public function create(): void
    {
        require_once __DIR__ . '/../views/cadastro.php';
    }

    // Realiza o cadastro do cliente
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha_plana = $_POST['senha'] ?? '';
        $cpf = trim($_POST['cpf'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');

        // Remove a formatação do CPF
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica os campos obrigatórios
        if (
            $nome === '' ||
            $email === '' ||
            $senha_plana === '' ||
            $cpf === ''
        ) {
            $erro = 'Preencha todos os campos obrigatórios.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        // Validação do nome
        if (mb_strlen($nome) > 100) {
            $erro = 'O nome deve possuir no máximo 100 caracteres.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        // Validação do e-mail
        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            mb_strlen($email) > 100
        ) {
            $erro = 'Informe um e-mail válido.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        // Validação do CPF
        if (!$this->validarCpf($cpf)) {
            $erro = 'Informe um CPF válido.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        // Validação do telefone
        if (mb_strlen($telefone) > 20) {
            $erro = 'Informe um telefone válido.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        // Validação da senha
        if (
            mb_strlen($senha_plana) < 8 ||
            strlen($senha_plana) > 72
        ) {
            $erro = 'A senha deve ter pelo menos 8 caracteres e no máximo 72 bytes.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        $usuarioModel = new Usuario();

        // Verifica se o e-mail já está cadastrado
        if ($usuarioModel->buscarPorEmail($email)) {
            $erro = 'Este e-mail já está cadastrado.';
            require __DIR__ . '/../views/cadastro.php';
            return;
        }

        // Gera o hash da senha
        $senha_hash = password_hash(
            $senha_plana,
            PASSWORD_DEFAULT
        );

        // Cadastra o usuário exclusivamente como cliente
        $sucesso = $usuarioModel->cadastrar(
            $nome,
            $email,
            $senha_hash,
            'cliente',
            [
                'cpf' => $cpf,
                'telefone' => $telefone !== ''
                    ? $telefone
                    : null
            ]
        );

        // Redireciona para a página de login
        if ($sucesso) {
            header(
                'Location: index.php?controller=auth&action=form'
            );
            exit;
        }

        $erro = 'Não foi possível realizar o cadastro. Verifique os dados informados.';

        require __DIR__ . '/../views/cadastro.php';
    }

    // Validação dos dígitos verificadores do CPF
    private function validarCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;

            for ($i = 0; $i < $t; $i++) {
                $soma += (int) $cpf[$i] * (($t + 1) - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }
}