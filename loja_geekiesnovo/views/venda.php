<?php

$vendas = $vendas ?? [];

$maisVendidos = $maisVendidos ?? [];

$totalVendas = $totalVendas ?? 0;

$totalUnidades = $totalUnidades ?? 0;

$faturamentoTotal = $faturamentoTotal ?? 0;

$faturamentoHoje = $faturamentoHoje ?? 0;

$totalPeriodo = $totalPeriodo ?? 0;

$unidadesPeriodo = $unidadesPeriodo ?? 0;

$inicio = $inicio ?? '';

$fim = $fim ?? '';

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

// Protege os textos exibidos
function escaparVenda($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Formata valores monetários
function moedaVenda($valor): string
{
    return 'R$ ' . number_format(
        (float) $valor,
        2,
        ',',
        '.'
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Gerenciamento de Vendas - Geekies</title>

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
                    Vendas
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparVenda($nomeUser) ?>
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
    <div
        class="container"
        style="padding:20px 0;">

        <div class="kpis">

            <!-- Registros de venda -->
            <div class="kpi">

                <small style="color:black;">
                    Registros de venda
                </small>

                <h2>
                    <?= (int) $totalVendas ?>
                </h2>

            </div>

            <!-- Unidades vendidas -->
            <div class="kpi">

                <small style="color:black;">
                    Unidades vendidas
                </small>

                <h2>
                    <?= (int) $totalUnidades ?>
                </h2>

            </div>

            <!-- Faturamento total -->
            <div class="kpi">

                <small style="color:black;">
                    Faturamento total
                </small>

                <h2>
                    <?= moedaVenda($faturamentoTotal) ?>
                </h2>

            </div>

            <!-- Faturamento de hoje -->
            <div class="kpi">

                <small style="color:black;">
                    Faturamento de hoje
                </small>

                <h2>
                    <?= moedaVenda($faturamentoHoje) ?>
                </h2>

            </div>

        </div>

    </div>

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Card esquerdo -->
        <div class="card">

            <h2>Filtrar Vendas</h2>

            <p style="color:black;">

                Consulte as vendas realizadas
                em um determinado período.

            </p>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= escaparVenda($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Formulário de filtro -->
            <form method="GET" action="index.php">

                <input
                    type="hidden"
                    name="controller"
                    value="venda">

                <input
                    type="hidden"
                    name="action"
                    value="index">

                <!-- Data inicial -->
                <div class="form-group">

                    <label for="inicio" style="color:black;">
                        Data inicial
                    </label>

                    <input
                        class="input"
                        type="date"
                        id="inicio"
                        name="inicio"
                        required
                        value="<?= escaparVenda($inicio) ?>">

                </div>

                <!-- Data final -->
                <div class="form-group">

                    <label for="fim" style="color:black;">
                        Data final
                    </label>

                    <input
                        class="input"
                        type="date"
                        id="fim"
                        name="fim"
                        required
                        value="<?= escaparVenda($fim) ?>">

                </div>

                <!-- Botões -->
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
                        href="index.php?controller=venda&action=index">

                        Limpar

                    </a>

                </div>

            </form>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Resumo das vendas exibidas -->
            <h2>Resumo da Consulta</h2>

            <p style="color:black;">

                Registros encontrados:

                <strong>
                    <?= count($vendas) ?>
                </strong>

            </p>

            <p style="color:black;">

                Unidades vendidas:

                <strong>
                    <?= (int) $unidadesPeriodo ?>
                </strong>

            </p>

            <p style="color:black;">

                Valor total:

                <strong>
                    <?= moedaVenda($totalPeriodo) ?>
                </strong>

            </p>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Produtos mais vendidos -->
            <h2>Produtos Mais Vendidos</h2>

            <small style="color:black;">
                Ranking geral de vendas.
            </small>

            <?php if (empty($maisVendidos)): ?>

                <p style="color:black;">

                    Ainda não existem vendas registradas.

                </p>

            <?php else: ?>

                <ol style="color:black; padding-left:20px;">

                    <?php foreach ($maisVendidos as $produto): ?>

                        <li style="margin-bottom:15px;">

                            <strong>

                                <?= escaparVenda(
                                    $produto['produto_nome']
                                ) ?>

                            </strong>

                            <div style="margin-top:4px;">

                                <?= (int) $produto['total_vendido'] ?>

                                unidade(s) vendida(s)

                            </div>

                            <small>

                                <?= moedaVenda(
                                    $produto['faturamento']
                                ) ?>

                            </small>

                        </li>

                    <?php endforeach; ?>

                </ol>

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

        <!-- Card direito: histórico -->
        <div class="card">

            <h2>Histórico de Vendas</h2>

            <table class="table">

                <thead>

                    <tr>

                        <th style="color:black;">
                            ID
                        </th>

                        <th style="color:black;">
                            Produto
                        </th>

                        <th style="color:black;">
                            Categoria
                        </th>

                        <th style="color:black;">
                            Quantidade
                        </th>

                        <th style="color:black;">
                            Valor total
                        </th>

                        <th style="color:black;">
                            Data e horário
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($vendas)): ?>

                        <tr>

                            <td
                                colspan="6"
                                style="text-align:center;
                                       color:black;">

                                Nenhuma venda encontrada.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($vendas as $v): ?>

                        <tr>

                            <!-- ID -->
                            <td style="color:black;">

                                #<?= (int) $v['id_venda'] ?>

                            </td>

                            <!-- Produto -->
                            <td style="color:black;">

                                <?= escaparVenda(
                                    $v['produto_nome']
                                ) ?>

                            </td>

                            <!-- Categoria -->
                            <td style="color:black;">

                                <?= escaparVenda(
                                    $v['categoria_nome']
                                ) ?>

                            </td>

                            <!-- Quantidade -->
                            <td style="color:black;">

                                <?= (int) $v['quantidade'] ?>

                                un.

                            </td>

                            <!-- Valor total -->
                            <td style="color:black;">

                                <strong>

                                    <?= moedaVenda(
                                        $v['valor_total']
                                    ) ?>

                                </strong>

                            </td>

                            <!-- Data -->
                            <td style="color:black;">

                                <?= date(
                                    'd/m/Y H:i',
                                    strtotime($v['data_venda'])
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>