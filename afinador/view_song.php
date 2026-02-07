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
    // getChord now accepts slugs directly
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
            font-size: 16px; /* Adjustable? */
        }

        pre {
            font-family: 'Courier New', Courier, monospace;
            white-space: pre;
            margin: 0;
            line-height: 1.5;
        }

        /* Cifra Club specific styling overrides */
        pre b {
            color: var(--primary-red);
            font-weight: bold;
        }

        .error-container {
            text-align: center;
            padding: 4rem 2rem;
        }

        .back-btn {
            display: inline-block;
            margin-top: 1rem;
            color: var(--primary-red);
            text-decoration: none;
            font-weight: bold;
        }

        /* Anti-Copy Scripts */
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media print {
            header, footer, .back-btn { display: none; }
            .chord-content { box-shadow: none; padding: 0; }
        }
    </style>
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function(e) {
            if(e.keyCode == 123) { return false; } // F12
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) { return false; } // Ctrl+Shift+I
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) { return false; } // Ctrl+Shift+C
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) { return false; } // Ctrl+Shift+J
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) { return false; } // Ctrl+U
            if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) { return false; } // Ctrl+S
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
                <div class="tone">Tom: <?php echo htmlspecialchars($data['tone']); ?></div>
            <?php endif; ?>
            <br>
            <a href="search.php" class="back-btn">&larr; Buscar outra música</a>
        </div>

        <div class="chord-content">
            <pre><?php echo $data['content']; ?></pre>
        </div>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mais Deus. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>
