<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (loginUser($email, $password)) {
        header("Location: index.php"); // Redirect to home/tuner
        exit;
    } else {
        $error = "Email ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Afinador Mais Deus</title>
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
            <a href="register.php" class="btn-cta">Criar Conta</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="auth-container">
        <h2 class="text-center mb-4">Entrar</h2>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <div class="auth-links">
            <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mais Deus. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>
