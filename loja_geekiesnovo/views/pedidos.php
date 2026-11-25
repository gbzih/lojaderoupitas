<?php

$pedidos = $pedidos ?? [];

$pedidoSelecionado = $pedidoSelecionado ?? null;

$itens = $itens ?? [];

$pagamentos = $pagamentos ?? [];

$statusFiltro = $statusFiltro ?? '';

$totalPedidos = $totalPedidos ?? 0;

$totalPendentes = $totalPendentes ?? 0;

$totalPagos = $totalPagos ?? 0;

$totalEntregues = $totalEntregues ?? 0;

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

// Protege os textos exibidos
function escaparPedido($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Formata valores monetários
function moedaPedido($valor): string
{
    return 'R$ ' . number_format(
        (float) $valor,
        2,
        ',',
        '.'
    );
}

// Formata o status do pedido
function statusPedido(string $status): string
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

// Define a cor do status
function corStatusPedido(string $status): string
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

// Formata o status do pagamento
function statusPagamentoPedido(string $status): string
{
    $nomes = [
        'pendente' => 'Pendente',
        'aprovado' => 'Aprovado',
        'recusado' => 'Recusado',
        'cancelado' => 'Cancelado'
    ];

    return $nomes[$status] ?? $status;
}

// Formata a forma de pagamento
function formaPagamentoPedido(string $forma): string
{
    $nomes = [
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto'
    ];

    return $nomes[$forma] ?? $forma;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Gerenciamento de Pedidos - Geekies</title>

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
                    Pedidos
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparPedido($nomeUser) ?>
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
                    Total de pedidos
                </small>

                <h2>
                    <?= (int) $totalPedidos ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Aguardando pagamento
                </small>

                <h2>
                    <?= (int) $totalPendentes ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Pagos aguardando envio
                </small>

                <h2>
                    <?= (int) $totalPagos ?>
                </h2>

            </div>

            <div class="kpi">

                <small style="color:black;">
                    Pedidos entregues
                </small>

                <h2>
                    <?= (int) $totalEntregues ?>
                </h2>

            </div>

        </div>

    </div>

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Card esquerdo -->
        <div class="card">

            <h2>Filtrar Pedidos</h2>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= escaparPedido($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Mensagem de sucesso -->
            <?php if (!empty($sucesso)): ?>

                <div
                    role="status"
                    style="color:darkgreen; margin:15px 0;">

                    <?= escaparPedido($sucesso) ?>

                </div>

            <?php endif; ?>

            <!-- Formulário de filtro -->
            <form method="GET" action="index.php">

                <input
                    type="hidden"
                    name="controller"
                    value="pedido">

                <input
                    type="hidden"
                    name="action"
                    value="index">

                <div class="form-group">

                    <label for="status" style="color:black;">
                        Situação do pedido
                    </label>

                    <select
                        class="input"
                        id="status"
                        name="status">

                        <option value="">
                            Todos os pedidos
                        </option>

                        <?php

                        $opcoes = [
                            'pendente',
                            'pago',
                            'enviado',
                            'entregue',
                            'cancelado'
                        ];

                        ?>

                        <?php foreach ($opcoes as $opcao): ?>

                            <option
                                value="<?= escaparPedido($opcao) ?>"
                                <?= $statusFiltro === $opcao
                                    ? 'selected'
                                    : '' ?>>

                                <?= escaparPedido(
                                    statusPedido($opcao)
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
                        href="index.php?controller=pedido&action=index">

                        Limpar

                    </a>

                </div>

            </form>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Detalhes do pedido -->
            <?php if ($pedidoSelecionado): ?>

                <h2>

                    Pedido
                    #<?= (int) $pedidoSelecionado['id_pedido'] ?>

                </h2>

                <!-- Informações do cliente -->
                <h3>Dados do Cliente</h3>

                <p style="color:black;">

                    <strong>Nome:</strong>

                    <?= escaparPedido(
                        $pedidoSelecionado['cliente_nome']
                    ) ?>

                </p>

                <p style="color:black; overflow-wrap:anywhere;">

                    <strong>E-mail:</strong>

                    <?= escaparPedido(
                        $pedidoSelecionado['cliente_email']
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Telefone:</strong>

                    <?= escaparPedido(
                        $pedidoSelecionado['telefone']
                        ?: 'Não informado'
                    ) ?>

                </p>

                <!-- Informações do pedido -->
                <h3>Dados do Pedido</h3>

                <p style="color:black;">

                    <strong>Data:</strong>

                    <?= date(
                        'd/m/Y',
                        strtotime(
                            $pedidoSelecionado['data_pedido']
                        )
                    ) ?>

                </p>

                <p style="color:black;">

                    <strong>Situação:</strong>

                    <span
                        class="tag"
                        style="color:<?= corStatusPedido(
                            $pedidoSelecionado['status']
                        ) ?>;">

                        <?= escaparPedido(
                            statusPedido(
                                $pedidoSelecionado['status']
                            )
                        ) ?>

                    </span>

                </p>

                <p style="color:black;">

                    <strong>Valor total:</strong>

                    <?= moedaPedido(
                        $pedidoSelecionado['valor_total']
                    ) ?>

                </p>

                <hr style="margin:20px 0;
                           border:0;
                           border-top:1px solid #999;">

                <!-- Produtos do pedido -->
                <h3>Produtos Comprados</h3>

                <?php if (empty($itens)): ?>

                    <p style="color:black;">

                        Nenhum item encontrado.

                    </p>

                <?php endif; ?>

                <?php foreach ($itens as $item): ?>

                    <div
                        style="padding:12px;
                               margin-bottom:10px;
                               background:rgba(255,255,255,.5);
                               border-radius:10px;
                               color:black;">

                        <strong>

                            <?= escaparPedido(
                                $item['produto_nome']
                            ) ?>

                        </strong>

                        <p>

                            Quantidade:
                            <?= (int) $item['quantidade'] ?>

                        </p>

                        <p>

                            Preço unitário:

                            <?= moedaPedido(
                                $item['preco_unitario']
                            ) ?>

                        </p>

                        <p>

                            <strong>

                                Subtotal:

                                <?= moedaPedido(
                                    $item['subtotal']
                                ) ?>

                            </strong>

                        </p>

                    </div>

                <?php endforeach; ?>

                <hr style="margin:20px 0;
                           border:0;
                           border-top:1px solid #999;">

                <!-- Informações de pagamento -->
                <h3>Pagamento</h3>

                <?php if (empty($pagamentos)): ?>

                    <p style="color:black;">

                        Nenhum pagamento registrado.

                    </p>

                <?php else: ?>

                    <?php foreach ($pagamentos as $pagamento): ?>

                        <div
                            style="padding:12px;
                                   margin-bottom:10px;
                                   background:rgba(255,255,255,.5);
                                   border-radius:10px;
                                   color:black;">

                            <p>

                                <strong>Forma:</strong>

                                <?= escaparPedido(
                                    formaPagamentoPedido(
                                        $pagamento['forma_pagamento']
                                    )
                                ) ?>

                            </p>

                            <p>

                                <strong>Valor:</strong>

                                <?= moedaPedido(
                                    $pagamento['valor']
                                ) ?>

                            </p>

                            <p>

                                <strong>Status:</strong>

                                <?= escaparPedido(
                                    statusPagamentoPedido(
                                        $pagamento['status_pagamento']
                                    )
                                ) ?>

                            </p>

                            <p>

                                <strong>Data:</strong>

                                <?= !empty(
                                    $pagamento['data_pagamento']
                                )
                                    ? date(
                                        'd/m/Y',
                                        strtotime(
                                            $pagamento['data_pagamento']
                                        )
                                    )
                                    : 'Não informado' ?>

                            </p>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

                <hr style="margin:20px 0;
                           border:0;
                           border-top:1px solid #999;">

                <!-- Atualização do status -->
                <h3>Gerenciar Pedido</h3>

                <?php

                $statusAtual = $pedidoSelecionado['status'];

                $transicoes = [
                    'pendente' => [
                        'cancelado' => 'Cancelar pedido'
                    ],
                    'pago' => [
                        'enviado' => 'Marcar como enviado'
                    ],
                    'enviado' => [
                        'entregue' => 'Marcar como entregue'
                    ]
                ];

                $acoesPermitidas =
                    $transicoes[$statusAtual] ?? [];

                ?>

                <?php if (!empty($acoesPermitidas)): ?>

                    <form
                        method="POST"
                        action="index.php?controller=pedido&action=atualizarStatus">

                        <input
                            type="hidden"
                            name="csrf"
                            value="<?= escaparPedido($csrf ?? '') ?>">

                        <input
                            type="hidden"
                            name="id_pedido"
                            value="<?= (int) $pedidoSelecionado['id_pedido'] ?>">

                        <div class="form-group">

                            <label
                                for="novo_status"
                                style="color:black;">

                                Atualizar situação

                            </label>

                            <select
                                class="input"
                                id="novo_status"
                                name="status"
                                required>

                                <option value="">
                                    Selecione uma ação...
                                </option>

                                <?php foreach (
                                    $acoesPermitidas as $valor => $texto
                                ): ?>

                                    <option
                                        value="<?= escaparPedido($valor) ?>">

                                        <?= escaparPedido($texto) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <details style="margin-top:14px; color:black;">

                            <summary>
                                Confirmar alteração
                            </summary>

                            <p>

                                Verifique a ação selecionada
                                antes de atualizar o pedido.

                            </p>

                            <button
                                class="btn"
                                type="submit"
                                style="color:black;">

                                Atualizar pedido

                            </button>

                        </details>

                    </form>

                <?php else: ?>

                    <p style="color:black;">

                        Não existem alterações disponíveis
                        para a situação atual deste pedido.

                    </p>

                <?php endif; ?>

                <div class="actions" style="margin-top:20px;">

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=pedido&action=index">

                        Fechar detalhes

                    </a>

                </div>

            <?php else: ?>

                <h2>Detalhes do Pedido</h2>

                <p style="color:black;">

                    Selecione um pedido na lista ao lado,
                    clicando em <strong>Visualizar</strong>,
                    para consultar os produtos comprados,
                    os dados do cliente e as informações
                    de pagamento.

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

            <h2>Lista de Pedidos</h2>

            <p style="color:black;">

                <?= count($pedidos) ?> pedido(s) encontrado(s).

            </p>

            <table class="table">

                <thead>

                    <tr>

                        <th style="color:black;">ID</th>

                        <th style="color:black;">Cliente</th>

                        <th style="color:black;">Data</th>

                        <th style="color:black;">Valor total</th>

                        <th style="color:black;">Situação</th>

                        <th style="color:black;">Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($pedidos)): ?>

                        <tr>

                            <td
                                colspan="6"
                                style="text-align:center;
                                       color:black;">

                                Nenhum pedido encontrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($pedidos as $p): ?>

                        <tr>

                            <!-- ID -->
                            <td style="color:black;">

                                #<?= (int) $p['id_pedido'] ?>

                            </td>

                            <!-- Cliente -->
                            <td style="color:black;">

                                <?= escaparPedido(
                                    $p['cliente_nome']
                                ) ?>

                            </td>

                            <!-- Data -->
                            <td style="color:black;">

                                <?= date(
                                    'd/m/Y',
                                    strtotime($p['data_pedido'])
                                ) ?>

                            </td>

                            <!-- Valor -->
                            <td style="color:black;">

                                <strong>

                                    <?= moedaPedido(
                                        $p['valor_total']
                                    ) ?>

                                </strong>

                            </td>

                            <!-- Situação -->
                            <td>

                                <span
                                    class="tag"
                                    style="color:<?= corStatusPedido(
                                        $p['status']
                                    ) ?>;">

                                    <?= escaparPedido(
                                        statusPedido(
                                            $p['status']
                                        )
                                    ) ?>

                                </span>

                            </td>

                            <!-- Ações -->
                            <td>

                                <a
                                    class="btn"
                                    style="color:black;"
                                    href="index.php?controller=pedido&action=index&id=<?= (int) $p['id_pedido'] ?>&amp;status=<?= escaparPedido($statusFiltro) ?>">

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