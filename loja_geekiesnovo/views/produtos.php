<?php

$editar = $editar ?? null;
$categorias = $categorias ?? [];
$produtos = $produtos ?? [];

// Localiza a imagem do produto pelo ID
function imagemProdutoUrl(int $produtoId): string
{
    $baseFs = __DIR__ . '/../public/uploads/produtos/';
    $baseUrl = 'public/uploads/produtos/';

    foreach (['jpg', 'png', 'webp'] as $ext) {
        if (is_file($baseFs . $produtoId . '.' . $ext)) {
            return $baseUrl . $produtoId . '.' . $ext;
        }
    }

    return 'public/assets/img/produto_sem_foto.png';
}

// Verifica se o produto possui imagem cadastrada
function produtoTemImagem(int $produtoId): bool
{
    $baseFs = __DIR__ . '/../public/uploads/produtos/';

    foreach (['jpg', 'png', 'webp'] as $ext) {
        if (is_file($baseFs . $produtoId . '.' . $ext)) {
            return true;
        }
    }

    return false;
}

// Protege os textos exibidos na página
function escaparProduto($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Gerenciamento de Produtos - Geekies</title>

    <link rel="stylesheet"
          href="public/assets/css/style.css">
</head>

<body class="pagina-produtos">

    <!-- Cabeçalho -->
    <div class="header">

        <div class="container header-inner">

            <div class="cabecaproduto">

                <strong>Loja Geekies</strong>

                <span class="badge">
                    Produtos
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparProduto(
                        $_SESSION['usuario_nome'] ?? 'Administrador'
                    ) ?>
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

    </div>

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Formulário de cadastro e edição -->
        <div class="card">

            <h2>
                <?= $editar
                    ? 'Editar Produto #' . (int) $editar['id_produto']
                    : 'Cadastrar Produto' ?>
            </h2>

            <?php if (!empty($erro)): ?>

                <div role="alert"
                     style="color:#8b0000; margin:15px 0;">

                    <?= escaparProduto($erro) ?>

                </div>

            <?php endif; ?>

            <?php if (!empty($sucesso)): ?>

                <div role="status"
                     style="color:darkgreen; margin:15px 0;">

                    <?= escaparProduto($sucesso) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="index.php?controller=produto&action=salvar"
                enctype="multipart/form-data">

                <input
                    type="hidden"
                    name="id_produto"
                    value="<?= $editar
                        ? (int) $editar['id_produto']
                        : 0 ?>">

                <!-- Categoria -->
                <div class="form-group">

                    <label for="id_categoria" style="color:black;">
                        Categoria
                    </label>

                    <select
                        class="input"
                        id="id_categoria"
                        name="id_categoria"
                        required>

                        <option value="">
                            Selecione uma categoria...
                        </option>

                        <?php foreach ($categorias as $c): ?>

                            <option
                                value="<?= (int) $c['id_categoria'] ?>"

                                <?= $editar &&
                                    (int) $editar['id_categoria'] ===
                                    (int) $c['id_categoria']
                                    ? 'selected'
                                    : '' ?>>

                                <?= escaparProduto($c['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Nome -->
                <div class="form-group">

                    <label for="nome" style="color:black;">
                        Nome do produto
                    </label>

                    <input
                        class="input"
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="150"
                        required
                        value="<?= escaparProduto(
                            $editar['nome'] ?? ''
                        ) ?>">

                </div>

                <!-- Descrição -->
                <div class="form-group">

                    <label for="descricao" style="color:black;">
                        Descrição (opcional)
                    </label>

                    <textarea
                        class="input"
                        id="descricao"
                        name="descricao"
                        rows="4"
                        style="width:100%;
                               padding:12px;
                               border-radius:14px;
                               border:0;
                               font-family:inherit;
                               resize:vertical;"><?= escaparProduto(
                            $editar['descricao'] ?? ''
                        ) ?></textarea>

                </div>

                <!-- Preço -->
                <div class="form-group">

                    <label for="preco" style="color:black;">
                        Preço (R$)
                    </label>

                    <input
                        class="input"
                        type="number"
                        id="preco"
                        name="preco"
                        step="0.01"
                        min="0"
                        max="99999999.99"
                        required
                        value="<?= escaparProduto(
                            $editar['preco'] ?? '0.00'
                        ) ?>">

                </div>

                <!-- Estoque inicial -->
                <?php if (!$editar): ?>

                    <div class="form-group">

                        <label for="estoque" style="color:black;">
                            Quantidade inicial em estoque
                        </label>

                        <input
                            class="input"
                            type="number"
                            id="estoque"
                            name="estoque"
                            min="0"
                            step="1"
                            value="0"
                            required>

                    </div>

                    <div class="form-group">

                        <label for="minimo" style="color:black;">
                            Estoque mínimo
                        </label>

                        <input
                            class="input"
                            type="number"
                            id="minimo"
                            name="minimo"
                            min="0"
                            step="1"
                            value="5"
                            required>

                    </div>

                <?php else: ?>

                    <div class="form-group">

                        <label style="color:black;">
                            Quantidade atual em estoque
                        </label>

                        <input
                            type="number"
                            value="<?= (int) ($editar['estoque'] ?? 0) ?>"
                            readonly>

                        <small class="muted" style="color:black;">
                            Para alterar a quantidade, utilize o
                            módulo de estoque.
                        </small>

                    </div>

                <?php endif; ?>

                <!-- Imagem -->
                <div class="form-group">

                    <label for="imagem" style="color:black;">
                        Imagem do produto (opcional)
                    </label>

                    <input
                        class="input"
                        type="file"
                        id="imagem"
                        name="imagem"
                        accept="image/png,image/jpeg,image/webp">

                    <small
                        class="muted"
                        style="display:block;
                               margin-bottom:10px;
                               color:black;">

                        Formatos: JPG, PNG e WEBP.
                        Tamanho máximo: 2 MB.

                    </small>

                    <?php if (
                        $editar &&
                        produtoTemImagem((int) $editar['id_produto'])
                    ): ?>

                        <div style="margin:12px 0;">

                            <img
                                src="<?= escaparProduto(
                                    imagemProdutoUrl(
                                        (int) $editar['id_produto']
                                    )
                                ) ?>"
                                alt="Imagem atual do produto"
                                style="width:100px;
                                       height:140px;
                                       object-fit:contain;
                                       border-radius:10px;">

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Botões do formulário -->
                <div class="actions" style="margin-top:16px;">

                    <button
                        style="color:black;"
                        class="btn"
                        type="submit">

                        Salvar

                    </button>

                    <a
                        style="color:black;"
                        class="btn"
                        href="index.php?controller=produto&action=index">

                        Limpar

                    </a>

                </div>

            </form>

            <!-- Remoção da imagem atual -->
            <?php if (
                $editar &&
                produtoTemImagem((int) $editar['id_produto'])
            ): ?>

                <form
                    method="POST"
                    action="index.php?controller=produto&action=removerImagem"
                    style="margin-top:12px;">

                    <input
                        type="hidden"
                        name="id_produto"
                        value="<?= (int) $editar['id_produto'] ?>">

                    <button
                        class="btn3"
                        type="submit"
                        style="color:#b00020; width:100%;">

                        Remover imagem atual

                    </button>

                </form>

            <?php endif; ?>

        </div>

        <!-- Listagem de produtos -->
        <div class="card">

            <h2>Lista de Produtos</h2>

            <table class="table">

                <thead>

                    <tr>
                        <th style="color:black;">Imagem</th>
                        <th style="color:black;">ID</th>
                        <th style="color:black;">Nome</th>
                        <th style="color:black;">Categoria</th>
                        <th style="color:black;">Preço</th>
                        <th style="color:black;">Estoque</th>
                        <th style="color:black;">Status</th>
                        <th style="color:black;">Ações</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($produtos)): ?>

                        <tr>

                            <td colspan="8"
                                style="text-align:center; color:black;">

                                Nenhum produto cadastrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($produtos as $p): ?>

                        <tr>

                            <!-- Imagem -->
                            <td>

                                <img
                                    class="thumb"
                                    src="<?= escaparProduto(
                                        imagemProdutoUrl(
                                            (int) $p['id_produto']
                                        )
                                    ) ?>"
                                    alt="<?= escaparProduto($p['nome']) ?>">

                            </td>

                            <!-- ID real -->
                            <td style="color:black;">

                                #<?= (int) $p['id_produto'] ?>

                            </td>

                            <!-- Nome -->
                            <td style="color:black;">

                                <?= escaparProduto($p['nome']) ?>

                            </td>

                            <!-- Categoria -->
                            <td style="color:black;">

                                <?= escaparProduto(
                                    $p['categoria_nome'] ?? 'Sem categoria'
                                ) ?>

                            </td>

                            <!-- Preço -->
                            <td style="color:black;">

                                R$ <?= number_format(
                                    (float) $p['preco'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                            </td>

                            <!-- Estoque -->
                            <td style="color:black;">

                                <?= (int) ($p['estoque'] ?? 0) ?> un.

                            </td>

                            <!-- Status -->
                            <td>

                                <?php if ((int) $p['ativo'] === 1): ?>

                                    <span
                                        class="tag ok"
                                        style="color:black;">

                                        Ativo

                                    </span>

                                <?php else: ?>

                                    <span
                                        class="tag off"
                                        style="color:black;">

                                        Inativo

                                    </span>

                                <?php endif; ?>

                            </td>

                            <!-- Ações -->
                            <td>

                                <div style="display:flex;
                                            flex-direction:column;
                                            gap:6px;">

                                    <!-- Editar -->
                                    <a
                                        class="btn"
                                        style="color:black;"
                                        href="index.php?controller=produto&action=index&id=<?= (int) $p['id_produto'] ?>">

                                        Editar

                                    </a>

                                    <!-- Ativar ou inativar -->
                                    <form
                                        method="POST"
                                        action="index.php?controller=produto&action=toggle">

                                        <input
                                            type="hidden"
                                            name="id_produto"
                                            value="<?= (int) $p['id_produto'] ?>">

                                        <input
                                            type="hidden"
                                            name="ativo"
                                            value="<?= (int) $p['ativo'] === 1
                                                ? 0
                                                : 1 ?>">

                                        <button
                                            type="submit"
                                            class="btn"
                                            style="color:black; width:100%;">

                                            <?= (int) $p['ativo'] === 1
                                                ? 'Inativar'
                                                : 'Ativar' ?>

                                        </button>

                                    </form>

                                    <!-- Exclusão permanente -->
                                    <form
                                        method="POST"
                                        action="index.php?controller=produto&action=deletar">

                                        <input
                                            type="hidden"
                                            name="id_produto"
                                            value="<?= (int) $p['id_produto'] ?>">

                                        <details>

                                            <summary
                                                style="color:#b00020;
                                                       cursor:pointer;
                                                       text-align:center;
                                                       padding:10px;">

                                                Excluir

                                            </summary>

                                            <p style="color:black;
                                                      font-size:12px;">

                                                Deseja excluir
                                                permanentemente este produto?

                                            </p>

                                            <button
                                                type="submit"
                                                class="btn"
                                                style="color:#b00020;
                                                       width:100%;">

                                                Confirmar exclusão

                                            </button>

                                        </details>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>