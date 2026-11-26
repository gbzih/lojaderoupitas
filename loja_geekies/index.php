<?php

session_start();

require_once __DIR__ . '/config/db.php';

Database::getConnection();

// Controller e ação padrão
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'form';

// Validação dos parâmetros
if (!is_string($controller) || !is_string($action)) {
    http_response_code(400);
    exit('Rota inválida.');
}

// Controllers e ações disponíveis
$rotas = [

    'auth' => [
        'classe' => 'AuthController',
        'acoes' => [
            'form',
            'login',
            'dashboard',
            'logout'
        ]
    ],

    'produto' => [
        'classe' => 'ProdutoController',
        'acoes' => [
            'index',
            'salvar',
            'deletar',
            'toggle',
            'removerImagem'
        ]
    ],

    'categoria' => [
        'classe' => 'CategoriaController',
        'acoes' => [
            'index',
            'salvar',
            'deletar'
        ]
    ],

    'estoque' => [
        'classe' => 'EstoqueController',
        'acoes' => [
            'index',
            'salvar',
            'movimentar'
        ]
    ],

    'entrada' => [
        'classe' => 'EntradaController',
        'acoes' => [
            'index',
            'salvar'
        ]
    ],

    'venda' => [
        'classe' => 'VendaController',
        'acoes' => [
            'index'
        ]
    ],

    'pedido' => [
        'classe' => 'PedidoController',
        'acoes' => [
            'index',
            'atualizarStatus'
        ]
    ],

    'pagamento' => [
        'classe' => 'PagamentoController',
        'acoes' => [
            'index'
        ]
    ],

    'cliente' => [
        'classe' => 'ClienteController',
        'acoes' => [
            'index',
            'salvar',
            'excluir'
        ]
    ]

];

// Verifica se o controller existe
if (!isset($rotas[$controller])) {
    http_response_code(404);
    exit('Controller inválido.');
}

// Verifica se a ação está autorizada
if (!in_array(
    $action,
    $rotas[$controller]['acoes'],
    true
)) {
    http_response_code(404);
    exit('Ação inválida.');
}

// Protege as rotas administrativas
if ($controller !== 'auth') {

    if (empty($_SESSION['usuario_id'])) {
        header('Location: index.php?controller=auth&action=form');
        exit;
    }

    if (
        ($_SESSION['perfil'] ?? '') !== 'admin' ||
        empty($_SESSION['administrador_id'])
    ) {
        http_response_code(403);
        exit('Acesso negado.');
    }
}

// Identifica a classe do controller
$classe = $rotas[$controller]['classe'];

$arquivo = __DIR__
    . '/controllers/'
    . $classe
    . '.php';

// Verifica se o arquivo existe
if (!is_file($arquivo)) {
    http_response_code(404);
    exit('Controller ainda não implementado.');
}

require_once $arquivo;

// Verifica se a classe existe
if (!class_exists($classe)) {
    http_response_code(500);
    exit('Classe do controller não encontrada.');
}

// Instancia o controller
$c = new $classe();

// Verifica se o método pode ser executado
if (!is_callable([$c, $action])) {
    http_response_code(404);
    exit('Ação inválida.');
}

// Executa a ação
$c->$action();