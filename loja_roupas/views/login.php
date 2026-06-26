<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Login - Nerdis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="card" style="max-width:520px; margin:70px auto;">
        <div class="brand">
            <div>
                 <h1 class="bem">Bem vindo!</h1>
            </div>
        </div>
       
        <form method="post" action="index.php?controller=auth&action=login">
            <label>E-mail</label>
            <input type="email" name="email" required autocomplete="username">
           
            <label>Senha</label>
            <input type="password" name="senha" required autocomplete="current-password">
           
            <div class="actions-login">
            <a href="index.php?controller=usuario&action=create" class="btn">
            Cadastrar
            </a>
            <button class="btn" type="submit">
             Entrar
    </button>
</div>
        </form>
    </div>
</div>
</body>
</html>

