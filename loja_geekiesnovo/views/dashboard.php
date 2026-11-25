<?php

$nome = $_SESSION['usuario_nome'] ?? 'Administrador';

$perfil = $_SESSION['perfil'] ?? '';

$nivelAcesso = $_SESSION['nivel_acesso'] ?? '';

$totalProdutos = $totalProdutos ?? null;

$totalEstoqueBaixo = $totalEstoqueBaixo ?? null;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>Painel Administrativo - Geekies</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="public/assets/css/style.css">
</head>

<body class="bodydash">

    <div class="container">

        <!-- Cabeçalho -->
        <div class="topbar">

            <div class="brand">

                <div class="abelha">

                    <h1>Loja Geekies</h1>

                    <small style="color:black;">
                        Painel Administrativo
                    </small>

                </div>

            </div>

            <!-- Informações do administrador -->
            <div class="pill" style="color:black;">

                Logado como:

                <strong style="color:purple;">
                    <?= htmlspecialchars(
                        $nome,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </strong>

                (Administrador)

                •

                <a
                    style="color:black;"
                    href="index.php?controller=auth&action=logout">

                    Sair

                </a>

            </div>

        </div>

        <!-- Conteúdo principal -->
        <div class="card">

            <h2 style="margin-top:0;">

                Bem-vindo(a),
                <?= htmlspecialchars(
                    $nome,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>!

            </h2>

            <p style="color:black; margin-top:6px;">
                Escolha um módulo para gerenciar a loja.
            </p>

            <?php if (!empty($erro)): ?>

                <div role="alert"
                     style="color:#8b0000; margin:15px 0;">

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <!-- Navegação administrativa -->
            <div class="nav">

                <a
                    style="color:black;"
                    href="index.php?controller=produto&action=index">

                    Produtos / Categorias

                </a>

                <a
                    style="color:black;"
                    href="index.php?controller=estoque&action=index">

                    Estoque

                </a>

            </div>

            <!-- Indicadores administrativos -->
            <div class="kpis">

                <div class="kpi">

                    <div class="label" style="color:black;">
                        Estoque baixo
                    </div>

                    <div class="value" style="color:black;">

                        <?= $totalEstoqueBaixo !== null
                            ? (int) $totalEstoqueBaixo
                            : '—' ?>

                    </div>

                </div>

                <div class="kpi">

                    <div class="label" style="color:black;">
                        Produtos ativos
                    </div>

                    <div class="value" style="color:black;">

                        <?= $totalProdutos !== null
                            ? (int) $totalProdutos
                            : '—' ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>