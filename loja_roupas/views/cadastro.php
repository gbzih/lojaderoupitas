<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
      <link rel="stylesheet" href="public/assets/css/style.css">
    <title>Cadastrar Vendedor</title>


</head>
<body>

 <div class="cop">

  <div class="card">

    <h2>Cadastro de Vendedor</h2>

    <form action="index.php?controller=usuario&action=store" method="POST">

      <div>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
      </div>

      <br>

      <div>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
      </div>

      <br>

      <div>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
      </div>

      <br>
      <div class="actions-login">
        <button type="submit" class="btn">Cadastrar</button>
        <a href="/loja_roupas/index.php?controller=auth&action=form" class="btn btn-secondary">Voltar</a>
      </div>

    </form>

  </div>

</div>

</body>
</html>
