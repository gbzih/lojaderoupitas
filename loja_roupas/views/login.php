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
           
            <div style="margin-top:16px; display:flex; justify-content:flex-end; align-items: center;">
                <button class="btn" type="submit">Entrar</button>
               
                <a href="index.php?controller=usuario&action=create" class="btn" style="margin: 3px; text-decoration: none; text-align: center; display: inline-block;">
                    Cadastrar
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

