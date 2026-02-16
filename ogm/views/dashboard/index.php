<?php $title = 'Dashboard - O Guia Metropolitano'; ?>
<?php ob_start(); ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <div class="text-sm text-gray-500">Bem-vindo, <?= htmlspecialchars(Auth::user()['name']) ?></div>
    </div>

    <?php if ($company): ?>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <i data-lucide="eye" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Visualizações</div>
                        <div class="text-2xl font-bold"><?= $company['views'] ?? 0 ?></div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <i data-lucide="phone" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Cliques no Telefone</div>
                        <div class="text-2xl font-bold"><?= $company['phone_clicks'] ?? 0 ?></div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Cliques no WhatsApp</div>
                        <div class="text-2xl font-bold"><?= $company['whatsapp_clicks'] ?? 0 ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Sua Empresa</h2>
                <div class="flex gap-2">
                    <a href="/empresa/<?= $company['slug'] ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                        <i data-lucide="external-link" class="w-4 h-4"></i> Ver Página
                    </a>
                    <a href="/dashboard/empresa" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm flex items-center gap-2">
                        <i data-lucide="edit-2" class="w-4 h-4"></i> Editar Dados
                    </a>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-500 mb-1">Nome</h3>
                    <p class="text-gray-800 font-semibold"><?= htmlspecialchars($company['name']) ?></p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-500 mb-1">Status</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        <?= $company['status'] === 'active' ? 'bg-green-100 text-green-800' :
                           ($company['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                        <?= ucfirst($company['status']) ?>
                    </span>
                </div>
                <!-- More details could go here -->
            </div>
        </div>

    <?php else: ?>
        <!-- No Company State -->
        <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600">
                <i data-lucide="building-2" class="w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Cadastre sua Empresa</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Você ainda não tem uma empresa cadastrada. Crie seu perfil agora para aparecer no guia e ser encontrado por milhares de clientes.
            </p>
            <a href="/dashboard/empresa" class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 font-medium inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i> Cadastrar Empresa
            </a>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
