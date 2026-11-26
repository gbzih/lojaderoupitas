<?php

$pedidos = $pedidos ?? [];
$pedidoSelecionado = $pedidoSelecionado ?? null;
$itens = $itens ?? [];
$pagamentos = $pagamentos ?? [];
$statusFiltro = $statusFiltro ?? '';

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

// Protege os dados exibidos no HTML
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
        'pendente' => 'Pendente',
        'pago' => 'Pago',
        'enviado' => 'Enviado',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado'
    ];

    return $nomes[$status] ?? $status;
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
function formaPagamentoPedido(?string $forma): string
{
    $nomes = [
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto'
    ];

    if (empty($forma)) {
        return 'Não informado';
    }

    return $nomes[$forma] ?? $forma;
}

// Formata as datas
function dataPedido($data): string
{
    if (empty($data)) {
        return 'Não informado';
    }

    $timestamp = strtotime($data);

    return $timestamp !== false
        ? date('d/m/Y', $timestamp)
        : 'Não informado';
}

// Monta a URL da página
function urlPedido(
    string $status = '',
    int $id = 0
): string {

    $url = 'index.php?controller=pedido&action=index';

    if ($status !== '') {
        $url .= '&status=' . urlencode($status);
    }

    if ($id > 0) {
        $url .= '&id=' . $id;
    }

    return $url;
}

// Filtros disponíveis
$filtros = [
    '' => 'Todos',
    'pendente' => 'Pendente',
    'pago' => 'Pago',
    'enviado' => 'Enviado',
    'entregue' => 'Entregue',
    'cancelado' => 'Cancelado'
];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Vendas - Geekies</title>

    <link rel="stylesheet"
          href="public/assets/css/style.css?v=20261126">

</head>

<body class="pagina-vendas">

    <!-- Cabeçalho -->

    <header class="header">

        <div class="container header-inner">

            <div class="cabecaproduto">

                <strong>Loja Geekies</strong>

                <span class="badge">Vendas</span>

                <a href="index.php?controller=auth&action=dashboard">
                    Voltar
                </a>

            </div>

            <div class="user">

                <span>Olá,</span>

                <strong>
                    <?= escaparPedido($nomeUser) ?>
                </strong>

                <span>|</span>

                <a href="index.php?controller=auth&action=logout">
                    Sair
                </a>

            </div>

        </div>

    </header>

    <!-- Conteúdo principal -->

    <main class="vendas-container">

        <section class="vendas-card">

            <!-- Mensagens de erro -->

            <?php if (!empty($erro)): ?>

                <div class="vendas-mensagem vendas-erro"
                     role="alert">

                    <?= escaparPedido($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Mensagens de sucesso -->

            <?php if (!empty($sucesso)): ?>

                <div class="vendas-mensagem vendas-sucesso"
                     role="status">

                    <?= escaparPedido($sucesso) ?>

                </div>

            <?php endif; ?>

            <?php if (!$pedidoSelecionado): ?>

                <!-- LISTAGEM DE PEDIDOS -->

                <h2>Vendas</h2>

                <!-- Filtros -->

                <nav class="vendas-filtros"
                     aria-label="Filtrar pedidos">

                    <?php foreach ($filtros as $valor => $texto): ?>

                        <a
                            class="vendas-filtro <?= $statusFiltro === $valor
                                ? 'vendas-filtro-ativo'
                                : '' ?>"

                            <?= $statusFiltro === $valor
                                ? 'aria-current="true"'
                                : '' ?>

                            href="<?= escaparPedido(
                                urlPedido($valor)
                            ) ?>">

                            <?= escaparPedido($texto) ?>

                        </a>

                    <?php endforeach; ?>

                </nav>

                <!-- Tabela de pedidos -->

                <div class="vendas-tabela-wrapper">

                    <table class="vendas-tabela">

                        <thead>

                            <tr>
                                <th>Pedido</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Pagamento</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($pedidos)): ?>

                                <tr>

                                    <td colspan="7"
                                        class="vendas-vazio">

                                        Nenhum pedido encontrado.

                                    </td>

                                </tr>

                            <?php endif; ?>

                            <?php foreach ($pedidos as $p): ?>

                                <tr>

                                    <td>
                                        #<?= (int) $p['id_pedido'] ?>
                                    </td>

                                    <td>
                                        <?= escaparPedido(
                                            $p['cliente_nome']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= dataPedido(
                                            $p['data_pedido']
                                        ) ?>
                                    </td>

                                    <td>

                                        <strong>

                                            <?= moedaPedido(
                                                $p['valor_total']
                                            ) ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <?= escaparPedido(
                                            formaPagamentoPedido(
                                                $p['forma_pagamento'] ?? null
                                            )
                                        ) ?>

                                    </td>

                                    <td>

                                        <span class="vendas-status">

                                            <?= escaparPedido(
                                                statusPedido(
                                                    $p['status']
                                                )
                                            ) ?>

                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            class="vendas-botao"
                                            href="<?= escaparPedido(
                                                urlPedido(
                                                    $statusFiltro,
                                                    (int) $p['id_pedido']
                                                )
                                            ) ?>">

                                            Detalhes

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <p class="vendas-contagem">

                    <?= count($pedidos) ?>
                    pedido(s) encontrado(s).

                </p>

            <?php else: ?>

                <!-- DETALHES DO PEDIDO -->

                <div class="vendas-detalhes-topo">

                    <h2>

                        Pedido
                        #<?= (int) $pedidoSelecionado['id_pedido'] ?>

                    </h2>

                    <a
                        class="vendas-botao"
                        href="<?= escaparPedido(
                            urlPedido($statusFiltro)
                        ) ?>">

                        Voltar à lista

                    </a>

                </div>

                <!-- Informações gerais -->

                <div class="vendas-informacoes">

                    <!-- Dados do cliente -->

                    <div class="vendas-bloco">

                        <h3>Cliente</h3>

                        <p>

                            <strong>Nome:</strong>

                            <?= escaparPedido(
                                $pedidoSelecionado['cliente_nome']
                            ) ?>

                        </p>

                        <p>

                            <strong>E-mail:</strong>

                            <?= escaparPedido(
                                $pedidoSelecionado['cliente_email']
                            ) ?>

                        </p>

                        <p>

                            <strong>Telefone:</strong>

                            <?= escaparPedido(
                                $pedidoSelecionado['telefone']
                                ?: 'Não informado'
                            ) ?>

                        </p>

                    </div>

                    <!-- Dados do pedido -->

                    <div class="vendas-bloco">

                        <h3>Pedido</h3>

                        <p>

                            <strong>Data:</strong>

                            <?= dataPedido(
                                $pedidoSelecionado['data_pedido']
                            ) ?>

                        </p>

                        <p>

                            <strong>Status:</strong>

                            <span class="vendas-status">

                                <?= escaparPedido(
                                    statusPedido(
                                        $pedidoSelecionado['status']
                                    )
                                ) ?>

                            </span>

                        </p>

                        <p>

                            <strong>Total:</strong>

                            <?= moedaPedido(
                                $pedidoSelecionado['valor_total']
                            ) ?>

                        </p>

                    </div>

                </div>

                <!-- Produtos do pedido -->

                <h3 class="vendas-subtitulo">
                    Produtos do pedido
                </h3>

                <div class="vendas-tabela-wrapper">

                    <table class="vendas-tabela vendas-tabela-detalhes">

                        <thead>

                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço unitário</th>
                                <th>Subtotal</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($itens)): ?>

                                <tr>

                                    <td colspan="4"
                                        class="vendas-vazio">

                                        Nenhum produto encontrado.

                                    </td>

                                </tr>

                            <?php endif; ?>

                            <?php foreach ($itens as $item): ?>

                                <tr>

                                    <td>

                                        <?= escaparPedido(
                                            $item['produto_nome']
                                        ) ?>

                                    </td>

                                    <td>
                                        <?= (int) $item['quantidade'] ?>
                                    </td>

                                    <td>

                                        <?= moedaPedido(
                                            $item['preco_unitario']
                                        ) ?>

                                    </td>

                                    <td>

                                        <?= moedaPedido(
                                            $item['subtotal']
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <!-- Informações de pagamento -->

                <h3 class="vendas-subtitulo">
                    Pagamento
                </h3>

                <div class="vendas-tabela-wrapper">

                    <table class="vendas-tabela vendas-tabela-detalhes">

                        <thead>

                            <tr>
                                <th>Forma</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($pagamentos)): ?>

                                <tr>

                                    <td colspan="4"
                                        class="vendas-vazio">

                                        Nenhum pagamento registrado.

                                    </td>

                                </tr>

                            <?php endif; ?>

                            <?php foreach ($pagamentos as $pg): ?>

                                <tr>

                                    <td>

                                        <?= escaparPedido(
                                            formaPagamentoPedido(
                                                $pg['forma_pagamento']
                                            )
                                        ) ?>

                                    </td>

                                    <td>

                                        <?= moedaPedido(
                                            $pg['valor']
                                        ) ?>

                                    </td>

                                    <td>

                                        <span class="vendas-status">

                                            <?= escaparPedido(
                                                statusPagamentoPedido(
                                                    $pg['status_pagamento']
                                                )
                                            ) ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?= dataPedido(
                                            $pg['data_pagamento']
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <!-- Gerenciamento do pedido -->

                <?php

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

                $acoesPermitidas = $transicoes[
                    $pedidoSelecionado['status']
                ] ?? [];

                ?>

                <div class="vendas-gerenciamento">

                    <h3>Gerenciar pedido</h3>

                    <?php if (!empty($acoesPermitidas)): ?>

                        <?php foreach (
                            $acoesPermitidas as $valor => $texto
                        ): ?>

                            <details class="vendas-confirmacao">

                                <summary class="vendas-botao">

                                    <?= escaparPedido($texto) ?>

                                </summary>

                                <form
                                    method="POST"
                                    action="index.php?controller=pedido&action=atualizarStatus">

                                    <input
                                        type="hidden"
                                        name="csrf"
                                        value="<?= escaparPedido(
                                            $csrf ?? ''
                                        ) ?>">

                                    <input
                                        type="hidden"
                                        name="id_pedido"
                                        value="<?= (int)
                                            $pedidoSelecionado['id_pedido'] ?>">

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="<?= escaparPedido($valor) ?>">

                                    <input
                                        type="hidden"
                                        name="status_filtro"
                                        value="<?= escaparPedido(
                                            $statusFiltro
                                        ) ?>">

                                    <p>
                                        Deseja confirmar esta alteração?
                                    </p>

                                    <button
                                        type="submit"
                                        class="vendas-botao vendas-botao-principal">

                                        Confirmar alteração

                                    </button>

                                </form>

                            </details>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p>

                            Não existem alterações disponíveis
                            para a situação atual deste pedido.

                        </p>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>