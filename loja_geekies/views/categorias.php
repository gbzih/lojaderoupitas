<?php

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

$editar = $editar ?? null;

$categorias = $categorias ?? [];

$nomeDigitado = $nomeDigitado ?? '';

$csrf = $csrf ?? '';

// Protege os textos exibidos na página
function escaparCategoria($valor): string
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

    <title>Gerenciamento de Categorias - Geekies</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="public/assets/css/style.css?v=20261126-4">

</head>

<body class="pagina-produtos pagina-categorias">

    <!-- Cabeçalho -->
    <header class="header">

        <div class="container header-inner">

            <div class="cabecaproduto">

                <strong>Loja Geekies</strong>

                <span class="badge">
                    Categorias
                </span>

                <a
                    href="index.php?controller=auth&action=dashboard"
                    style="color:black;">

                    Voltar

                </a>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparCategoria($nomeUser) ?>
                </strong>

                <span style="margin:8px;">|</span>

                <a
                    href="index.php?controller=auth&action=logout"
                    style="color:black;">

                    Sair

                </a>

            </div>

        </div>

    </header>

    <!-- Conteúdo principal -->
    <main class="categorias-container">

        <!-- Formulário -->
        <section class="categorias-card">

            <h2>

                <?= $editar
                    ? 'Editar Categoria #' .
                        (int) $editar['id_categoria']
                    : 'Cadastrar Categoria' ?>

            </h2>

            <p class="categorias-descricao">

                <?= $editar
                    ? 'Altere o nome da categoria selecionada.'
                    : 'Adicione uma nova categoria à loja.' ?>

            </p>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    class="categorias-mensagem categorias-erro"
                    role="alert">

                    <?= escaparCategoria($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Mensagem de sucesso -->
            <?php if (!empty($sucesso)): ?>

                <div
                    class="categorias-mensagem categorias-sucesso"
                    role="status">

                    <?= escaparCategoria($sucesso) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="index.php?controller=categoria&action=salvar">

                <!-- Token de segurança -->
                <input
                    type="hidden"
                    name="csrf"
                    value="<?= escaparCategoria($csrf) ?>">

                <!-- ID da categoria -->
                <input
                    type="hidden"
                    name="id"
                    value="<?= $editar
                        ? (int) $editar['id_categoria']
                        : 0 ?>">

                <!-- Nome -->
                <div class="categorias-campo">

                    <label for="nome">
                        Nome da categoria
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Ex.: Mangás"
                        maxlength="100"
                        required
                        value="<?= escaparCategoria(
                            $nomeDigitado !== ''
                                ? $nomeDigitado
                                : ($editar['nome'] ?? '')
                        ) ?>">

                </div>

                <!-- Botões do formulário -->
                <div class="categorias-acoes">

                    <button
                        class="categorias-botao categorias-botao-principal"
                        type="submit">

                        <?= $editar
                            ? 'Salvar alterações'
                            : 'Cadastrar categoria' ?>

                    </button>

                    <a
                        class="categorias-botao"
                        href="index.php?controller=categoria&action=index">

                        <?= $editar
                            ? 'Cancelar'
                            : 'Limpar' ?>

                    </a>

                </div>

            </form>

        </section>

        <!-- Lista de categorias -->
        <section class="categorias-card">

            <div class="categorias-lista-topo">

                <h2>Lista de Categorias</h2>

                <a
                    class="categorias-botao categorias-botao-principal"
                    href="index.php?controller=categoria&action=index#nome">

                    + Nova categoria

                </a>

            </div>

            <!-- Tabela -->
            <div class="categorias-tabela-wrapper">

                <table class="categorias-tabela">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Nome</th>

                            <th>Ações</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (empty($categorias)): ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="categorias-vazio">

                                    Nenhuma categoria cadastrada.

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach ($categorias as $c): ?>

                            <tr>

                                <!-- ID -->
                                <td>

                                    #<?= (int) $c['id_categoria'] ?>

                                </td>

                                <!-- Nome -->
                                <td>

                                    <?= escaparCategoria(
                                        $c['nome']
                                    ) ?>

                                </td>

                                <!-- Ações -->
                                <td>

                                    <div style="display:flex;
                                                flex-wrap:wrap;
                                                align-items:center;
                                                gap:8px;">

                                        <!-- Editar -->
                                        <a
                                            class="categorias-botao"
                                            href="index.php?controller=categoria&action=index&id=<?= (int) $c['id_categoria'] ?>#nome">

                                            Editar

                                        </a>

                                        <!-- Excluir -->
                                        <a
                                            class="categoria-btn-excluir"
                                            href="#excluir-categoria-<?= (int) $c['id_categoria'] ?>">

                                            Excluir

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <!-- Contagem -->
            <p class="categorias-contagem">

                <?= count($categorias) ?>

                categoria(s) cadastrada(s).

            </p>

            <!-- Navegação -->
            <div class="categorias-rodape">

                <a
                    class="categorias-botao"
                    href="index.php?controller=produto&action=index">

                    Gerenciar Produtos

                </a>

            </div>

        </section>

    </main>

    <!-- Janelas de confirmação de exclusão -->
    <?php foreach ($categorias as $c): ?>

        <div
            class="categoria-modal"
            id="excluir-categoria-<?= (int) $c['id_categoria'] ?>">

            <div
                class="categoria-modal-conteudo"
                role="dialog"
                aria-labelledby="titulo-exclusao-<?= (int) $c['id_categoria'] ?>">

                <h3 id="titulo-exclusao-<?= (int) $c['id_categoria'] ?>">

                    Confirmar exclusão

                </h3>

                <p>

                    Tem certeza de que deseja excluir a categoria

                    <strong>

                        <?= escaparCategoria($c['nome']) ?>

                    </strong>?

                </p>

                <p class="categoria-modal-aviso">

                    Esta ação não poderá ser desfeita.

                </p>

                <form
                    method="POST"
                    action="index.php?controller=categoria&action=deletar">

                    <!-- ID da categoria -->
                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $c['id_categoria'] ?>">

                    <!-- Token de segurança -->
                    <input
                        type="hidden"
                        name="csrf"
                        value="<?= escaparCategoria($csrf) ?>">

                    <!-- Botões de confirmação -->
                    <div class="categoria-modal-acoes">

                        <a
                            class="categoria-btn-cancelar"
                            href="#">

                            Cancelar

                        </a>

                        <button
                            type="submit"
                            class="categoria-btn-excluir">

                            Sim, excluir

                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php endforeach; ?>

</body>

</html>