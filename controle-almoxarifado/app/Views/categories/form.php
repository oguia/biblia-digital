<?php
$isEdit = isset($category);
$action = $isEdit ? base_url('categories/update/' . $category['id']) : base_url('categories/store');
$title = $isEdit ? 'Editar Categoria' : 'Nova Categoria';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $title ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('categories/index') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= $action ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Categoria</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= $isEdit ? htmlspecialchars($category['nome']) : '' ?>" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Salvar
            </button>
        </form>
    </div>
</div>
