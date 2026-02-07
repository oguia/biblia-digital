<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "As senhas não coincidem.";
    } else {
        $result = registerUser($name, $email, $password);
        if ($result['success']) {
            $success = "Cadastro realizado com sucesso! Você pode entrar agora.";
            // Optionally redirect after a delay or show a link
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Afinador Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .btn-submit {
            width: 100%;
            background-color: var(--primary-red);
            color: white;
            border: none;
            padding: 1rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background-color: #a00000;
        }
        .error-msg {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
        .success-msg {
            color: green;
            margin-bottom: 1rem;
            text-align: center;
        }
        .auth-links {
            text-align: center;
            margin-top: 1rem;
        }
        .auth-links a {
            color: var(--primary-red);
            text-decoration: none;
        }
    </style>
</head>
<body>

<header>
    <div class="container navbar">
        <a href="index.php" class="logo-text">MAISDEUS<span>.COM</span></a>
        <nav class="nav-links">
            <a href="index.php">Afinador</a>
            <a href="login.php" class="btn-cta">Entrar</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="auth-container">
        <h2 class="text-center mb-4">Criar Conta</h2>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success-msg">
                <?php echo $success; ?>
                <p><a href="login.php">Clique aqui para entrar.</a></p>
            </div>
        <?php else: ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Senha</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-submit">Cadastrar</button>
        </form>

        <div class="auth-links">
            <p>Já tem uma conta? <a href="login.php">Entre aqui</a></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mais Deus. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>
