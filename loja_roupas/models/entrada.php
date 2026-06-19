<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Entradas - Loja de Roupas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .btn { display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .voltar { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h1>📥 Gerenciamento de Entradas de Estoque</h1>
    <p>Olá, <?php echo $_SESSION['usuario_nome'] ?? 'Usuário'; ?>! Aqui você gerencia a entrada de novas roupas no estoque.</p>

    <a href="#" class="btn">+ Registrar Nova Entrada</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Data da Entrada</th>
                <th>Usuário</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" style="text-align: center; color: #777;">Nenhuma entrada registrada no momento ou o banco ainda não foi conectado aqui.</td>
            </tr>
        </tbody>
    </table>

    <a href="index.php?controller=auth&action=dashboard" class="voltar">← Voltar para o Dashboard</a>
</div>

</body>
</html>