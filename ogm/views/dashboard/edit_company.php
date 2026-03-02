<?php $title = ($company ? 'Editar' : 'Cadastrar') . ' Empresa - O Guia Metropolitano'; ?>
<?php ob_start(); ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800"><?= $company ? 'Editar Empresa' : 'Cadastrar Nova Empresa' ?></h1>
            <a href="/dashboard" class="text-gray-500 hover:text-gray-700 flex items-center gap-1 text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Voltar
            </a>
        </div>

        <form action="/dashboard/empresa" method="POST" class="px-8 py-8 space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Empresa</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($company['name'] ?? '') ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($company['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                    <select name="neighborhood_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="">Selecione...</option>
                        <?php foreach ($neighborhoods as $neigh): ?>
                            <option value="<?= $neigh['id'] ?>" <?= ($company['neighborhood_id'] ?? '') == $neigh['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($neigh['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Contact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Endereço Completo</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($company['address'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Ex: Av. Batel, 1230 - Batel, Curitiba - PR">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="(41) 3333-3333">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="message-circle" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="whatsapp" value="<?= htmlspecialchars($company['whatsapp'] ?? '') ?>"
                            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="(41) 99999-9999">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Conte um pouco sobre sua empresa..."><?= htmlspecialchars($company['description'] ?? '') ?></textarea>
            </div>

            <!-- Image Note -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 flex gap-3 items-start">
                <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0"></i>
                <div class="text-sm text-blue-700">
                    <p class="font-medium">Imagem de Capa</p>
                    <p>Por enquanto, geramos uma imagem automática com o nome da sua empresa. Em breve você poderá fazer upload de fotos personalizadas.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="/dashboard" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
