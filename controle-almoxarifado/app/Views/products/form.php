<?php
$isEdit = isset($product) && isset($product['id']);
$action = $isEdit ? base_url('products/update/' . $product['id']) : base_url('products/store');
$title = $isEdit ? 'Editar Produto' : 'Novo Produto';

// Helper para selecionar categoria
function isSelected($val, $current) {
    return $val == $current ? 'selected' : '';
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $title ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('products/index') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= $action ?>" method="POST">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="codigo" class="form-label">Código *</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" value="<?= htmlspecialchars($product['codigo'] ?? '') ?>" required <?= $isEdit ? 'readonly' : '' ?>>
                    <div class="form-text">Único para cada produto. Será usado no código de barras.</div>
                </div>

                <div class="col-md-8 mb-3">
                    <label for="nome" class="form-label">Nome do Produto *</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($product['nome'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($product['descricao'] ?? '') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select class="form-select" id="categoria_id" name="categoria_id">
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= isSelected($cat['id'], $product['categoria_id'] ?? '') ?>>
                                <?= htmlspecialchars($cat['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="unidade" class="form-label">Unidade *</label>
                    <input type="text" class="form-control" id="unidade" name="unidade" list="unidades" value="<?= htmlspecialchars($product['unidade'] ?? 'un') ?>" required>
                    <datalist id="unidades">
                        <option value="un">
                        <option value="kg">
                        <option value="m">
                        <option value="L">
                        <option value="cx">
                        <option value="pct">
                    </datalist>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="estoque_minimo" class="form-label">Estoque Mínimo</label>
                    <input type="number" step="0.01" class="form-control" id="estoque_minimo" name="estoque_minimo" value="<?= htmlspecialchars($product['estoque_minimo'] ?? '0') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="quantidade" class="form-label">Quantidade Atual</label>
                    <input type="number" step="0.01" class="form-control" id="quantidade" name="quantidade" value="<?= htmlspecialchars($product['quantidade'] ?? '0') ?>" <?= $isEdit ? 'readonly' : '' ?>>
                    <?php if ($isEdit): ?>
                        <div class="form-text text-muted">Para alterar o estoque, use a função de Movimentações.</div>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Salvar
            </button>
        </form>
    </div>
</div>
