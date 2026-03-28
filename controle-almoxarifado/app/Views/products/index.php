<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Produtos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?= base_url('products/import') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-upload"></i> Importar
            </a>
            <a href="<?= base_url('products/export') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Exportar
            </a>
        </div>
        <a href="<?= base_url('products/create') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Produto
        </a>
    </div>
</div>

<div class="table-responsive">
    <form action="<?= base_url('products/bulkLabelOptions') ?>" method="POST" id="bulkForm">
    <?= csrf_field() ?>
    <div class="mb-3">
        <button type="submit" class="btn btn-sm btn-outline-dark" onclick="if(!confirm('Gerar etiquetas para os selecionados?')) return false;">
            <i class="bi bi-upc-scan"></i> Gerar Etiquetas (Selecionados)
        </button>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                <th>Código</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Estoque</th>
                <th>Mínimo</th>
                <th>Unidade</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $prod): ?>
                    <?php
                        $lowStock = $prod['quantidade'] <= $prod['estoque_minimo'];
                        $rowClass = $lowStock ? 'table-danger' : '';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td>
                            <input type="checkbox" name="product_ids[]" value="<?= $prod['id'] ?>" class="product-checkbox">
                        </td>
                        <td><?= htmlspecialchars($prod['codigo']) ?></td>
                        <td><?= htmlspecialchars($prod['nome']) ?></td>
                        <td><?= htmlspecialchars($prod['categoria_nome'] ?? '-') ?></td>
                        <td>
                            <strong><?= number_format($prod['quantidade'], 2, ',', '.') ?></strong>
                            <?php if($lowStock): ?>
                                <i class="bi bi-exclamation-triangle-fill text-danger" title="Estoque Baixo"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($prod['estoque_minimo'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($prod['unidade']) ?></td>
                        <td class="text-end">
                            <a href="<?= base_url('products/edit/' . $prod['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <!-- Botão de Etiqueta (Futuro) -->
                            <a href="<?= base_url('products/label/' . $prod['id']) ?>" class="btn btn-sm btn-outline-info" title="Gerar Etiqueta" target="_blank">
                                <i class="bi bi-upc-scan"></i>
                            </a>
                            <form action="<?= base_url('products/delete/' . $prod['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Nenhum produto cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </form>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.product-checkbox');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});
</script>
