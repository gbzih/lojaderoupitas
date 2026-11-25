<?php

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

$editar = $editar ?? null;

$categorias = $categorias ?? [];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>Gerenciamento de Categorias - Geekies</title>

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
                    Categorias
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= htmlspecialchars(
                        $nomeUser,
                        ENT_QUOTES,
                        'UTF-8'
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

    </header>

    <!-- Conteúdo principal -->
    <div class="container grid">

        <!-- Formulário de cadastro e edição -->
        <div class="card">

            <h2>
                <?= $editar
                    ? 'Editar Categoria #' .
                        (int) $editar['id_categoria']
                    : 'Cadastrar Categoria' ?>
            </h2>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <!-- Mensagem de sucesso -->
            <?php if (!empty($sucesso)): ?>

                <div
                    role="status"
                    style="color:darkgreen; margin:15px 0;">

                    <?= htmlspecialchars(
                        $sucesso,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="index.php?controller=categoria&action=salvar">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $editar
                        ? (int) $editar['id_categoria']
                        : 0 ?>">

                <!-- Nome da categoria -->
                <div class="form-group">

                    <label for="nome" style="color:black;">
                        Nome da categoria
                    </label>

                    <input
                        class="input"
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Digite o nome da categoria"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars(
                            $editar['nome'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">

                </div>

                <!-- Botões -->
                <div class="actions" style="margin-top:14px;">

                    <button
                        class="btn"
                        type="submit"
                        style="color:black;">

                        Salvar

                    </button>

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=categoria&action=index">

                        Limpar

                    </a>

                </div>

            </form>

        </div>

        <!-- Listagem de categorias -->
        <div class="card">

            <h2>Lista de Categorias</h2>

            <div style="flex:1; overflow-y:auto;">

                <table class="table" style="min-width:0;">

                    <thead>

                        <tr>

                            <th style="color:black;">
                                ID
                            </th>

                            <th style="color:black;">
                                Nome
                            </th>

                            <th style="width:120px; color:black;">
                                Ações
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (empty($categorias)): ?>

                            <tr>

                                <td
                                    colspan="3"
                                    style="text-align:center; color:black;">

                                    Nenhuma categoria cadastrada.

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach ($categorias as $c): ?>

                            <tr>

                                <td style="color:black;">

                                    #<?= (int) $c['id_categoria'] ?>

                                </td>

                                <td style="color:black;">

                                    <?= htmlspecialchars(
                                        $c['nome'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>

                                <td>

                                    <a
                                        class="btn"
                                        style="color:black;"
                                        href="index.php?controller=categoria&action=index&id=<?= (int) $c['id_categoria'] ?>">

                                        Editar

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <!-- Navegação -->
            <div class="actions" style="margin-top:14px;">

                <a
                    class="btn"
                    style="color:black;"
                    href="index.php?controller=auth&action=dashboard">

                    Voltar ao Dashboard

                </a>

                <a
                    class="btn"
                    style="color:black;"
                    href="index.php?controller=produto&action=index">

                    Gerenciar Produtos

                </a>

            </div>

        </div>

    </div>

</body>

</html>