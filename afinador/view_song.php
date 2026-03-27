<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/scraper.php';

requireLogin();

if (!isPremium()) {
    header("Location: upgrade.php");
    exit;
}

$artistSlug = isset($_GET['artist']) ? $_GET['artist'] : '';
$songSlug = isset($_GET['song']) ? $_GET['song'] : '';
$error = '';
$data = null;

if ($artistSlug && $songSlug) {
    $data = getChord($artistSlug, $songSlug);
    if (!$data['success']) {
        $error = $data['message'];
    }
} else {
    header("Location: search.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data ? htmlspecialchars($data['title']) : 'Erro'; ?> - Afinador Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .chord-header {
            background: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            text-align: center;
            position: relative;
        }
        .chord-header h1 { margin: 0; color: var(--primary-red); }
        .chord-header h2 { margin: 0.5rem 0 0; color: #666; font-weight: normal; }
        .chord-header .tone {
            margin-top: 1rem;
            display: inline-block;
            background: #eee;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .chord-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow-x: auto;
            font-size: 16px;
            line-height: 1.5;
        }

        pre {
            font-family: 'Courier New', Courier, monospace;
            white-space: pre;
            margin: 0;
        }

        pre b {
            color: var(--primary-red);
            font-weight: bold;
        }

        .error-container { text-align: center; padding: 4rem 2rem; }
        .back-btn { display: inline-block; margin-top: 1rem; color: var(--primary-red); text-decoration: none; font-weight: bold; }

        /* Floating Tools */
        .tools-bar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 100;
        }
        .tool-btn {
            background: var(--secondary-dark);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }
        .tool-btn:hover { transform: scale(1.1); background: var(--primary-red); }
        .tool-tooltip {
            position: absolute;
            right: 60px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            white-space: nowrap;
        }
        .tool-btn:hover + .tool-tooltip { opacity: 1; }

        @media print {
            header, footer, .back-btn, .tools-bar { display: none; }
            .chord-content { box-shadow: none; padding: 0; }
            body { background: white; }
        }
    </style>
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function(e) {
            if(e.keyCode == 123) { return false; }
            if(e.ctrlKey && (e.key === 'u' || e.key === 's')) { return false; }
        }
    </script>
</head>
<body>

<header>
    <div class="container navbar">
        <a href="index.php" class="logo-text">MAISDEUS<span>.COM</span></a>
        <nav class="nav-links">
            <a href="index.php">Afinador</a>
            <a href="search.php">Nova Busca</a>
        </nav>
    </div>
</header>

<div class="container">
    <?php if ($error): ?>
        <div class="error-container">
            <h2>Ops!</h2>
            <p><?php echo $error; ?></p>
            <p>Tente verificar se a cifra está disponível.</p>
            <a href="search.php" class="btn-cta">Tentar Novamente</a>
        </div>
    <?php else: ?>
        <div class="chord-header">
            <h1><?php echo htmlspecialchars($data['title']); ?></h1>
            <h2><?php echo htmlspecialchars($data['artist']); ?></h2>
            <?php if (!empty($data['tone'])): ?>
                <div class="tone">Tom: <span id="key"><?php echo htmlspecialchars($data['tone']); ?></span></div>
            <?php endif; ?>
            <br>
            <a href="search.php" class="back-btn">&larr; Buscar outra música</a>
        </div>

        <div class="chord-content">
            <pre id="chord-text"><?php echo $data['content']; ?></pre>
        </div>

        <!-- Floating Tools for Premium Users -->
        <div class="tools-bar">
            <div>
                <button class="tool-btn" id="btn-transpose-up" title="Aumentar Tom">♯</button>
                <span class="tool-tooltip">Aumentar meio tom</span>
            </div>
            <div>
                <button class="tool-btn" id="btn-transpose-down" title="Diminuir Tom">♭</button>
                <span class="tool-tooltip">Diminuir meio tom</span>
            </div>
            <div>
                <button class="tool-btn" id="btn-autoscroll" title="Auto Rolagem">⬇</button>
                <span class="tool-tooltip">Auto Rolagem</span>
            </div>
            <div>
                <button class="tool-btn" onclick="window.print()" title="Imprimir / PDF">🖨️</button>
                <span class="tool-tooltip">Salvar PDF / Imprimir</span>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mais Deus. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="js/transpose.js"></script>
</body>
</html>
