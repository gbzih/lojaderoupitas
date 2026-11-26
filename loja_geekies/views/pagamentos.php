<?php

$pagamentos = $pagamentos ?? [];

$pagamentoSelecionado =
    $pagamentoSelecionado ?? null;

$resumoFormas = $resumoFormas ?? [];

$statusFiltro = $statusFiltro ?? '';

$totalPagamentos = $totalPagamentos ?? 0;

$totalAprovados = $totalAprovados ?? 0;

$totalPendentes = $totalPendentes ?? 0;

$totalRecusados = $totalRecusados ?? 0;

$totalCancelados = $totalCancelados ?? 0;

$totalRecebido = $totalRecebido ?? 0;

$valorPendente = $valorPendente ?? 0;

$nomeUser =
    $_SESSION['usuario_nome'] ?? 'Administrador';

// Protege os textos exibidos
function escaparPagamento($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Formata valores monetários
function moedaPagamento($valor): string
{
    return 'R$ ' . number_format(
        (float) $valor,
        2,
        ',',
        '.'
    );
}

// Formata o status do pagamento
function nomeStatusPagamento(string $status): string
{
    $nomes = [
        'pendente' => 'Pendente',
        'aprovado' => 'Aprovado',
        'recusado' => 'Recusado',
        'cancelado' => 'Cancelado'
    ];

    return $nomes[$status] ?? $status;
}

// Define a cor do status
function corStatusPagamento(string $status): string
{
    $cores = [
        'pendente' => '#8b4500',
        'aprovado' => '#166534',
        'recusado' => '#8b0000',
        'cancelado' => '#555555'
    ];

    return $cores[$status] ?? '#333333';
}

// Formata a forma de pagamento
function nomeFormaPagamento(string $forma): string
{
    $formas = [
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto'
    ];

    return $formas[$forma] ?? $forma;
}

// Formata o status do pedido relacionado
function nomeStatusPedidoPagamento(
    string $status
): string {

    $nomes = [
        'pendente' => 'Aguardando pagamento',
        'pago' => 'Pagamento aprovado',
        'enviado' => 'Enviado',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado'
    ];

    return $nomes[$status] ?? $status;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Gerenciamento de Pagamentos - Geekies</title>

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
                    Pagamentos
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparPagamento($nomeUser) ?>
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
                    Total de pagamentos
                </small>

                <h2>
                    <?= (int) $totalPagamentos ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Pagamentos aprovados
                </small>

                <h2>
                    <?= (int) $totalAprovados ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Pagamentos pendentes
                </small>

                <h2>
                    <?= (int) $totalPendentes ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Total recebido (simulado)
                </small>

                <h2>
                    <?= moedaPagamento($totalRecebido) ?>
                </h2>

            </div>

        </div>

    </div>

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Card esquerdo -->
        <div class="card">

            <h2>Filtrar Pagamentos</h2>

            <p style="color:black;">

                Consulte os pagamentos realizados
                pelos clientes da loja virtual.

            </p>

            <!-- Aviso de simulação -->
            <div style="
                padding:12px;
                background:rgba(255,255,255,.5);
                border-radius:10px;
                color:black;
                margin-bottom:15px;
            ">

                <strong>Ambiente de simulação</strong>

                <p style="margin-bottom:0;">

                    Todos os pagamentos são fictícios.
                    Nenhuma transação financeira real
                    é realizada.

                </p>

            </div>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= escaparPagamento($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Formulário de filtro -->
            <form method="GET" action="index.php">

                <input
                    type="hidden"
                    name="controller"
                    value="pagamento">

                <input
                    type="hidden"
                    name="action"
                    value="index">

                <div class="form-group">

                    <label for="status" style="color:black;">
                        Situação do pagamento
                    </label>

                    <select
                        class="input"
                        id="status"
                        name="status">

                        <option value="">
                            Todos os pagamentos
                        </option>

                        <?php

                        $opcoes = [
                            'pendente',
                            'aprovado',
                            'recusado',
                            'cancelado'
                        ];

                        ?>

                        <?php foreach ($opcoes as $opcao): ?>

                            <option
                                value="<?= escaparPagamento($opcao) ?>"
                                <?= $statusFiltro === $opcao
                                    ? 'selected'
                                    : '' ?>>

                                <?= escaparPagamento(
                                    nomeStatusPagamento($opcao)
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="actions" style="margin-top:14px;">

                    <button
                        class="btn"
                        type="submit"
                        style="color:black;">

                        Filtrar

                    </button>

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=pagamento&action=index">

                        Limpar

                    </a>

                </div>

            </form>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Resumo financeiro -->
            <h2>Resumo Financeiro</h2>

            <p style="color:black;">

                <strong>Valor recebido (simulado):</strong>

                <?= moedaPagamento($totalRecebido) ?>

            </p>

            <p style="color:black;">

                <strong>Valor pendente:</strong>

                <?= moedaPagamento($valorPendente) ?>

            </p>

            <p style="color:black;">

                <strong>Pagamentos recusados:</strong>

                <?= (int) $totalRecusados ?>

            </p>

            <p style="color:black;">

                <strong>Pagamentos cancelados:</strong>

                <?= (int) $totalCancelados ?>

            </p>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Resumo por forma de pagamento -->
            <h2>Formas de Pagamento</h2>

            <small style="color:black;">
                Considera somente pagamentos aprovados.
            </small>

            <?php if (empty($resumoFormas)): ?>

                <p style="color:black;">

                    Nenhum pagamento aprovado.

                </p>

            <?php endif; ?>

            <?php foreach ($resumoFormas as $forma): ?>

                <div style="
                    background:rgba(255,255,255,.5);
                    padding:12px;
                    border-radius:10px;
                    margin-top:12px;
                    color:black;
                ">

                    <strong>

                        <?= escaparPagamento(
                            nomeFormaPagamento(
                                $forma['forma_pagamento']
                            )
                        ) ?>

                    </strong>

                    <p>

                        Pagamentos:

                        <?= (int) $forma['quantidade'] ?>

                    </p>

                    <p style="margin-bottom:0;">

                        Total:

                        <strong>

                            <?= moedaPagamento(
                                $forma['total']
                            ) ?>

                        </strong>

                    </p>

                </div>

            <?php endforeach; ?>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Detalhes do pagamento -->
            <?php if ($pagamentoSelecionado): ?>

                <h2>

                    Pagamento
                    #<?= (int) $pagamentoSelecionado['id_pagamento'] ?>

                </h2>

                <p style="color:black;">

                    <strong>Pedido:</strong>

                    #<?= (int) $pagamentoSelecionado['id_pedido'] ?>

                </p>

                <p style="color:black;">

                    <strong>Cliente:</strong>

                    <?= escaparPagamento(
                        $pagamentoSelecionado['cliente_nome']
                    ) ?>

                </p>

                <p style="color:black; overflow-wrap:anywhere;">

                    <strong>E-mail:</strong>

                    <?= escaparPagamento(
                        $pagamentoSelecionado['cliente_email']
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Forma de pagamento:</strong>

                    <?= escaparPagamento(
                        nomeFormaPagamento(
                            $pagamentoSelecionado['forma_pagamento']
                        )
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Valor:</strong>

                    <?= moedaPagamento(
                        $pagamentoSelecionado['valor']
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Situação:</strong>

                    <span
                        class="tag"
                        style="color:<?= corStatusPagamento(
                            $pagamentoSelecionado['status_pagamento']
                        ) ?>;">

                        <?= escaparPagamento(
                            nomeStatusPagamento(
                                $pagamentoSelecionado['status_pagamento']
                            )
                        ) ?>

                    </span>

                </p>

                <p style="color:black;">

                    <strong>Data do pagamento:</strong>

                    <?= !empty(
                        $pagamentoSelecionado['data_pagamento']
                    )
                        ? date(
                            'd/m/Y',
                            strtotime(
                                $pagamentoSelecionado['data_pagamento']
                            )
                        )
                        : 'Não informado' ?>

                </p>

                <p style="color:black;">

                    <strong>Situação do pedido:</strong>

                    <?= escaparPagamento(
                        nomeStatusPedidoPagamento(
                            $pagamentoSelecionado['status_pedido']
                        )
                    ) ?>

                </p>

                <div class="actions" style="margin-top:20px;">

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=pedido&action=index&id=<?= (int) $pagamentoSelecionado['id_pedido'] ?>">

                        Visualizar pedido

                    </a>

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=pagamento&action=index">

                        Fechar detalhes

                    </a>

                </div>

            <?php else: ?>

                <h2>Detalhes do Pagamento</h2>

                <p style="color:black;">

                    Selecione um pagamento na lista
                    ao lado para consultar suas
                    informações completas.

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

            <h2>Histórico de Pagamentos</h2>

            <p style="color:black;">

                <?= count($pagamentos) ?>
                pagamento(s) encontrado(s).

            </p>

            <table class="table">

                <thead>

                    <tr>

                        <th style="color:black;">ID</th>

                        <th style="color:black;">Pedido</th>

                        <th style="color:black;">Cliente</th>

                        <th style="color:black;">Forma</th>

                        <th style="color:black;">Valor</th>

                        <th style="color:black;">Data</th>

                        <th style="color:black;">Situação</th>

                        <th style="color:black;">Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($pagamentos)): ?>

                        <tr>

                            <td
                                colspan="8"
                                style="text-align:center;
                                       color:black;">

                                Nenhum pagamento encontrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($pagamentos as $pg): ?>

                        <tr>

                            <!-- ID -->
                            <td style="color:black;">

                                #<?= (int) $pg['id_pagamento'] ?>

                            </td>

                            <!-- Pedido -->
                            <td style="color:black;">

                                #<?= (int) $pg['id_pedido'] ?>

                            </td>

                            <!-- Cliente -->
                            <td style="color:black;">

                                <?= escaparPagamento(
                                    $pg['cliente_nome']
                                ) ?>

                            </td>

                            <!-- Forma de pagamento -->
                            <td style="color:black;">

                                <?= escaparPagamento(
                                    nomeFormaPagamento(
                                        $pg['forma_pagamento']
                                    )
                                ) ?>

                            </td>

                            <!-- Valor -->
                            <td style="color:black;">

                                <strong>

                                    <?= moedaPagamento(
                                        $pg['valor']
                                    ) ?>

                                </strong>

                            </td>

                            <!-- Data -->
                            <td style="color:black;">

                                <?= !empty($pg['data_pagamento'])
                                    ? date(
                                        'd/m/Y',
                                        strtotime(
                                            $pg['data_pagamento']
                                        )
                                    )
                                    : '—' ?>

                            </td>

                            <!-- Status -->
                            <td>

                                <span
                                    class="tag"
                                    style="color:<?= corStatusPagamento(
                                        $pg['status_pagamento']
                                    ) ?>;">

                                    <?= escaparPagamento(
                                        nomeStatusPagamento(
                                            $pg['status_pagamento']
                                        )
                                    ) ?>

                                </span>

                            </td>

                            <!-- Ações -->
                            <td>

                                <a
                                    class="btn"
                                    style="color:black;"
                                    href="index.php?controller=pagamento&action=index&id=<?= (int) $pg['id_pagamento'] ?>&amp;status=<?= escaparPagamento($statusFiltro) ?>">

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