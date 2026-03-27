<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/payment.php';

requireLogin();

if (isPremium()) {
    header("Location: search.php"); // Already premium
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkoutUrl = createPreference($_SESSION['user_id'], $_SESSION['user_email'] ?? 'user@example.com');
    if ($checkoutUrl) {
        header("Location: " . $checkoutUrl);
        exit;
    } else {
        $error = "Erro ao iniciar pagamento. Verifique as configurações.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seja Premium - Afinador Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .upgrade-container {
            max-width: 800px;
            margin: 3rem auto;
            background: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .price-tag {
            font-size: 3rem;
            color: var(--primary-red);
            font-weight: bold;
            margin: 1rem 0;
        }
        .benefits-list {
            text-align: left;
            max-width: 400px;
            margin: 2rem auto;
            list-style-type: none;
            padding: 0;
        }
        .benefits-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
            font-size: 1.1rem;
        }
        .benefits-list li:before {
            content: "✓";
            color: green;
            margin-right: 10px;
            font-weight: bold;
        }
        .btn-upgrade {
            background-color: var(--primary-red);
            color: white;
            font-size: 1.5rem;
            padding: 1rem 3rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.2s, background 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-upgrade:hover {
            background-color: #a00000;
            transform: scale(1.05);
        }
        .guarantee {
            margin-top: 2rem;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<header>
    <div class="container navbar">
        <a href="index.php" class="logo-text">MAISDEUS<span>.COM</span></a>
        <nav class="nav-links">
            <a href="index.php">Afinador</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="upgrade-container">
        <h1>Desbloqueie Todo o Potencial</h1>
        <p>Tenha acesso ilimitado a cifras de toda a internet, diretamente no nosso afinador.</p>

        <div class="price-tag">
            R$ <?php echo number_format(PREMIUM_PRICE, 2, ',', '.'); ?> <span style="font-size: 1rem; color: #666;">/ único</span>
        </div>

        <ul class="benefits-list">
            <li>Busca ilimitada de cifras</li>
            <li>Acesso a cifras do Cifra Club e outros</li>
            <li>Visualização limpa e sem anúncios</li>
            <li>Suporte ao desenvolvimento</li>
        </ul>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" class="btn-upgrade">Quero ser Premium</button>
        </form>

        <div class="guarantee">
            <p>Pagamento seguro via Mercado Pago.</p>
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
