<?php

$nome = $_SESSION['usuario_nome'] ?? 'Administrador';

$totalProdutos = $totalProdutos ?? null;
$totalEstoqueBaixo = $totalEstoqueBaixo ?? null;
$totalEntradasMes = $totalEntradasMes ?? null;
$totalVendasMes = $totalVendasMes ?? null;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Painel Administrativo - Geekies</title>

    <link rel="stylesheet"
      href="public/assets/css/style.css">

    <link rel="stylesheet"
      href="public/assets/css/dashboard.css">

</head>

<body class="bodydash">

    <main class="container dashboard-container">

        <!-- Cabeçalho -->
        <header class="topbar">

            <div class="brand">

                <div class="abelha">

                    <h1>Loja Geekies</h1>

                    <small>Painel do Sistema</small>

                </div>

            </div>

            <!-- Identificação do administrador -->
            <div class="pill">

                <span>
                    Logado como

                    <strong>
                        <?= htmlspecialchars(
                            $nome,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>

                    (admin)
                </span>

                <span> - </span>

                <a href="index.php?controller=auth&action=logout"
                   class="dashboard-sair">

                    Sair

                </a>

            </div>

        </header>

        <!-- Painel principal -->
        <section class="card dashboard-card">

            <h2>
                Bem-vindo(a),
                <?= htmlspecialchars(
                    $nome,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>!
            </h2>

            <p class="dashboard-descricao">
                Escolha um módulo para continuar.
            </p>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div class="dashboard-erro" role="alert">

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <!-- Cinco módulos administrativos -->
            <nav class="nav dashboard-nav"
                 aria-label="Módulos administrativos"
                 style="--colunas-menu: 5;">

                <a href="index.php?controller=produto&action=index">
                    Produtos
                </a>

                <a href="index.php?controller=categoria&action=index">
                    Categorias
                </a>

                <a href="index.php?controller=estoque&action=index">
                    Entrada
                </a>

                <a href="index.php?controller=pedido&action=index">
                    Vendas
                </a>

                <a href="index.php?controller=cliente&action=index">
                    Usuários
                </a>

            </nav>

            <!-- Indicadores administrativos -->
            <section class="kpis dashboard-kpis">

                <!-- Estoque baixo -->
                <a class="kpi"
                   href="index.php?controller=estoque&action=index">

                    <div class="label">
                        Estoque baixo
                    </div>

                    <div class="value">

                        <?= $totalEstoqueBaixo !== null
                            ? (int) $totalEstoqueBaixo
                            : '—' ?>

                    </div>

                </a>

                <!-- Entradas do mês -->
                <a class="kpi"
                   href="index.php?controller=entrada&action=index">

                    <div class="label" style="color: rgb(75, 7, 107)">
                        Entradas do mês
                    </div>

                    <div class="value" style="color: rgb(75, 7, 107)">

                        <?= $totalEntradasMes !== null
                            ? (int) $totalEntradasMes
                            : '—' ?>

                    </div>

                </a>

                <!-- Produtos ativos -->
                <a class="kpi"
                   href="index.php?controller=produto&action=index">

                    <div class="label">
                        Produtos
                    </div>

                    <div class="value">

                        <?= $totalProdutos !== null
                            ? (int) $totalProdutos
                            : '—' ?>

                    </div>

                </a>

                <!-- Vendas do mês -->
                <a class="kpi"
                   href="index.php?controller=pedido&action=index">

                    <div class="label">
                        Vendas do mês
                    </div>

                    <div class="value">

                        <?= $totalVendasMes !== null
                            ? (int) $totalVendasMes
                            : '—' ?>

                    </div>

                </a>

            </section>

        </section>

    </main>

</body>

</html>