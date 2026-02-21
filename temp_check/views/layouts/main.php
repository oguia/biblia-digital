<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'O Guia Metropolitano' ?></title>
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .leaflet-container { height: 400px; width: 100%; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <img src="/logo.svg" alt="Logo" class="h-10 w-10">
                <span class="font-bold text-xl text-blue-600 hidden sm:block">O Guia Metropolitano</span>
            </a>

            <div class="flex items-center gap-4">
                <a href="/busca" class="text-gray-600 hover:text-blue-600 hidden md:block">Explorar</a>
                <a href="/register" class="text-gray-600 hover:text-blue-600 hidden md:block">Para Empresas</a>

                <?php if (Auth::check()): ?>
                    <a href="<?= Auth::isAdmin() ? '/admin' : '/dashboard' ?>" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition flex items-center gap-2">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <a href="/logout" class="text-gray-500 hover:text-red-600" title="Sair">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </a>
                <?php else: ?>
                    <a href="/login" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <img src="/logo.svg" alt="Logo" class="h-8 w-8 grayscale brightness-200">
                    <span class="font-bold text-lg">O Guia Metropolitano</span>
                </div>
                <p class="text-gray-400 text-sm">O maior guia comercial de Curitiba e Região.</p>
            </div>
            <div>
                <h3 class="font-bold mb-4">Empresa</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white">Sobre Nós</a></li>
                    <li><a href="#" class="hover:text-white">Anuncie</a></li>
                    <li><a href="#" class="hover:text-white">Contato</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Legal</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white">Termos de Uso</a></li>
                    <li><a href="#" class="hover:text-white">Privacidade</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Newsletter</h3>
                <form class="flex gap-2">
                    <input type="email" placeholder="Seu email" class="bg-gray-800 border-none rounded px-3 py-2 text-sm w-full">
                    <button class="bg-blue-600 px-3 py-2 rounded hover:bg-blue-700">OK</button>
                </form>
            </div>
        </div>
        <div class="container mx-auto px-4 mt-8 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> O Guia Metropolitano. Todos os direitos reservados.
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    <script src="/js/main.js"></script>
</body>
</html>
