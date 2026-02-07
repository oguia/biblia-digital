<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Force refresh user session to update premium status
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT is_premium FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['is_premium'] = $user['is_premium'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Concluído - Afinador Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="refresh" content="5;url=search.php">
</head>
<body>

<header>
    <div class="container navbar">
        <a href="index.php" class="logo-text">MAISDEUS<span>.COM</span></a>
    </div>
</header>

<div class="container">
    <div style="text-align: center; margin-top: 5rem;">
        <h1 style="color: green;">Pagamento Recebido com Sucesso!</h1>
        <p>Sua conta agora é Premium. Aproveite todas as funcionalidades.</p>
        <p>Você será redirecionado em 5 segundos...</p>
        <br>
        <a href="search.php" class="btn-cta">Ir para Busca de Cifras</a>
    </div>
</div>

</body>
</html>
