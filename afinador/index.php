<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$is_logged_in = isLoggedIn();
$is_premium = isPremium();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afinador de Violão Online - Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container navbar">
        <a href="index.php" class="logo-text">MAISDEUS<span>.COM</span></a>
        <nav class="nav-links">
            <a href="index.php">Afinador</a>
            <?php if ($is_logged_in): ?>
                <?php if ($is_premium): ?>
                    <a href="search.php">Buscar Cifras</a>
                <?php else: ?>
                    <a href="upgrade.php" class="btn-cta">Seja Premium</a>
                <?php endif; ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a href="register.php" class="btn-cta">Criar Conta</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<div class="container">
    <div class="tuner-section">
        <h1 class="text-center">Afinador de Violão Online</h1>
        <p class="text-center">Afine seu violão usando o microfone ou ouça as cordas.</p>

        <div class="tuner-container">
            <div id="tuner-display">
                <div class="note-display" id="note">--</div>
                <div class="frequency-display"><span id="frequency">0</span> Hz</div>

                <div class="meter-container">
                    <div class="meter-center"></div>
                    <div class="meter-needle" id="needle"></div>
                    <div class="meter-scale">
                        <span>b</span>
                        <span>#</span>
                    </div>
                </div>
                <div class="status-msg" id="status">Clique em "Iniciar Microfone" para começar</div>
            </div>

            <div class="controls">
                <button id="start-btn" class="btn-primary">Iniciar Microfone</button>
            </div>

            <div class="reference-notes">
                <h3>Notas de Referência (Padrão)</h3>
                <div class="string-buttons">
                    <button data-note="E2" data-freq="82.41">6ª (E)</button>
                    <button data-note="A2" data-freq="110.00">5ª (A)</button>
                    <button data-note="D3" data-freq="146.83">4ª (D)</button>
                    <button data-note="G3" data-freq="196.00">3ª (G)</button>
                    <button data-note="B3" data-freq="246.94">2ª (B)</button>
                    <button data-note="E4" data-freq="329.63">1ª (E)</button>
                </div>
                <button id="stop-sound-btn" class="btn-secondary" style="display:none;">Parar Som</button>
            </div>
        </div>
    </div>

    <?php if (!$is_premium): ?>
    <div class="premium-promo text-center">
        <h2>Quer tocar qualquer música?</h2>
        <p>Com o plano Premium, você busca cifras de toda a internet e visualiza aqui, sem anúncios e distrações.</p>
        <a href="<?php echo $is_logged_in ? 'upgrade.php' : 'register.php'; ?>" class="btn-cta large">Quero ser Premium</a>
    </div>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mais Deus. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="js/tuner.js"></script>
</body>
</html>
