<?php

$editar = $editar ?? null;

$estoques = $estoques ?? [];

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

// Localiza a imagem do produto
function imagemProdutoUrlEstoque(int $produtoId): string
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

// Protege os textos exibidos na página
function escaparEstoque($valor): string
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

    <title>Controle de Estoque - Geekies</title>

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
                    Estoque
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparEstoque($nomeUser) ?>
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

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Formulários de gerenciamento -->
        <div class="card">

            <h2>
                <?= $editar
                    ? 'Ajustar Estoque #' .
                        (int) $editar['id_produto']
                    : 'Gerenciar Estoque' ?>
            </h2>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= escaparEstoque($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Mensagem de sucesso -->
            <?php if (!empty($sucesso)): ?>

                <div
                    role="status"
                    style="color:darkgreen; margin:15px 0;">

                    <?= escaparEstoque($sucesso) ?>

                </div>

            <?php endif; ?>

            <?php if ($editar): ?>

                <!-- Informações do produto selecionado -->
                <div style="text-align:center; margin:15px 0;">

                    <img
                        src="<?= escaparEstoque(
                            imagemProdutoUrlEstoque(
                                (int) $editar['id_produto']
                            )
                        ) ?>"
                        alt="<?= escaparEstoque(
                            $editar['produto_nome']
                        ) ?>"
                        style="width:100px;
                               height:140px;
                               object-fit:contain;
                               border-radius:10px;">

                </div>

                <div class="form-group">

                    <label for="produto_nome" style="color:black;">
                        Produto selecionado
                    </label>

                    <input
                        class="input"
                        type="text"
                        id="produto_nome"
                        value="<?= escaparEstoque(
                            $editar['produto_nome']
                        ) ?>"
                        readonly>

                </div>

                <div class="form-group">

                    <label for="categoria_nome" style="color:black;">
                        Categoria
                    </label>

                    <input
                        class="input"
                        type="text"
                        id="categoria_nome"
                        value="<?= escaparEstoque(
                            $editar['categoria_nome'] ?? ''
                        ) ?>"
                        readonly>

                </div>

                <!-- Formulário de ajuste direto -->
                <form
                    method="POST"
                    action="index.php?controller=estoque&action=salvar">

                    <input
                        type="hidden"
                        name="id_produto"
                        value="<?= (int) $editar['id_produto'] ?>">

                    <!-- Quantidade -->
                    <div class="form-group">

                        <label for="quantidade" style="color:black;">
                            Quantidade em estoque
                        </label>

                        <input
                            class="input"
                            type="number"
                            id="quantidade"
                            name="quantidade"
                            min="0"
                            max="2147483647"
                            step="1"
                            required
                            value="<?= (int) $editar['quantidade'] ?>">

                    </div>

                    <!-- Estoque mínimo -->
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
                            max="2147483647"
                            step="1"
                            required
                            value="<?= (int) $editar['minimo'] ?>">

                        <small
                            class="muted"
                            style="display:block;
                                   margin-top:4px;
                                   color:black;">

                            O sistema sinaliza um alerta quando
                            a quantidade disponível for menor
                            ou igual ao estoque mínimo.

                        </small>

                    </div>

                    <div class="actions" style="margin-top:14px;">

                        <button
                            class="btn"
                            type="submit"
                            style="color:black;">

                            Salvar alterações

                        </button>

                        <a
                            class="btn"
                            style="color:black;"
                            href="index.php?controller=estoque&action=index">

                            Cancelar

                        </a>

                    </div>

                </form>

                <hr style="margin:25px 0;
                           border:0;
                           border-top:1px solid #999;">

                <!-- Formulário de movimentação -->
                <h2>Movimentar Estoque</h2>

                <p style="color:black;">

                    Registre uma entrada ou saída de unidades
                    do produto selecionado.

                </p>

                <form
                    method="POST"
                    action="index.php?controller=estoque&action=movimentar">

                    <input
                        type="hidden"
                        name="id_produto"
                        value="<?= (int) $editar['id_produto'] ?>">

                    <!-- Tipo de movimentação -->
                    <div class="form-group">

                        <label for="tipo" style="color:black;">
                            Tipo de movimentação
                        </label>

                        <select
                            class="input"
                            id="tipo"
                            name="tipo"
                            required>

                            <option value="">
                                Selecione...
                            </option>

                            <option value="entrada">
                                Entrada de produtos
                            </option>

                            <option value="saida">
                                Saída de produtos
                            </option>

                        </select>

                    </div>

                    <!-- Quantidade movimentada -->
                    <div class="form-group">

                        <label
                            for="quantidade_movimentada"
                            style="color:black;">

                            Quantidade da movimentação

                        </label>

                        <input
                            class="input"
                            type="number"
                            id="quantidade_movimentada"
                            name="quantidade"
                            min="1"
                            max="2147483647"
                            step="1"
                            placeholder="Informe a quantidade"
                            required>

                        <small
                            class="muted"
                            style="display:block;
                                   margin-top:5px;
                                   color:black;">

                            Saldo atual:
                            <?= (int) $editar['quantidade'] ?>
                            unidade(s).

                        </small>

                    </div>

                    <div class="actions" style="margin-top:14px;">

                        <button
                            class="btn"
                            type="submit"
                            style="color:black; width:100%;">

                            Registrar movimentação

                        </button>

                    </div>

                </form>

            <?php else: ?>

                <!-- Orientação quando nenhum produto foi selecionado -->
                <p style="color:black;">

                    Selecione um produto na lista ao lado,
                    clicando em <strong>Ajustar</strong>,
                    para gerenciar seu estoque.

                </p>

                <p style="color:black;">
                    Neste módulo você poderá:
                </p>

                <ul style="color:black; line-height:1.8;">

                    <li>Consultar a quantidade disponível.</li>

                    <li>Alterar o estoque mínimo.</li>

                    <li>Corrigir a quantidade em estoque.</li>

                    <li>Registrar entradas de produtos.</li>

                    <li>Realizar saídas manuais.</li>

                    <li>Identificar produtos com estoque baixo.</li>

                </ul>

            <?php endif; ?>

            <!-- Navegação -->
            <div class="actions" style="margin-top:20px;">

                <a
                    class="btn"
                    style="color:black;"
                    href="index.php?controller=produto&action=index">

                    Gerenciar produtos

                </a>

            </div>

        </div>

        <!-- Listagem de estoque -->
        <div class="card">

            <h2>Posição do Inventário</h2>

            <table class="table">

                <thead>

                    <tr>

                        <th style="color:black;">Imagem</th>

                        <th style="color:black;">ID</th>

                        <th style="color:black;">Produto</th>

                        <th style="color:black;">Categoria</th>

                        <th style="color:black;">Estoque</th>

                        <th style="color:black;">Mínimo</th>

                        <th style="color:black;">Situação</th>

                        <th style="color:black;">Status</th>

                        <th style="width:120px; color:black;">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($estoques)): ?>

                        <tr>

                            <td
                                colspan="9"
                                style="text-align:center;
                                       color:black;">

                                Nenhum produto cadastrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($estoques as $e): ?>

                        <?php

                        $qtd = (int) $e['quantidade'];

                        $min = (int) $e['minimo'];

                        $ativo = (int) $e['ativo'] === 1;

                        ?>

                        <tr>

                            <!-- Imagem -->
                            <td>

                                <img
                                    class="thumb"
                                    src="<?= escaparEstoque(
                                        imagemProdutoUrlEstoque(
                                            (int) $e['id_produto']
                                        )
                                    ) ?>"
                                    alt="<?= escaparEstoque(
                                        $e['produto_nome']
                                    ) ?>">

                            </td>

                            <!-- ID do produto -->
                            <td style="color:black;">

                                #<?= (int) $e['id_produto'] ?>

                            </td>

                            <!-- Nome -->
                            <td style="color:black;">

                                <?= escaparEstoque(
                                    $e['produto_nome']
                                ) ?>

                            </td>

                            <!-- Categoria -->
                            <td style="color:black;">

                                <?= escaparEstoque(
                                    $e['categoria_nome']
                                ) ?>

                            </td>

                            <!-- Quantidade disponível -->
                            <td style="color:black;">

                                <strong><?= $qtd ?></strong> un.

                            </td>

                            <!-- Estoque mínimo -->
                            <td style="color:black;">

                                <?= $min ?> un.

                            </td>

                            <!-- Situação do estoque -->
                            <td>

                                <?php if ($qtd === 0): ?>

                                    <span
                                        class="tag off"
                                        style="color:#8b0000;">

                                        Sem estoque

                                    </span>

                                <?php elseif ($qtd <= $min): ?>

                                    <span
                                        class="tag off"
                                        style="color:#8b4500;">

                                        Estoque baixo

                                    </span>

                                <?php else: ?>

                                    <span
                                        class="tag ok"
                                        style="color:darkgreen;">

                                        Normal

                                    </span>

                                <?php endif; ?>

                            </td>

                            <!-- Status do produto -->
                            <td>

                                <?php if ($ativo): ?>

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

                                <a
                                    class="btn"
                                    style="color:black;"
                                    href="index.php?controller=estoque&action=index&id=<?= (int) $e['id_produto'] ?>">

                                    Ajustar

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