<?php

$entradas = $entradas ?? [];

$produtos = $produtos ?? [];

$totalEntradas = $totalEntradas ?? 0;

$totalUnidades = $totalUnidades ?? 0;

$nomeUser = $_SESSION['usuario_nome'] ?? 'Administrador';

function escaparEntrada($valor): string
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

    <title>Controle de Entradas - Geekies</title>

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
                    Entradas
                </span>

            </div>

            <div class="user">

                <span style="color:black;">
                    Olá,
                </span>

                <strong>
                    <?= escaparEntrada($nomeUser) ?>
                </strong>

                <span style="margin:8px;">|</span>

                <a
                    href="index.php?controller=auth&action=dashboard"
                    style="color:black;">

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

        <!-- Cadastro de entrada -->
        <div class="card">

            <h2>Registrar Entrada</h2>

            <p style="color:black;">

                Registre o recebimento de novos produtos.
                A quantidade será adicionada automaticamente
                ao estoque.

            </p>

            <!-- Mensagem de erro -->
            <?php if (!empty($erro)): ?>

                <div
                    role="alert"
                    style="color:#8b0000; margin:15px 0;">

                    <?= escaparEntrada($erro) ?>

                </div>

            <?php endif; ?>

            <!-- Mensagem de sucesso -->
            <?php if (!empty($sucesso)): ?>

                <div
                    role="status"
                    style="color:darkgreen; margin:15px 0;">

                    <?= escaparEntrada($sucesso) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="index.php?controller=entrada&action=salvar">

                <input
                    type="hidden"
                    name="csrf"
                    value="<?= escaparEntrada($csrf ?? '') ?>">

                <!-- Produto -->
                <div class="form-group">

                    <label for="id_produto" style="color:black;">
                        Produto
                    </label>

                    <select
                        class="input"
                        id="id_produto"
                        name="id_produto"
                        required>

                        <option value="">
                            Selecione um produto...
                        </option>

                        <?php foreach ($produtos as $p): ?>

                            <option
                                value="<?= (int) $p['id_produto'] ?>">

                                <?= escaparEntrada($p['nome']) ?>

                                (Estoque:
                                <?= (int) $p['estoque'] ?>)

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Quantidade -->
                <div class="form-group">

                    <label for="quantidade" style="color:black;">
                        Quantidade recebida
                    </label>

                    <input
                        class="input"
                        type="number"
                        id="quantidade"
                        name="quantidade"
                        min="1"
                        max="2147483647"
                        step="1"
                        placeholder="Informe a quantidade"
                        required>

                </div>

                <small
                    class="muted"
                    style="display:block;
                           color:black;
                           margin-top:8px;">

                    A data da entrada será registrada
                    automaticamente pelo sistema.

                </small>

                <!-- Botões -->
                <div class="actions" style="margin-top:16px;">

                    <button
                        class="btn"
                        type="submit"
                        style="color:black;"
                        <?= empty($produtos) ? 'disabled' : '' ?>>

                        Registrar entrada

                    </button>

                    <a
                        class="btn"
                        style="color:black;"
                        href="index.php?controller=entrada&action=index">

                        Limpar

                    </a>

                </div>

            </form>

            <?php if (empty($produtos)): ?>

                <p style="color:#8b0000;">

                    Nenhum produto ativo disponível
                    para registrar entradas.

                </p>

            <?php endif; ?>

            <hr style="margin:25px 0;
                       border:0;
                       border-top:1px solid #999;">

            <!-- Indicadores -->
            <h2>Resumo das Entradas</h2>

            <div class="kpis"
                 style="grid-template-columns:1fr 1fr;">

                <div class="kpi">

                    <small style="color:black;">
                        Entradas registradas
                    </small>

                    <h2>
                        <?= (int) $totalEntradas ?>
                    </h2>

                </div>

                <div class="kpi">

                    <small style="color:black;">
                        Unidades recebidas
                    </small>

                    <h2>
                        <?= (int) $totalUnidades ?>
                    </h2>

                </div>

            </div>

            <!-- Navegação -->
            <div class="actions" style="margin-top:20px;">

                <a
                    class="btn"
                    style="color:black;"
                    href="index.php?controller=estoque&action=index">

                    Consultar estoque

                </a>

            </div>

        </div>

        <!-- Histórico de entradas -->
        <div class="card">

            <h2>Histórico de Entradas</h2>

            <table class="table">

                <thead>

                    <tr>

                        <th style="color:black;">ID</th>

                        <th style="color:black;">Produto</th>

                        <th style="color:black;">Categoria</th>

                        <th style="color:black;">Quantidade</th>

                        <th style="color:black;">Data e horário</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($entradas)): ?>

                        <tr>

                            <td
                                colspan="5"
                                style="text-align:center;
                                       color:black;">

                                Nenhuma entrada registrada.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach ($entradas as $e): ?>

                        <tr>

                            <td style="color:black;">

                                #<?= (int) $e['id_entrada'] ?>

                            </td>

                            <td style="color:black;">

                                <?= escaparEntrada(
                                    $e['produto_nome']
                                ) ?>

                            </td>

                            <td style="color:black;">

                                <?= escaparEntrada(
                                    $e['categoria_nome']
                                ) ?>

                            </td>

                            <td>

                                <span
                                    class="tag ok"
                                    style="color:darkgreen;">

                                    +<?= (int) $e['quantidade'] ?>
                                    un.

                                </span>

                            </td>

                            <td style="color:black;">

                                <?= date(
                                    'd/m/Y H:i',
                                    strtotime($e['data_entrada'])
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>