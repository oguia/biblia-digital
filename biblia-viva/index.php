<?php
require_once 'includes/functions.php';

$model = new BibliaModel();

// Parâmetros da URL ou Padrões
$livroId = isset($_GET['livro']) ? (int)$_GET['livro'] : 1; // Gênesis
$capitulo = isset($_GET['cap']) ? (int)$_GET['cap'] : 1;
$versaoId = isset($_GET['versao']) ? (int)$_GET['versao'] : 1; // Padrão (NVI ou similar)

// Buscar Dados
$livros = $model->getLivros();
$versoes = $model->getVersoes();
$livroAtual = $model->getBookById($livroId);

// Validação de Livro Inválido
if (!$livroAtual) {
    // Redirecionar para Gênesis 1 se o livro não existir
    header("Location: ?livro=1&cap=1&versao={$versaoId}");
    exit;
}

$versiculos = $model->getVersiculos($livroId, $capitulo, $versaoId);
$contextoGeo = $model->getContextoGeografico($livroId, $capitulo);
$cronologia = $model->getCronologia($livroId, $capitulo);
$aplicacao = $model->getAplicacaoPratica($livroId, $capitulo);

// Título da Página
$tituloPagina = "{$livroAtual['liv_nome']} {$capitulo} - Bíblia Viva";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?></title>

    <!-- Tailwind CSS (CDN para prototipagem rápida/produção leve) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9F1414',
                        secondary: '#2C323E',
                    }
                }
            }
        }
    </script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bible-text { font-family: 'Merriweather', serif; line-height: 1.8; }
        .leaflet-container { height: 400px; width: 100%; border-radius: 0.5rem; z-index: 10; }
    </style>
</head>
<body class="bg-gray-50 text-secondary">

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="assets/img/logo.png" alt="Mais Deus" class="h-10 w-auto">
                <div class="hidden md:block">
                    <h1 class="text-xl font-bold text-secondary tracking-tight">Bíblia Viva</h1>
                    <p class="text-xs text-primary font-semibold tracking-wider uppercase">A Bíblia em Contexto</p>
                </div>
            </div>

            <!-- Navegação Rápida -->
            <div class="flex items-center gap-4">
                <select id="versaoSelect" class="bg-gray-100 border-none text-sm rounded-md px-3 py-2 focus:ring-2 focus:ring-primary">
                    <?php foreach ($versoes as $v): ?>
                        <option value="<?= $v['vrs_id'] ?>" <?= $v['vrs_id'] == $versaoId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['vrs_nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="#" class="text-secondary hover:text-primary transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

        <?php include 'views/leitor.php'; ?>

        <?php include 'views/contexto.php'; ?>

    </main>

    <footer class="bg-secondary text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="opacity-75 text-sm">&copy; <?= date('Y') ?> Mais Deus. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Dados PHP para JS
        window.bibliaLocais = <?= json_encode($contextoGeo) ?>;
        const versoesId = <?= $versaoId ?>;
    </script>
    <script src="assets/js/main.js"></script>
</body>
</html>
