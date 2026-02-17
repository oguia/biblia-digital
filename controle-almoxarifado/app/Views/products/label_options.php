<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gerar Etiquetas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('products/index') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Produto: <?= htmlspecialchars($product['nome']) ?> (<?= htmlspecialchars($product['codigo']) ?>)</h5>

        <form action="<?= base_url('products/printLabels') ?>" method="POST" target="_blank">
            <?= csrf_field() ?>
            <input type="hidden" name="products[0][id]" value="<?= $product['id'] ?>">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="quantidade" class="form-label">Quantidade de Etiquetas</label>
                    <input type="number" class="form-control" id="quantidade" name="products[0][qty]" value="1" min="1" required>
                </div>
                <div class="col-md-4">
                    <label for="formato" class="form-label">Formato</label>
                    <select class="form-select" id="formato" name="formato">
                        <option value="html">Impressão Direta (Térmica 10x5cm)</option>
                        <option value="pdf">PDF (Folha A4)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-printer"></i> Gerar
            </button>
        </form>
    </div>
</div>
