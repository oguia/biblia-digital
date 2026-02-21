<?php ob_start(); ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="w-full md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-24">
                <h3 class="font-bold text-lg mb-4">Filtros</h3>

                <form action="/busca" method="GET">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="q" value="<?= htmlspecialchars($query ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                        <select name="category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="">Todas</option>
                            <!-- Would populate dynamically in real app -->
                            <option value="restaurante" <?= ($filters['category'] ?? '') == 'restaurante' ? 'selected' : '' ?>>Restaurante</option>
                            <option value="farmacia" <?= ($filters['category'] ?? '') == 'farmacia' ? 'selected' : '' ?>>Farmácia</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                        <input type="text" name="neighborhood" value="<?= htmlspecialchars($filters['neighborhood'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Filtrar</button>
                </form>
            </div>
        </aside>

        <!-- Results -->
        <div class="w-full md:w-3/4">
            <h1 class="text-2xl font-bold mb-6">Resultados da Busca</h1>

            <?php if (empty($companies)): ?>
                <div class="bg-yellow-50 text-yellow-800 p-4 rounded border border-yellow-200">
                    Nenhuma empresa encontrada com estes filtros. Tente buscar algo diferente.
                </div>
            <?php else: ?>
                <div class="grid gap-6">
                    <?php foreach ($companies as $comp): ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 hover:shadow-md transition">
                        <div class="w-full md:w-48 h-32 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                             <img src="/image.php?name=<?= urlencode($comp['name']) ?>" alt="<?= htmlspecialchars($comp['name']) ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1 block"><?= htmlspecialchars($comp['category_name']) ?></span>
                                    <h2 class="text-xl font-bold text-gray-900 mb-1">
                                        <a href="/empresa/<?= $comp['slug'] ?>" class="hover:text-blue-600"><?= htmlspecialchars($comp['name']) ?></a>
                                    </h2>
                                    <div class="flex items-center text-gray-500 text-sm mb-3">
                                        <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                        <?= htmlspecialchars($comp['address']) ?>, <?= htmlspecialchars($comp['number']) ?> - <?= htmlspecialchars($comp['neighborhood_name']) ?>
                                    </div>
                                </div>
                                <?php if($comp['is_featured']): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">DESTAQUE</span>
                                <?php endif; ?>
                            </div>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($comp['description']) ?></p>

                            <div class="flex gap-3">
                                <a href="/empresa/<?= $comp['slug'] ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-sm font-medium transition">Ver Detalhes</a>
                                <?php if($comp['whatsapp']): ?>
                                    <a href="https://wa.me/<?= $comp['whatsapp'] ?>" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm font-medium transition flex items-center gap-2">
                                        <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
