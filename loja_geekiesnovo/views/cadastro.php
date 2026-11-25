<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="public/assets/css/style.css">

    <title>Cadastro de Cliente - Geekies</title>
</head>

<body class="pagina-cadastro">

    <div class="cop">

        <div class="card">

            <h2>Crie sua conta na Geekies!</h2>

            <p>Preencha seus dados para realizar o cadastro.</p>

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

            <form action="index.php?controller=usuario&action=store"
                  method="POST">

                <div>
                    <label style="color:black;" for="nome">
                        Nome completo:
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Digite seu nome completo"
                        maxlength="100"
                        autocomplete="name"
                        required
                    >
                </div>

                <div>
                    <label style="color:black;" for="email">
                        E-mail:
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        maxlength="100"
                        autocomplete="email"
                        required
                    >
                </div>

                <div>
                    <label style="color:black;" for="cpf">
                        CPF:
                    </label>

                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        placeholder="000.000.000-00"
                        minlength="11"
                        maxlength="14"
                        inputmode="numeric"
                        required
                    >
                </div>

                <div>
                    <label style="color:black;" for="telefone">
                        Telefone (opcional):
                    </label>

                    <input
                        type="tel"
                        id="telefone"
                        name="telefone"
                        placeholder="(00) 00000-0000"
                        maxlength="20"
                        autocomplete="tel"
                    >
                </div>

                <div>
                    <label style="color:black;" for="senha">
                        Senha:
                    </label>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Mínimo de 8 caracteres"
                        minlength="8"
                        maxlength="72"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="actions-login">

                    <button
                        style="color:black;"
                        type="submit"
                        class="btn">

                        Cadastrar

                    </button>

                    <a
                        style="color:black;"
                        href="index.php?controller=auth&action=form"
                        class="btn">

                        Voltar

                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>