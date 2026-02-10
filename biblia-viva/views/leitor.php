        <!-- Coluna Esquerda: Texto Bíblico -->
        <section id="tab-content-texto" class="lg:w-2/3 w-full block">

            <!-- Navegação Livro/Capítulo -->
            <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between border border-gray-100">
                <div class="w-full sm:w-auto">
                    <select id="livroSelect" class="w-full sm:w-auto bg-white border border-gray-200 text-lg font-bold text-secondary rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary outline-none">
                        <?php foreach ($livros as $l): ?>
                            <option value="<?= $l['liv_id'] ?>" <?= $l['liv_id'] == $livroId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['liv_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-center justify-between w-full sm:w-auto bg-gray-50 sm:bg-transparent rounded-lg p-1 sm:p-0">
                    <a href="?livro=<?= $livroId ?>&cap=<?= max(1, $capitulo - 1) ?>&versao=<?= $versaoId ?>" class="p-3 hover:bg-gray-200 sm:hover:bg-gray-100 rounded-lg transition <?= $capitulo <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
                        <svg class="w-6 h-6 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <span class="text-xl font-bold px-4">Cap. <?= $capitulo ?></span>
                    <!-- Nota: Idealmente verificar se existe próximo capítulo -->
                    <a href="?livro=<?= $livroId ?>&cap=<?= $capitulo + 1 ?>&versao=<?= $versaoId ?>" class="p-3 hover:bg-gray-200 sm:hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Texto -->
            <div class="bg-white p-6 md:p-10 rounded-xl shadow-sm border border-gray-100 bible-text text-gray-800 text-lg break-words">
                <?php if (count($versiculos) > 0): ?>
                    <?php foreach ($versiculos as $v): ?>
                        <p class="mb-4">
                            <sup class="text-xs text-primary font-bold mr-1 select-none"><?= $v['ver_versiculo'] ?></sup>
                            <?= $v['ver_texto'] ?>
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-10 text-gray-500">
                        <p>Texto não encontrado para esta referência.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Aplicação Prática (Mobile/Desktop Inline) -->
            <?php if ($aplicacao): ?>
            <div class="mt-8 bg-gradient-to-br from-primary to-red-800 rounded-xl p-6 text-white shadow-lg">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Aplicação Prática
                </h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs uppercase tracking-wider opacity-75 mb-1">Verdade Central</span>
                        <p class="font-medium"><?= htmlspecialchars($aplicacao['verdade_central']) ?></p>
                    </div>
                    <div>
                        <span class="block text-xs uppercase tracking-wider opacity-75 mb-1">Alerta</span>
                        <p class="font-medium"><?= htmlspecialchars($aplicacao['alerta']) ?></p>
                    </div>
                    <div>
                        <span class="block text-xs uppercase tracking-wider opacity-75 mb-1">Ação</span>
                        <p class="font-medium"><?= htmlspecialchars($aplicacao['acao_pratica']) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </section>
