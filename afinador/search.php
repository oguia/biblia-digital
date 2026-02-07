<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

// Check if user is premium
if (!isPremium()) {
    header("Location: upgrade.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cifras - Afinador Mais Deus</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .search-container {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1.1rem;
        }
        .btn-search {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-red);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-search:hover { background-color: #a00000; }
        .helper-text {
            font-size: 0.9rem;
            color: #666;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <div class="container navbar">
        <a href="index.php" class="logo-text">MAISDEUS<span>.COM</span></a>
        <nav class="nav-links">
            <a href="index.php">Afinador</a>
            <a href="search.php">Buscar Cifras</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="search-container">
        <h1 class="text-center mb-4">Buscar Cifra</h1>
        <p class="text-center mb-4">Encontre qualquer música para tocar.</p>

        <form action="view_song.php" method="GET">
            <div class="form-group">
                <label for="artist">Nome do Artista / Banda</label>
                <input type="text" id="artist" name="artist" placeholder="Ex: Aline Barros" required>
            </div>

            <div class="form-group">
                <label for="song">Nome da Música</label>
                <input type="text" id="song" name="song" placeholder="Ex: Ressuscita-me" required>
            </div>

            <button type="submit" class="btn-search">Buscar Cifra</button>
        </form>

        <p class="helper-text">
            * Buscamos diretamente de grandes portais de cifras.<br>
            * Digite o nome corretamente para garantir o resultado.
        </p>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mais Deus. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>
