<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nova Movimentação</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('movements/index') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('movements/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="produto_id" class="form-label">Produto *</label>
                <select class="form-select" id="produto_id" name="produto_id" required onchange="updateStockInfo()">
                    <option value="">Selecione um produto...</option>
                    <?php foreach ($products as $prod): ?>
                        <option value="<?= $prod['id'] ?>" data-stock="<?= $prod['quantidade'] ?>" data-unit="<?= $prod['unidade'] ?>">
                            <?= htmlspecialchars($prod['codigo']) ?> - <?= htmlspecialchars($prod['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="stockInfo" class="form-text text-info fw-bold" style="display: none;">
                    Estoque atual: <span id="currentStock">0</span> <span id="currentUnit"></span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Movimentação *</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="tipo_entrada" value="entrada" required>
                        <label class="form-check-label text-success fw-bold" for="tipo_entrada">
                            <i class="bi bi-arrow-down-circle"></i> Entrada
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="tipo_saida" value="saida" required>
                        <label class="form-check-label text-danger fw-bold" for="tipo_saida">
                            <i class="bi bi-arrow-up-circle"></i> Saída
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade *</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="quantidade" name="quantidade" required>
            </div>

            <div class="mb-3">
                <label for="observacao" class="form-label">Observação</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Registrar Movimentação
            </button>
        </form>
    </div>
</div>

<script>
function updateStockInfo() {
    var select = document.getElementById('produto_id');
    var selectedOption = select.options[select.selectedIndex];
    var stockInfo = document.getElementById('stockInfo');

    if (selectedOption.value) {
        var stock = selectedOption.getAttribute('data-stock');
        var unit = selectedOption.getAttribute('data-unit');
        document.getElementById('currentStock').innerText = parseFloat(stock).toLocaleString('pt-BR');
        document.getElementById('currentUnit').innerText = unit;
        stockInfo.style.display = 'block';
    } else {
        stockInfo.style.display = 'none';
    }
}
</script>
