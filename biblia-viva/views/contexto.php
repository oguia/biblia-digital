        <!-- Coluna Direita: Contexto -->
        <aside id="tab-content-contexto" class="lg:w-1/3 w-full space-y-6 hidden lg:block">

            <!-- Mapa -->
            <div id="map-container" class="<?= count($contextoGeo) > 0 ? '' : 'hidden' ?> bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-secondary flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        Geografia Bíblica
                    </h3>
                    <span id="geo-count" class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full font-bold"><?= count($contextoGeo) ?> Locais</span>
                </div>
                <div id="map" class="h-64 md:h-80 w-full z-0"></div>

                <!-- Lista de Locais -->
                <div id="geo-list" class="max-h-60 overflow-y-auto p-4 space-y-3">
                    <?php foreach ($contextoGeo as $geo): ?>
                        <div class="flex gap-3 items-start group cursor-pointer hover:bg-gray-50 p-2 rounded transition" onclick="focarMapa(<?= $geo['latitude'] ?>, <?= $geo['longitude'] ?>, '<?= addslashes($geo['nome']) ?>')">
                            <div class="w-2 h-2 mt-2 rounded-full bg-primary flex-shrink-0"></div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-800 group-hover:text-primary transition"><?= htmlspecialchars($geo['nome']) ?></h4>
                                <p class="text-xs text-gray-500 line-clamp-2"><?= htmlspecialchars($geo['descricao']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="no-geo-data" class="<?= count($contextoGeo) > 0 ? 'hidden' : '' ?> bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                <p class="text-gray-500 text-sm">Nenhum dado geográfico registrado para este capítulo.</p>
            </div>

            <!-- Cronologia -->
            <div id="cronologia-container" class="<?= $cronologia ? '' : 'hidden' ?> bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-secondary flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Linha do Tempo
                    </h3>
                </div>
                <div class="p-6 relative">
                    <!-- Linha Vertical -->
                    <div class="absolute left-8 top-6 bottom-6 w-0.5 bg-gray-200"></div>

                    <!-- Item 1: Ano -->
                    <div class="relative pl-10 mb-6">
                        <div class="absolute left-6 top-1.5 w-4 h-4 rounded-full bg-primary border-4 border-white shadow-sm"></div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wider">Período</span>
                        <h4 id="crono-periodo" class="text-lg font-bold text-gray-800"><?= htmlspecialchars($cronologia['periodo'] ?? '') ?></h4>
                        <p id="crono-ano" class="text-sm text-gray-500">Aprox. <?= htmlspecialchars($cronologia['ano_estimado'] ?? '') ?></p>
                    </div>

                    <!-- Item 2: Personagens -->
                    <div class="relative pl-10 mb-6">
                        <div class="absolute left-7 top-2 w-2 h-2 rounded-full bg-gray-300"></div>
                        <h5 class="text-sm font-bold text-gray-700 mb-1">Personagens Chave</h5>
                        <p id="crono-personagens" class="text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($cronologia['personagens'] ?? '') ?></p>
                    </div>

                    <!-- Item 3: Contexto Mundial -->
                    <div class="relative pl-10 mb-6">
                        <div class="absolute left-7 top-2 w-2 h-2 rounded-full bg-gray-300"></div>
                        <h5 class="text-sm font-bold text-gray-700 mb-1">No Mundo</h5>
                        <p id="crono-eventos" class="text-sm text-gray-600 italic leading-relaxed">"<?= htmlspecialchars($cronologia['eventos_mundiais'] ?? '') ?>"</p>
                    </div>

                    <!-- Item 4: Conexão com Jesus -->
                    <div id="crono-jesus-container" class="relative pl-10 <?= !empty($cronologia['conexao_jesus']) ? '' : 'hidden' ?>">
                        <div class="absolute left-6 top-1.5 w-4 h-4 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-red-600 rounded-full"></div>
                        </div>
                        <h5 class="text-sm font-bold text-red-700 mb-1">Conexão com Jesus</h5>
                        <p id="crono-jesus" class="text-sm text-gray-700 font-medium leading-relaxed bg-red-50 p-3 rounded-lg border border-red-100">
                            <?= htmlspecialchars($cronologia['conexao_jesus'] ?? '') ?>
                        </p>
                    </div>
                </div>
            </div>

        </aside>
