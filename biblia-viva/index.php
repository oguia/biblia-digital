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
$totalCapitulos = $model->getTotalCapitulos($livroId);

// Título da Página
$tituloPagina = "{$livroAtual['liv_nome']} {$capitulo} - Bíblia Viva";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?></title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#9F1414">

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
<body class="bg-gray-50 text-secondary overflow-x-hidden">

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
                <select id="versaoSelect" class="bg-gray-100 border-none text-sm rounded-md px-3 py-2 focus:ring-2 focus:ring-primary max-w-[120px] md:max-w-xs truncate">
                    <?php foreach ($versoes as $v): ?>
                        <option value="<?= $v['vrs_id'] ?>" <?= $v['vrs_id'] == $versaoId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['vrs_nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button id="installAppBtn" onclick="handleInstallApp()" class="bg-primary text-white text-xs px-3 py-1 rounded-full font-bold hover:bg-red-800 transition shadow-sm mr-2" title="Instalar Aplicativo">
                    Instalar App
                </button>

                <button id="shareButton" onclick="handleShare()" class="text-secondary hover:text-primary transition focus:outline-none" title="Compartilhar Estudo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                </button>

                <button id="menuButton" class="text-secondary hover:text-primary transition focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobileMenu" class="hidden border-t border-gray-100 bg-white">
            <div class="container mx-auto px-4 py-2 flex flex-col gap-2">
                <a href="#" class="block py-2 text-secondary hover:text-primary font-medium">Início</a>
                <a href="#" class="block py-2 text-secondary hover:text-primary font-medium">Sobre o Projeto</a>
                <a href="#" class="block py-2 text-secondary hover:text-primary font-medium">Contato</a>
            </div>
        </div>
    </header>

    <!-- Mobile Tabs (Sticky) -->
    <div class="lg:hidden bg-white border-b border-gray-200 sticky top-[64px] z-40 flex shadow-sm">
        <button id="tab-btn-texto" class="flex-1 py-3 text-center text-sm font-bold border-b-2 border-primary text-primary transition focus:outline-none">
            Texto
        </button>
        <button id="tab-btn-contexto" class="flex-1 py-3 text-center text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-secondary transition focus:outline-none">
            Contexto
        </button>
    </div>

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

        // INLINE LOGIC TO BYPASS CACHE ISSUES
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('Evento beforeinstallprompt disparado (Inline)!');
            e.preventDefault();
            deferredPrompt = e;
        });

        function handleInstallApp() {
            console.log('Botão Instalar Clicado (Inline). DeferredPrompt:', deferredPrompt);
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            } else {
                alert('Para instalar o App:\n\n📱 iPhone/iPad: Toque no botão Compartilhar e escolha "Adicionar à Tela de Início".\n\n🤖 Android: Toque no menu do navegador (três pontos) e escolha "Instalar aplicativo".');
            }
        }

        async function handleShare() {
            const shareData = {
                title: document.title,
                text: 'Estou lendo este capítulo na Bíblia Viva: A Bíblia em Contexto.',
                url: window.location.href
            };

            if (navigator.share) {
                try {
                    await navigator.share(shareData);
                } catch (err) {
                    console.log('Compartilhamento cancelado ou falhou:', err);
                }
            } else {
                prompt('Copie o link abaixo para compartilhar:', window.location.href);
            }
        }
    </script>
    <script src="assets/js/main.js?v=<?= time() ?>"></script>
</body>
</html>
