<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Histórico de Movimentações</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('movements/export') ?>" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-download"></i> Exportar
        </a>
        <a href="<?= base_url('movements/create') ?>" class="btn btn-sm btn-success">
            <i class="bi bi-arrow-left-right"></i> Nova Movimentação
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="<?= base_url('movements/index') ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Data Início</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Data Fim</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>Data</th>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Responsável</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($movements)): ?>
                <?php foreach ($movements as $mov): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($mov['data_movimentacao'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($mov['produto_codigo'] ?? '') ?></strong> -
                            <?= htmlspecialchars($mov['produto_nome'] ?? '') ?>
                        </td>
                        <td>
                            <?php if ($mov['tipo'] == 'entrada'): ?>
                                <span class="badge bg-success">Entrada</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Saída</span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($mov['quantidade'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($mov['usuario_nome'] ?? 'Sistema') ?></td>
                        <td><?= htmlspecialchars($mov['observacao'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Nenhuma movimentação registrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
