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
        <label for="nome">Nome: ophelia</label>
        <input type="text" id="nome" name="nome" required>
      </div>

      <br>

      <div>
        <label for="email">E-mail: gb@gmail.com</label>
        <input type="email" id="email" name="email" required>
      </div>

      <br>

      <div>
        <label for="senha">Senha: 1234</label>
        <input type="password" id="senha" name="senha" required>
      </div>

      <br>

      <button type="submit">Cadastrar</button>

    </form>

  </div>

</div>

</body>
</html>
