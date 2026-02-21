<?php ob_start(); ?>

<div class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6 text-sm text-gray-500">
            <a href="/" class="hover:text-blue-600">Home</a>
            <span class="mx-2">/</span>
            <a href="/busca?category=<?= htmlspecialchars($company['category_name']) ?>" class="hover:text-blue-600"><?= htmlspecialchars($company['category_name']) ?></a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium"><?= htmlspecialchars($company['name']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Header Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="h-64 bg-gray-200 relative">
                        <img src="/image.php?name=<?= urlencode($company['name']) ?>" alt="<?= htmlspecialchars($company['name']) ?>" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-6 text-white">
                            <span class="bg-blue-600 text-xs font-bold px-2 py-1 rounded mb-2 inline-block"><?= htmlspecialchars($company['category_name']) ?></span>
                            <h1 class="text-3xl font-bold mb-1"><?= htmlspecialchars($company['name']) ?></h1>
                            <div class="flex items-center text-gray-300 text-sm">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                <?= htmlspecialchars($company['neighborhood_name']) ?>, Curitiba
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-wrap gap-4 mb-6">
                            <?php if($company['whatsapp']): ?>
                            <a href="https://wa.me/<?= $company['whatsapp'] ?>" target="_blank" class="flex-1 bg-green-500 text-white py-3 px-4 rounded-lg font-bold text-center hover:bg-green-600 transition flex justify-center items-center gap-2">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                                WhatsApp
                            </a>
                            <?php endif; ?>

                            <?php if($company['phone']): ?>
                            <a href="tel:<?= $company['phone'] ?>" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-bold text-center hover:bg-blue-700 transition flex justify-center items-center gap-2">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                                Ligar
                            </a>
                            <?php endif; ?>
                        </div>

                        <h2 class="text-xl font-bold mb-4">Sobre a Empresa</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            <?= nl2br(htmlspecialchars($company['description'])) ?>
                        </p>

                        <h3 class="font-bold mb-2">Horário de Funcionamento</h3>
                        <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-600">
                            <div class="flex justify-between py-1 border-b border-gray-200 last:border-0">
                                <span>Segunda - Sexta</span>
                                <span class="font-medium">09:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 last:border-0">
                                <span>Sábado</span>
                                <span class="font-medium">09:00 - 13:00</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 last:border-0">
                                <span>Domingo</span>
                                <span class="text-red-500 font-medium">Fechado</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold mb-6">Avaliações</h2>

                    <!-- Placeholder Reviews -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold">João Silva</div>
                                <div class="flex text-yellow-400">
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Excelente atendimento e serviço de qualidade! Recomendo muito.</p>
                            <span class="text-xs text-gray-400 mt-2 block">Há 2 dias</span>
                        </div>
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold">Maria Oliveira</div>
                                <div class="flex text-yellow-400">
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Muito bom, mas o estacionamento estava cheio.</p>
                            <span class="text-xs text-gray-400 mt-2 block">Há 1 semana</span>
                        </div>
                    </div>

                    <button class="w-full mt-6 py-2 border border-blue-600 text-blue-600 rounded font-medium hover:bg-blue-50 transition">Escrever Avaliação</button>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Map -->
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <h3 class="font-bold mb-4">Localização</h3>
                    <div id="map" class="h-64 w-full rounded-lg bg-gray-200 mb-4 z-0"></div>
                    <div class="text-sm text-gray-600 mb-4">
                        <p class="font-medium text-gray-900"><?= htmlspecialchars($company['address']) ?>, <?= htmlspecialchars($company['number']) ?></p>
                        <p><?= htmlspecialchars($company['neighborhood_name']) ?> - Curitiba/PR</p>
                        <p>CEP: <?= htmlspecialchars($company['zip_code']) ?></p>
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $company['latitude'] ?>,<?= $company['longitude'] ?>" target="_blank" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 rounded transition">
                        Como Chegar
                    </a>
                </div>

                <!-- Share -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="font-bold mb-4">Compartilhar</h3>
                    <div class="flex gap-2">
                        <button class="flex-1 bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition flex justify-center"><i data-lucide="facebook" class="w-5 h-5"></i></button>
                        <button class="flex-1 bg-sky-500 text-white p-2 rounded hover:bg-sky-600 transition flex justify-center"><i data-lucide="twitter" class="w-5 h-5"></i></button>
                        <button class="flex-1 bg-green-500 text-white p-2 rounded hover:bg-green-600 transition flex justify-center"><i data-lucide="message-circle" class="w-5 h-5"></i></button>
                        <button class="flex-1 bg-gray-200 text-gray-600 p-2 rounded hover:bg-gray-300 transition flex justify-center"><i data-lucide="link" class="w-5 h-5"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Map
        var map = L.map('map').setView([<?= $company['latitude'] ?>, <?= $company['longitude'] ?>], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([<?= $company['latitude'] ?>, <?= $company['longitude'] ?>]).addTo(map)
            .bindPopup('<?= htmlspecialchars($company['name']) ?>')
            .openPopup();
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
