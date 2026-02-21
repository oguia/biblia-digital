<?php ob_start(); ?>

<!-- Hero Section -->
<section class="bg-blue-600 text-white relative overflow-hidden">
    <div class="container mx-auto px-4 py-20 text-center relative z-10">
        <h1 class="text-4xl font-bold mb-4">Descubra as melhores empresas de Curitiba</h1>
        <p class="text-xl mb-8">Encontre serviços, produtos e muito mais em sua região.</p>

        <!-- Search Form -->
        <form action="/busca" method="GET" class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-2 flex flex-col md:flex-row gap-2">
            <div class="flex-grow flex items-center border-b md:border-b-0 md:border-r border-gray-200 px-4 py-2">
                <i data-lucide="search" class="text-gray-400 w-5 h-5 mr-2"></i>
                <input type="text" name="q" placeholder="O que você procura?" class="w-full focus:outline-none text-gray-700">
            </div>
            <div class="flex-grow flex items-center px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200">
                <i data-lucide="map-pin" class="text-gray-400 w-5 h-5 mr-2"></i>
                <input type="text" name="neighborhood" placeholder="Bairro (ex: Batel)" class="w-full focus:outline-none text-gray-700">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 transition font-medium">Buscar</button>
        </form>

        <!-- Quick Categories -->
        <div class="flex justify-center flex-wrap gap-4 mt-8">
            <a href="/busca?category=restaurante" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm backdrop-blur-sm transition">🍕 Restaurantes</a>
            <a href="/busca?category=farmacia" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm backdrop-blur-sm transition">💊 Farmácias</a>
            <a href="/busca?category=hotel" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm backdrop-blur-sm transition">🏨 Hotéis</a>
            <a href="/busca?category=oficina-mecanica" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm backdrop-blur-sm transition">🔧 Oficinas</a>
        </div>
    </div>

    <!-- Pattern Background -->
    <div class="absolute inset-0 opacity-10 bg-[url('/img_exemplo.png')] bg-cover bg-center mix-blend-overlay"></div>
</section>

<!-- AI Assistant Section -->
<section class="bg-indigo-50 py-16">
    <div class="container mx-auto px-4 text-center">
        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide mb-4 inline-block">✨ Novo: Assistente IA</span>
        <h2 class="text-3xl font-bold mb-4 text-gray-900">Pergunte ao GuiaBot</h2>
        <p class="text-gray-600 mb-8 max-w-2xl mx-auto">Não sabe exatamente o que procura? Nossa Inteligência Artificial encontra as melhores opções para você.</p>

        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden border border-indigo-100">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <span class="font-medium text-gray-600 flex items-center gap-2"><i data-lucide="bot" class="text-indigo-600 w-5 h-5"></i> GuiaBot</span>
                <span class="text-xs text-gray-400">Powered by Gemini</span>
            </div>
            <div id="ai-chat-response" class="p-6 text-left min-h-[100px] text-gray-700 hidden">
                <!-- Response will appear here -->
            </div>
            <div class="p-4 bg-white border-t border-gray-100 flex gap-2">
                <input type="text" id="ai-chat-input" placeholder="Ex: Qual a pizzaria mais barata no centro?" class="flex-grow bg-gray-100 border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <button id="ai-chat-btn" class="bg-indigo-600 text-white p-3 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center min-w-[50px]">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-8 text-gray-900">Categorias Populares</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ($categories as $cat): ?>
            <a href="/busca?category=<?= htmlspecialchars($cat['slug']) ?>" class="group block text-center p-6 border border-gray-100 rounded-xl hover:border-blue-200 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                    <i data-lucide="layout-grid" class="w-6 h-6"></i>
                </div>
                <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition"><?= htmlspecialchars($cat['name']) ?></h3>
                <span class="text-xs text-gray-400"><?= $cat['company_count'] ?> empresas</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Companies -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Empresas em Destaque</h2>
                <p class="text-gray-500">As melhores avaliadas da região</p>
            </div>
            <a href="/busca" class="text-blue-600 font-medium hover:underline">Ver todas &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured as $comp): ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100">
                <div class="h-48 bg-gray-200 relative">
                    <!-- Dynamic Facade Image -->
                    <img src="/image.php?name=<?= urlencode($comp['name']) ?>" alt="<?= htmlspecialchars($comp['name']) ?>" class="w-full h-full object-cover">
                    <?php if($comp['is_featured']): ?>
                        <span class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded shadow">DESTAQUE</span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <div class="text-xs text-blue-600 font-bold mb-2 uppercase tracking-wide"><?= htmlspecialchars($comp['category_name']) ?></div>
                    <h3 class="font-bold text-lg mb-2 text-gray-900 line-clamp-1">
                        <a href="/empresa/<?= $comp['slug'] ?>" class="hover:text-blue-600 transition"><?= htmlspecialchars($comp['name']) ?></a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($comp['description']) ?></p>

                    <div class="flex items-center text-gray-400 text-sm mb-4">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                        <?= htmlspecialchars($comp['neighborhood_name']) ?>
                    </div>

                    <a href="/empresa/<?= $comp['slug'] ?>" class="block text-center border border-blue-600 text-blue-600 font-medium py-2 rounded hover:bg-blue-600 hover:text-white transition">Ver Perfil</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
