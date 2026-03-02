<?php $title = 'Admin Panel - O Guia Metropolitano'; ?>
<?php ob_start(); ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Painel Administrativo</h1>
        <div class="text-sm text-gray-500">Logado como Admin</div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <i data-lucide="building-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Empresas</div>
                    <div class="text-2xl font-bold"><?= $stats['total_companies'] ?></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Pendentes</div>
                    <div class="text-2xl font-bold"><?= $stats['pending_companies'] ?></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Ativas</div>
                    <div class="text-2xl font-bold"><?= $stats['active_companies'] ?></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Usuários</div>
                    <div class="text-2xl font-bold"><?= $stats['total_users'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4 mb-8">
        <a href="/admin/empresas?status=pending" class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition flex items-center gap-2 shadow-sm">
            <i data-lucide="check-square" class="w-5 h-5"></i> Moderar Pendentes
        </a>
        <a href="/admin/empresas" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
            <i data-lucide="list" class="w-5 h-5"></i> Listar Todas Empresas
        </a>
    </div>

    <!-- Recent Companies -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Empresas Recentes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Nome</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Data</th>
                        <th class="px-6 py-3 font-medium text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($recent as $comp): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-800 font-medium"><?= htmlspecialchars($comp['name']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    <?= $comp['status'] === 'active' ? 'bg-green-100 text-green-800' :
                                       ($comp['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                    <?= ucfirst($comp['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm"><?= date('d/m/Y', strtotime($comp['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="/empresa/<?= $comp['slug'] ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
