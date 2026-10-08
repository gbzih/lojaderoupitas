<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$perfil = $_SESSION['perfil'] ?? 'vendedor';

try {
    $db = Database::getConnection();

    // Consulta total de produtos
    $stmt = $db->query("SELECT COUNT(*) as total FROM produto WHERE ativo = 1");
    $resultado = $stmt->fetch();
    $totalProdutos = $resultado['total'] ?? 0;

    // Consulta total de produtos com estoque crítico (menor ou igual ao mínimo)
    $stmtEstoque = $db->query("
        SELECT COUNT(*) as total 
        FROM estoque e
        INNER JOIN produto p ON p.id_produto = e.id_estoque
        WHERE e.quantidade <= e.minimo AND p.ativo = 1
    ");
    $resultadoEstoque = $stmtEstoque->fetch();
    $totalEstoqueBaixo = $resultadoEstoque['total'] ?? 0;

} catch (Exception $e) {
    $totalProdutos = 0;
    $totalEstoqueBaixo = 0;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Loja Geekies</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="bodydash">

<div class="container">
    <div class="topbar">
        <div class="brand">
            <div class="abelha">
                <h1>Loja Geekies</h1>
                <small style="color: black;">Painel do Sistema</small>
            </div>
        </div>
        <div style="color: black;" class="pill">
            Logado como: <strong style="color: purple;"><?= htmlspecialchars($_SESSION['usuario_nome'] ?? $nome) ?></strong>
            (<?php echo htmlspecialchars($perfil); ?>)
            • <a style="color: black;" href="/loja_geekies/index.php?controller=auth&action=logout">Sair</a>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-top:0;">Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome'] ?? $nome) ?>!</h2>
        <p style="color:var(--muted); margin-top:6px;">
            Escolha um módulo para continuar.
        </p>

        <!-- Navegação com 'Produtos / Categorias' mantido e 'Estoque' adicionado -->
        <div class="nav">
            <a style="color: black;" href="/loja_geekies/index.php?controller=produto&action=index">Produtos / Categorias</a>
            <a style="color: black;" href="/loja_geekies/index.php?controller=estoque&action=index">Estoque</a>
        </div>

        <!-- Cards resumo de indicadores mantendo a mesma classe CSS -->
        <div class="kpis">
            <div class="kpi">
                <div style="color: black;" class="label">Estoque baixo</div>
                <div style="color: black;" class="value"><?= (int)$totalEstoqueBaixo ?></div>
            </div>

            <div class="kpi">
                <div style="color: black;" class="label">Produtos</div>
                <div style="color: black;" class="value"><?= (int)$totalProdutos ?></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>