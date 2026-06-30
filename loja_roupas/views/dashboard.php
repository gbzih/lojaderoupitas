<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$perfil = $_SESSION['perfil'] ?? 'vendedor';

try {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT COUNT(*) as total FROM produto");
    $resultado = $stmt->fetch();
    $totalProdutos = $resultado['total'] ?? 0;
} catch (Exception $e) {
    $totalProdutos = 0; //ele via exibir 0 por seguranca
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Loja Nerdis</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>

<div class="container">
<div class="topbar">
<div class="brand">
<div class="abelha">
<h1>Loja Nerdis</h1>
<small>Painel do Sistema</small>
</div>
</div>
<div class="pill">
Logado como <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?></strong>
(<?php echo htmlspecialchars($perfil); ?>)
• <a href="/loja_roupas/index.php?controller=auth&action=logout">Sair</a>
</div>
</div>
<div class="card">
<h2 style="margin-top:0;">Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>!</h2>
<p style="color:var(--muted); margin-top:6px;">
Escolha um módulo para continuar.
</p>
<div class="nav">

<a href="/loja_roupas/index.php?controller=produto&action=index">Produtos / Categorias</a>
<a href="/loja_roupas/index.php?controller=entrada&action=index">Entradas</a>
<a href="/loja_roupas/index.php?controller=venda&action=index">Vendas</a>
<a href="/loja_roupas/index.php?controller=relatorio&action=index">Relatórios</a>
</div>

<div class="kpis">
<div class="kpi">
<div class="label">Vendas (mês)</div>
<div class="value">0</div>
</div>
<div class="kpi">
<div class="label">Entradas (mês)</div>
<div class="value">0</div>
</div>
<div class="kpi">
<div class="label">Estoque baixo</div>
<div class="value">0</div>
</div>

<div class="kpi">
<div class="label">Produtos</div>
<div class="value"><?= (int)$totalProdutos ?></div>
</div>
</div>
</div>
</div>
</body>
</html>