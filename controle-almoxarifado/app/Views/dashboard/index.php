<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('movements/create') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-arrow-left-right"></i> Nova Movimentação
        </a>
    </div>
</div>

<!-- Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3 h-100">
            <div class="card-header">Produtos Cadastrados</div>
            <div class="card-body">
                <h2 class="card-title"><?= $stats['total_products'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3 h-100">
            <div class="card-header">Estoque Total (Qtd)</div>
            <div class="card-body">
                <h2 class="card-title"><?= number_format($stats['total_stock'], 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3 h-100">
            <div class="card-header">Estoque Baixo</div>
            <div class="card-body">
                <h2 class="card-title"><?= $stats['low_stock_count'] ?></h2>
                <small>Produtos abaixo do mínimo</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light mb-3 h-100 border-secondary">
            <div class="card-header">Movimentações (Mês)</div>
            <div class="card-body">
                <p class="mb-1 text-success"><i class="bi bi-arrow-down"></i> Entrada: <?= number_format($monthSummary['total_entrada'] ?? 0, 2, ',', '.') ?></p>
                <p class="mb-0 text-danger"><i class="bi bi-arrow-up"></i> Saída: <?= number_format($monthSummary['total_saida'] ?? 0, 2, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Low Stock -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">Movimentações (Últimos 30 dias)</div>
            <div class="card-body">
                <canvas id="movementsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-warning">
            <div class="card-header bg-warning text-dark">Alerta de Estoque Baixo</div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($lowStockProducts)): ?>
                    <?php foreach ($lowStockProducts as $prod): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <strong><?= htmlspecialchars($prod['codigo']) ?></strong><br>
                                <small><?= htmlspecialchars($prod['nome']) ?></small>
                            </span>
                            <span class="badge bg-danger rounded-pill">
                                <?= number_format($prod['quantidade'], 2, ',', '.') ?> / <?= number_format($prod['estoque_minimo'], 2, ',', '.') ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-center text-muted">Estoque regular.</li>
                <?php endif; ?>
            </ul>
            <?php if (count($lowStockProducts) >= 5): ?>
                <div class="card-footer text-center">
                    <a href="<?= base_url('products/index') ?>" class="text-muted text-decoration-none small">Ver todos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Last Movements -->
<div class="card">
    <div class="card-header">Últimas Movimentações</div>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Tipo</th>
                    <th>Qtd</th>
                    <th>Resp.</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lastMovements)): ?>
                    <?php foreach ($lastMovements as $mov): ?>
                        <tr>
                            <td><?= date('d/m H:i', strtotime($mov['data_movimentacao'])) ?></td>
                            <td><?= htmlspecialchars($mov['produto_nome']) ?></td>
                            <td>
                                <?php if ($mov['tipo'] == 'entrada'): ?>
                                    <span class="badge bg-success">Entrada</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Saída</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($mov['quantidade'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($mov['usuario_nome'] ?? 'Sistema') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma movimentação.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const ctx = document.getElementById('movementsChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $chartLabels ?>, // PHP generated JSON
            datasets: [
                {
                    label: 'Entradas',
                    data: <?= $chartInputs ?>,
                    backgroundColor: 'rgba(25, 135, 84, 0.5)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Saídas',
                    data: <?= $chartOutputs ?>,
                    backgroundColor: 'rgba(220, 53, 69, 0.5)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
