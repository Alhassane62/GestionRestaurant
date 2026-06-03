<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-tags me-2"></i>Liste des categories</h5>
        <a href="index.php?page=categories&action=create" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Ajouter</a>
    </div>
    <div class="card-body-custom p-0">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Aucune categorie enregistree.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $categorie): ?>
                            <tr>
                                <td><?= e($categorie['id']) ?></td>
                                <td><?= e($categorie['nom']) ?></td>
                                <td class="text-end">
                                    <a class="btn-icon edit" title="Modifier" href="index.php?page=categories&action=edit&id=<?= e($categorie['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                                    <a class="btn-icon delete" title="Supprimer" onclick="return confirm('Supprimer cette categorie ?')" href="index.php?page=categories&action=delete&id=<?= e($categorie['id']) ?>"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
