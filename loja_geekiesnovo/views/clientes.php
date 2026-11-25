<?php

$clientes = $clientes ?? [];

$clienteSelecionado =
    $clienteSelecionado ?? null;

$pedidosCliente = $pedidosCliente ?? [];

$resumoCliente = $resumoCliente ?? [];

$totalClientes = $totalClientes ?? 0;

$totalComPedidos = $totalComPedidos ?? 0;

$totalComCompras = $totalComCompras ?? 0;

$totalSemPedidos = $totalSemPedidos ?? 0;

$busca = $busca ?? '';

$nomeUser =
    $_SESSION['usuario_nome'] ?? 'Administrador';

// Protege os textos exibidos
function escaparCliente($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Formata valores monetários
function moedaCliente($valor): string
{
    return 'R$ ' . number_format(
        (float) $valor,
        2,
        ',',
        '.'
    );
}

// Formata o CPF
function formatarCpfCliente(string $cpf): string
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11) {
        return 'CPF inválido';
    }

    return substr($cpf, 0, 3) . '.'
        . substr($cpf, 3, 3) . '.'
        . substr($cpf, 6, 3) . '-'
        . substr($cpf, 9, 2);
}

// Oculta parte do CPF na listagem geral
function ocultarCpfCliente(string $cpf): string
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11) {
        return 'CPF inválido';
    }

    return '***.***.'
        . substr($cpf, 6, 3)
        . '-**';
}

// Formata a situação do pedido
function statusPedidoCliente(string $status): string
{
    $nomes = [
        'pendente' => 'Aguardando pagamento',
        'pago' => 'Pagamento aprovado',
        'enviado' => 'Enviado',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado'
    ];

    return $nomes[$status] ?? $status;
}

// Define a cor da situação do pedido
function corPedidoCliente(string $status): string
{
    $cores = [
        'pendente' => '#8b4500',
        'pago' => '#166534',
        'enviado' => '#1d4ed8',
        'entregue' => '#166534',
        'cancelado' => '#8b0000'
    ];

    return $cores[$status] ?? '#333333';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Gerenciamento de Clientes - Geekies</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="public/assets/css/style.css">

</head>

<body class="pagina-produtos">

    <!-- Cabeçalho -->
    <header class="header">

        <div class="container header-inner">

            <div class="cabecaproduto">

                <strong>Loja Geekies</strong>

                <span class="badge">
                    Clientes
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparCliente($nomeUser) ?>
                </strong>

                <span style="margin:8px;">|</span>

                <a
                    href="index.php?controller=auth&action=dashboard"
                    style="margin-right:8px; color:black;">

                    Início

                </a>

                <span style="margin:4px;">•</span>

                <a
                    href="index.php?controller=auth&action=logout"
                    style="color:black;">

                    Sair

                </a>

            </div>

        </div>

    </header>

    <!-- Indicadores gerais -->
    <div class="container" style="padding:20px 0;">

        <div class="kpis">

            <div class="kpi">

                <small style="color:black;">
                    Clientes cadastrados
                </small>

                <h2>
                    <?= (int) $totalClientes ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Clientes com pedidos
                </small>

                <h2>
                    <?= (int) $totalComPedidos ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Clientes com compras pagas
                </small>

                <h2>
                    <?= (int) $totalComCompras ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Clientes sem pedidos
                </small>

                <h2>
                    <?= (int) $totalSemPedidos ?>
                </h2>

            </div>

        </div>

    </div>

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Card esquerdo -->
        <div class="card">

            <h2>Pesquisar Clientes</h2>

            <p style="color:black;">

                Pesquise os clientes cadastrados
                na loja virtual.

            </p>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= escaparCliente($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Formulário de pesquisa -->
            <form method="GET" action="index.php">

                <input
                    type="hidden"
                    name="controller"
                    value="cliente">

                <input
                    type="hidden"
                    name="action"
                    value="index">

                <div class="form-group">

                    <label for="busca" style="color:black;">
                        Nome, e-mail ou CPF
                    </label>

                    <input
                        class="input"
                        type="search"
                        id="busca"
                        name="busca"
                        maxlength="150"
                        placeholder="Pesquisar cliente..."
                        value="<?= escaparCliente($busca) ?>">

                </div>

                <div class="actions" style="margin-top:14px;">

                    <button
                        class="btn"
                        type="submit"
                        style="color:black;">

                        Pesquisar

                    </button>

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=cliente&action=index">

                        Limpar

                    </a>

                </div>

            </form>

            <hr style="
                margin:25px 0;
                border:0;
                border-top:1px solid #999;
            ">

            <!-- Dados do cliente selecionado -->
            <?php if ($clienteSelecionado): ?>

                <h2>

                    Cliente
                    #<?= (int) $clienteSelecionado['id_cliente'] ?>

                </h2>

                <h3>Dados Cadastrais</h3>

                <p style="color:black;">

                    <strong>Nome:</strong>

                    <?= escaparCliente(
                        $clienteSelecionado['nome']
                    ) ?>

                </p>

                <p style="color:black; overflow-wrap:anywhere;">

                    <strong>E-mail:</strong>

                    <?= escaparCliente(
                        $clienteSelecionado['email']
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>CPF:</strong>

                    <?= escaparCliente(
                        formatarCpfCliente(
                            $clienteSelecionado['cpf']
                        )
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Telefone:</strong>

                    <?= escaparCliente(
                        $clienteSelecionado['telefone']
                        ?: 'Não informado'
                    ) ?>

                </p>

                <hr style="
                    margin:20px 0;
                    border:0;
                    border-top:1px solid #999;
                ">

                <!-- Resumo das compras -->
                <h3>Resumo das Compras</h3>

                <p style="color:black;">

                    <strong>Total de pedidos:</strong>

                    <?= (int) (
                        $resumoCliente['total_pedidos'] ?? 0
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Pedidos pagos:</strong>

                    <?= (int) (
                        $resumoCliente['pedidos_pagos'] ?? 0
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Pedidos pendentes:</strong>

                    <?= (int) (
                        $resumoCliente['pedidos_pendentes'] ?? 0
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Pedidos cancelados:</strong>

                    <?= (int) (
                        $resumoCliente['pedidos_cancelados'] ?? 0
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Total em compras pagas:</strong>

                    <?= moedaCliente(
                        $resumoCliente['total_compras'] ?? 0
                    ) ?>

                </p>

                <hr style="
                    margin:20px 0;
                    border:0;
                    border-top:1px solid #999;
                ">

                <!-- Histórico de pedidos -->
                <h3>Histórico de Pedidos</h3>

                <?php if (empty($pedidosCliente)): ?>

                    <p style="color:black;">

                        Este cliente ainda não realizou
                        nenhum pedido.

                    </p>

                <?php endif; ?>

                <?php foreach ($pedidosCliente as $pedido): ?>

                    <div style="
                        padding:12px;
                        margin-bottom:12px;
                        background:rgba(255,255,255,.5);
                        border-radius:10px;
                        color:black;
                    ">

                        <strong>

                            Pedido
                            #<?= (int) $pedido['id_pedido'] ?>

                        </strong>

                        <p>

                            Data:

                            <?= date(
                                'd/m/Y',
                                strtotime(
                                    $pedido['data_pedido']
                                )
                            ) ?>

                        </p>

                        <p>

                            Valor:

                            <strong>

                                <?= moedaCliente(
                                    $pedido['valor_total']
                                ) ?>

                            </strong>

                        </p>

                        <p>

                            Situação:

                            <span
                                class="tag"
                                style="color:<?= corPedidoCliente(
                                    $pedido['status']
                                ) ?>;">

                                <?= escaparCliente(
                                    statusPedidoCliente(
                                        $pedido['status']
                                    )
                                ) ?>

                            </span>

                        </p>

                        <a
                            class="btn"
                            style="color:black; display:inline-block;"
                            href="index.php?controller=pedido&action=index&id=<?= (int) $pedido['id_pedido'] ?>">

                            Visualizar pedido

                        </a>

                    </div>

                <?php endforeach; ?>

                <div class="actions" style="margin-top:20px;">

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=cliente&action=index">

                        Fechar detalhes

                    </a>

                </div>

            <?php else: ?>

                <h2>Detalhes do Cliente</h2>

                <p style="color:black;">

                    Selecione um cliente na lista ao lado,
                    clicando em <strong>Visualizar</strong>,
                    para consultar seus dados cadastrais
                    e seu histórico de compras.

                </p>

            <?php endif; ?>

            <!-- Navegação -->
            <div class="actions" style="margin-top:20px;">

                <a
                    class="btn"
                    style="color:black;"
                    href="index.php?controller=auth&action=dashboard">

                    Voltar ao Dashboard

                </a>

            </div>

        </div>

        <!-- Card direito -->
        <div class="card">

            <h2>Lista de Clientes</h2>

            <p style="color:black;">

                <?= count($clientes) ?>
                cliente(s) encontrado(s).

            </p>

            <table class="table">

                <thead>

                    <tr>

                        <th style="color:black;">ID</th>

                        <th style="color:black;">Nome</th>

                        <th style="color:black;">E-mail</th>

                        <th style="color:black;">CPF</th>

                        <th style="color:black;">Pedidos</th>

                        <th style="color:black;">Total em compras</th>

                        <th style="color:black;">Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($clientes)): ?>

                        <tr>

                            <td
                                colspan="7"
                                style="text-align:center; color:black;">

                                Nenhum cliente encontrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($clientes as $c): ?>

                        <tr>

                            <!-- ID -->
                            <td style="color:black;">

                                #<?= (int) $c['id_cliente'] ?>

                            </td>

                            <!-- Nome -->
                            <td style="color:black;">

                                <?= escaparCliente(
                                    $c['nome']
                                ) ?>

                            </td>

                            <!-- E-mail -->
                            <td style="color:black;">

                                <?= escaparCliente(
                                    $c['email']
                                ) ?>

                            </td>

                            <!-- CPF -->
                            <td style="color:black;">

                                <?= escaparCliente(
                                    ocultarCpfCliente(
                                        $c['cpf']
                                    )
                                ) ?>

                            </td>

                            <!-- Total de pedidos -->
                            <td style="color:black;">

                                <?= (int) $c['total_pedidos'] ?>

                            </td>

                            <!-- Total em compras -->
                            <td style="color:black;">

                                <strong>

                                    <?= moedaCliente(
                                        $c['total_compras']
                                    ) ?>

                                </strong>

                            </td>

                            <!-- Ações -->
                            <td>

                                <a
                                    class="btn"
                                    style="color:black;"
                                    href="index.php?controller=cliente&action=index&id=<?= (int) $c['id_cliente'] ?>&amp;busca=<?= escaparCliente(rawurlencode($busca)) ?>">

                                    Visualizar

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>