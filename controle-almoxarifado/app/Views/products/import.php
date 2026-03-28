<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Importar Produtos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('products/index') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <p class="text-muted">Faça upload de um arquivo Excel (.xlsx) ou CSV (.csv) com as seguintes colunas na primeira linha:</p>
        <div class="alert alert-info">
            <code>codigo</code> | <code>nome</code> | <code>descricao</code> | <code>categoria</code> | <code>quantidade</code> | <code>estoque_minimo</code> | <code>unidade</code>
        </div>

        <form action="<?= base_url('products/processImport') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="file" class="form-label">Arquivo</label>
                <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .csv" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Importar
            </button>
        </form>
    </div>
</div>
