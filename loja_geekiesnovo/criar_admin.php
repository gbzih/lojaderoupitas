<?php

// Impede a execução pelo navegador
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Acesso negado.');
}

require_once __DIR__ . '/models/Usuario.php';

$usuarioModel = new Usuario();

$conn = Database::getConnection();

// Verifica se já existe um administrador
$totalAdmins = (int) $conn
    ->query("SELECT COUNT(*) FROM administrador")
    ->fetchColumn();

if ($totalAdmins > 0) {
    exit("Já existe um administrador cadastrado.\n");
}

// Dados do administrador
$nome = 'Administrador Geekies';

$email = 'admin@geekies.example';

// Gera uma senha aleatória
$senha = bin2hex(random_bytes(12));

// Verifica se o e-mail já existe
if ($usuarioModel->buscarPorEmail($email)) {
    exit("Este e-mail já está cadastrado.\n");
}

// Protege a senha
$senhaHash = password_hash(
    $senha,
    PASSWORD_DEFAULT
);

// Cadastra o administrador
$idUsuario = $usuarioModel->cadastrar(
    $nome,
    $email,
    $senhaHash,
    'admin',
    [
        'nivelAcesso' => 'admin'
    ]
);

if ($idUsuario) {

    echo "Administrador cadastrado com sucesso!\n";

    echo "Nome: " . $nome . "\n";

    echo "E-mail: " . $email . "\n";

    echo "Senha: " . $senha . "\n";

    echo "ID do usuario: " . $idUsuario . "\n";

    echo "Guarde a senha em um local seguro.\n";

} else {

    echo "Erro ao cadastrar o administrador.\n";
}