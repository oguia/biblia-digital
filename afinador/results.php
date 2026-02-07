<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/scraper.php';

requireLogin();

// Check if user is premium
if (!isPremium()) {
    header("Location: upgrade.php");
    exit;
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$error = '';
$results = [];

if ($query) {
    // Perform search
    $searchData = searchSongs($query);

    if ($searchData['success']) {
        $results = $searchData['results'];
    } else {
        $error = $searchData['message'];
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
    <title>Resultados: <?php echo htmlspecialchars($query); ?> - Afinador Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .results-container {
            max-width: 800px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .result-item {
            display: block;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }
        .result-item:hover {
            background-color: #f9f9f9;
        }
        .result-item:last-child { border-bottom: none; }
        .result-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-red);
            margin-bottom: 0.5rem;
        }
        .result-artist {
            color: #666;
            font-size: 1rem;
        }
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--primary-red);
            text-decoration: none;
            font-weight: bold;
        }
    </style>
    <script>
        // Disable context menu
        document.addEventListener('contextmenu', event => event.preventDefault());
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
    <div class="results-container">
        <a href="search.php" class="back-link">&larr; Voltar para Busca</a>
        <h1 class="mb-4">Resultados para "<?php echo htmlspecialchars($query); ?>"</h1>

        <?php if ($error): ?>
            <div class="no-results">
                <p><?php echo $error; ?></p>
            </div>
        <?php elseif (empty($results)): ?>
            <div class="no-results">
                <p>Nenhuma cifra encontrada. Tente termos diferentes.</p>
            </div>
        <?php else: ?>
            <div class="results-list">
                <?php foreach ($results as $item): ?>
                    <a href="view_song.php?artist=<?php echo urlencode($item['artist_slug']); ?>&song=<?php echo urlencode($item['song_slug']); ?>" class="result-item">
                        <div class="result-title"><?php echo htmlspecialchars($item['display_title']); ?></div>
                        <div class="result-artist">Artista: <?php echo htmlspecialchars($item['display_artist']); ?></div>
                    </a>
                <?php endforeach; ?>
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
