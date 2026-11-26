<?php

$usuarios = $usuarios ?? [];
$editar = $editar ?? null;
$busca = $busca ?? '';
$csrf = $csrf ?? '';

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

function escaparUsuario($valor): string
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

    <title>Usuários - Geekies</title>

    <link rel="stylesheet"
          href="public/assets/css/style.css">

</head>

<body class="pagina-produtos pagina-usuarios">

    <header class="header">

        <div class="container header-inner">

            <div class="cabecaproduto">

                <strong>Loja Geekies</strong>

                <span class="badge">
                    Usuários
                </span>

                <a href="index.php?controller=auth&action=dashboard">
                    Voltar
                </a>

            </div>

            <div class="user">

                Olá,

                <strong>
                    <?= escaparUsuario($nomeUser) ?>
                </strong>

                <a href="index.php?controller=auth&action=logout">
                    Sair
                </a>

            </div>

        </div>

    </header>

    <main class="container painel-listagem">

        <section class="card">

            <h2>Lista de Usuários</h2>

            <?php if (!empty($erro)): ?>

                <div class="aviso-erro" role="alert">
                    <?= escaparUsuario($erro) ?>
                </div>

            <?php endif; ?>

            <?php if (!empty($sucesso)): ?>

                <div class="aviso-sucesso" role="status">
                    <?= escaparUsuario($sucesso) ?>
                </div>

            <?php endif; ?>

            <!-- Barra de pesquisa -->
            <form method="GET"
                  action="index.php"
                  class="pesquisa-listagem">

                <input type="hidden"
                       name="controller"
                       value="cliente">

                <input type="hidden"
                       name="action"
                       value="index">

                <label for="busca">
                    Barra de pesquisa:
                </label>

                <input type="search"
                       id="busca"
                       name="busca"
                       maxlength="150"
                       placeholder="Nome ou e-mail..."
                       value="<?= escaparUsuario($busca) ?>">

                <button type="submit"
                        class="btn-listagem">
                    Buscar
                </button>

                <?php if ($busca !== ''): ?>

                    <a class="btn-listagem"
                       href="index.php?controller=cliente&action=index">
                        Limpar
                    </a>

                <?php endif; ?>

            </form>

            <!-- Edição -->
            <?php if ($editar): ?>

                <section class="edicao-listagem">

                    <h3>Editar usuário</h3>

                    <form method="POST"
                          action="index.php?controller=cliente&action=salvar">

                        <input type="hidden"
                               name="csrf"
                               value="<?= escaparUsuario($csrf) ?>">

                        <input type="hidden"
                               name="id_usuario"
                               value="<?= (int) $editar['id_usuario'] ?>">

                        <div class="campos-edicao">

                            <div>

                                <label for="nome">
                                    Nome
                                </label>

                                <input type="text"
                                       id="nome"
                                       name="nome"
                                       maxlength="100"
                                       required
                                       value="<?= escaparUsuario($editar['nome']) ?>">

                            </div>

                            <div>

                                <label for="email">
                                    E-mail
                                </label>

                                <input type="email"
                                       id="email"
                                       name="email"
                                       maxlength="100"
                                       required
                                       value="<?= escaparUsuario($editar['email']) ?>">

                            </div>

                        </div>

                        <div class="acoes-edicao">

                            <button type="submit"
                                    class="btn-listagem">
                                Salvar
                            </button>

                            <a class="btn-listagem"
                               href="index.php?controller=cliente&action=index">
                                Cancelar
                            </a>

                        </div>

                    </form>

                </section>

            <?php endif; ?>

            <!-- Listagem -->
            <div class="tabela-listagem-scroll">

                <table class="tabela-listagem">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (empty($usuarios)): ?>

                            <tr>

                                <td colspan="5"
                                    class="listagem-vazia">

                                    Nenhum usuário encontrado.

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach ($usuarios as $u): ?>

                            <?php

                            $administrador =
                                $u['tipo'] === 'Administrador';

                            $propriaConta =
                                (int) $u['id_usuario'] ===
                                (int) ($_SESSION['usuario_id'] ?? 0);

                            ?>

                            <tr>

                                <td>
                                    #<?= (int) $u['id_usuario'] ?>
                                </td>

                                <td>
                                    <?= escaparUsuario($u['nome']) ?>
                                </td>

                                <td>
                                    <?= escaparUsuario($u['email']) ?>
                                </td>

                                <td>
                                    <?= escaparUsuario($u['tipo']) ?>
                                </td>

                                <td>

                                    <div class="acoes-listagem">

                                        <?php if (!$administrador || $propriaConta): ?>

                                            <a class="btn-listagem"
                                               href="index.php?controller=cliente&action=index&id=<?= (int) $u['id_usuario'] ?>">

                                                Editar

                                            </a>

                                        <?php endif; ?>

                                        <?php if ($administrador || $propriaConta): ?>

                                            <span class="conta-protegida">
                                                Protegido
                                            </span>

                                        <?php else: ?>

                                            <details class="confirmar-exclusao">

                                                <summary class="btn-listagem">
                                                    Excluir
                                                </summary>

                                                <form method="POST"
                                                      action="index.php?controller=cliente&action=excluir">

                                                    <input type="hidden"
                                                           name="csrf"
                                                           value="<?= escaparUsuario($csrf) ?>">

                                                    <input type="hidden"
                                                           name="id_usuario"
                                                           value="<?= (int) $u['id_usuario'] ?>">

                                                    <p>
                                                        Confirmar exclusão?
                                                    </p>

                                                    <button type="submit"
                                                            class="btn-listagem btn-excluir">

                                                        Confirmar

                                                    </button>

                                                </form>

                                            </details>

                                        <?php endif; ?>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <p class="total-listagem">

                <?= count($usuarios) ?>
                usuário(s) encontrado(s).

            </p>

        </section>

    </main>

</body>

</html>