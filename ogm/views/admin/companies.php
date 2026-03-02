<?php $title = 'Gerenciar Empresas - Admin'; ?>
<?php ob_start(); ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gerenciar Empresas</h1>
        <div class="flex gap-2">
            <a href="/admin/empresas?status=all" class="px-3 py-1 rounded-full text-sm font-medium border <?= $status === 'all' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300' ?>">Todas</a>
            <a href="/admin/empresas?status=pending" class="px-3 py-1 rounded-full text-sm font-medium border <?= $status === 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-600 border-gray-300' ?>">Pendentes</a>
            <a href="/admin/empresas?status=active" class="px-3 py-1 rounded-full text-sm font-medium border <?= $status === 'active' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-300' ?>">Ativas</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Empresa</th>
                        <th class="px-6 py-3 font-medium">Categoria / Bairro</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($companies)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Nenhuma empresa encontrada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($companies as $comp): ?>
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($comp['name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($comp['owner_email'] ?? 'Sem email') ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($comp['category_name'] ?? '-') ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($comp['neighborhood_name'] ?? '-') ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        <?= $comp['status'] === 'active' ? 'bg-green-100 text-green-800' :
                                           ($comp['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= ucfirst($comp['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2 items-center">
                                    <a href="/empresa/<?= $comp['slug'] ?>" target="_blank" class="text-gray-400 hover:text-blue-600" title="Ver Página">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>

                                    <?php if ($comp['status'] === 'pending' || $comp['status'] === 'blocked'): ?>
                                        <a href="/admin/empresa/<?= $comp['id'] ?>/aprovar" class="bg-green-100 text-green-700 p-1.5 rounded hover:bg-green-200 transition" title="Aprovar">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($comp['status'] === 'active' || $comp['status'] === 'pending'): ?>
                                        <a href="/admin/empresa/<?= $comp['id'] ?>/bloquear" class="bg-red-100 text-red-700 p-1.5 rounded hover:bg-red-200 transition" title="Bloquear">
                                            <i data-lucide="ban" class="w-4 h-4"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-center gap-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&status=<?= $status ?>"
                       class="px-3 py-1 rounded border text-sm <?= $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
