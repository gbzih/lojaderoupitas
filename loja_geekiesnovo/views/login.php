<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login Administrativo - Geekies</title>

    <link rel="stylesheet"
          href="public/assets/css/style.css">
</head>

<body class="pagina-login">

    <main class="container">

        <div class="card" style="max-width:520px; margin:70px auto;">

            <div class="brand">
                <div>

                    <h1 class="bem">
                        Geekies — Área Administrativa
                    </h1>

                    <small>
                        Entre com sua conta de administrador.
                    </small>

                </div>
            </div>

            <?php if (!empty($erro)): ?>

                <div class="mensagem-erro"
                     role="alert"
                     style="color:#8b0000; margin:15px 0;">

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <form method="post"
                  action="index.php?controller=auth&action=login">

                <label for="email" style="color:black;">
                    E-mail:
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    maxlength="100"
                    required
                    autocomplete="username"
                >

                <label for="senha" style="color:black;">
                    Senha:
                </label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Digite sua senha"
                    required
                    autocomplete="current-password"
                >

                <div class="actions-login">

                    <button
                        type="submit"
                        class="btn"
                        style="color:black;">

                        Entrar

                    </button>

                </div>

            </form>

        </div>

    </main>

</body>

</html>