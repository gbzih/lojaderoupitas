<?php
// Helper simples para exibir a imagem do produto no estoque
function imagemProdutoUrlEstoque(int $produtoId): string
{
    $baseFs = __DIR__ . "/../public/uploads/produtos/";
    $baseUrl = "public/uploads/produtos/";
    foreach (['jpg', 'png', 'webp'] as $ext) {
        if (file_exists($baseFs . $produtoId . '.' . $ext)) {
            return $baseUrl . $produtoId . '.' . $ext;
        }
    }
    return "public/assets/img/produto_sem_foto.png";
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Controle de Estoque</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="pagina-produtos">

<div class="header">
    <div class="container header-inner">
        <div class="cabecaproduto">
            <strong>Loja Geekies</strong>
            <span class="badge">Estoque</span>
        </div>
        <div class="user">
            <span style="color: black;">Olá,</span> <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? $_SESSION['nome'] ?? 'Usuário') ?></strong>
            <span style="margin: 8px;">|</span>
            <a href="index.php?controller=auth&action=dashboard" style="margin-right: 8px; color: black;">Início</a>
            <span style="margin: 4px;">•</span>
            <a href="index.php?controller=auth&action=logout" style="color: black;">Sair</a>
        </div>
    </div>
</div>

<div class="container grid">
    <!-- Card Esquerdo: Formulário de Atualização do Item Selecionado -->
    <div class="card">
        <h2><?= $editar ? "Ajustar Estoque #" . (int)$editar['id_estoque'] : "Gerenciar Estoque" ?></h2>

        <?php if ($editar): ?>
            <form method="post" action="index.php?controller=estoque&action=salvar">
                <input type="hidden" name="id_estoque" value="<?= (int)$editar['id_estoque'] ?>">

                <div class="form-group">
                    <label style="color: black;">Produto</label>
                    <input class="input" type="text" value="<?= htmlspecialchars($editar['produto_nome']) ?>" disabled readonly>
                </div>

                <div class="form-group">
                    <label style="color: black;">Quantidade em Estoque</label>
                    <input class="input" type="number" name="quantidade" min="0" required
                           value="<?= (int)$editar['quantidade'] ?>">
                </div>

                <div class="form-group">
                    <label style="color: black;">Estoque Mínimo (Alerta)</label>
                    <input class="input" type="number" name="minimo" min="0" required
                           value="<?= (int)$editar['minimo'] ?>">
                    <small class="muted" style="display: block; margin-top: 4px; color: var(--muted);">
                        Sinaliza em alerta caso o saldo fique menor ou igual a este valor.
                    </small>
                </div>

                <div class="actions" style="margin-top: 14px;">
                    <button style="color: black;" class="btn btn-primary" type="submit">Salvar Alterações</button>
                    <a style="color: black;" class="btn" href="index.php?controller=estoque&action=index">Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <p style="color: black;">
                Selecione um produto na lista ao lado clicando em <strong>"Ajustar"</strong> para alterar as quantidades de estoque e o nível de alerta mínimo.
            </p>
        <?php endif; ?>

        
    </div>

    <!-- Card Direito: Tabela de Posições do Estoque -->
    <div class="card">
        <h2>Posição do Inventário</h2>
        <table class="table">
            <thead>
                <tr>
                    <th style="color: black;">Imagem</th>
                    <th style="color: black;">ID</th>
                    <th style="color: black;">Produto</th>
                    <th style="color: black;">Categoria</th>
                    <th style="color: black;">Estoque</th>
                    <th style="color: black;">Mínimo</th>
                    <th style="color: black;">Situação</th>
                    <th style="width: 120px; color: black;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $posicao = 1;
                foreach ($estoques as $e): 
                    $qtd = (int)($e['quantidade'] ?? 0);
                    $min = (int)($e['minimo'] ?? 0);
                    $emAlerta = $qtd <= $min;
                ?>
                <tr>
                    <td>
                        <img class="thumb" src="<?= imagemProdutoUrlEstoque((int)$e['id_produto']) ?>" alt="produto">
                    </td>
                    <td style="color: black;">#<?= $posicao ?></td>
                    <td style="color: black;"><?= htmlspecialchars($e['produto_nome']) ?></td>
                    <td style="color: black;"><?= htmlspecialchars($e['categoria_nome']) ?></td>
                    <td style="color: black;"><strong><?= $qtd ?></strong> un</td>
                    <td style="color: black;"><?= $min ?> un</td>
                    <td>
                        <?php if ($emAlerta): ?>
                            <span style="color: black;" class="tag off">Estoque Baixo</span>
                        <?php else: ?>
                            <span style="color: black;" class="tag ok">Normal</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a style="color: black;" class="btn" href="index.php?controller=estoque&action=index&id=<?= (int)$e['id_estoque'] ?>">
                            Ajustar
                        </a>
                    </td>
                </tr>
                <?php 
                $posicao++;
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (strtolower($_SESSION['perfil'] ?? '') === 'admin'): ?>
    <!-- Exibe o formulário de ajuste e o botão "Ajustar" -->
<?php endif; ?>

</body>
</html>