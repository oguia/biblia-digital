<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categorias</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('categories/create') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= htmlspecialchars($cat['nome']) ?></td>
                        <td class="text-end">
                            <a href="<?= base_url('categories/edit/' . $cat['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= base_url('categories/delete/' . $cat['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">Nenhuma categoria cadastrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
