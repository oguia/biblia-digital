<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gerar Etiquetas em Lote</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('products/index') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('products/printLabels') ?>" method="POST" target="_blank">
            <?= csrf_field() ?>

            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Código</th>
                            <th style="width: 150px;">Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $idx => $prod): ?>
                            <tr>
                                <td><?= htmlspecialchars($prod['nome']) ?></td>
                                <td><?= htmlspecialchars($prod['codigo']) ?></td>
                                <td>
                                    <input type="hidden" name="products[<?= $idx ?>][id]" value="<?= $prod['id'] ?>">
                                    <input type="number" class="form-control" name="products[<?= $idx ?>][qty]" value="1" min="0">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="formato" class="form-label">Formato de Impressão</label>
                    <select class="form-select" id="formato" name="formato">
                        <option value="html">Impressão Direta (Térmica 10x5cm)</option>
                        <option value="pdf">PDF (Folha A4)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-printer"></i> Gerar Todas
            </button>
        </form>
    </div>
</div>
