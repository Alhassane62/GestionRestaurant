<div class="content-card mb-3">
    <div class="card-body-custom">
        <form class="row g-2" method="get" action="index.php">
            <input type="hidden" name="page" value="clients">
            <div class="col-md-9">
                <input type="text" name="q" class="form-control" placeholder="Rechercher par nom ou telephone" value="<?= e($keyword) ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn-primary-custom flex-fill justify-content-center" type="submit"><i class="bi bi-search"></i> Rechercher</button>
                <a href="index.php?page=clients" class="btn-secondary-custom"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-people me-2"></i>Liste des clients</h5>
        <a href="index.php?page=clients&action=create" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Ajouter</a>
    </div>
    <div class="card-body-custom p-0">
        <?php if (empty($clients)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Aucun client trouve.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Telephone</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= e($client['id']) ?></td>
                                <td><?= e($client['nom']) ?></td>
                                <td><?= e($client['telephone']) ?></td>
                                <td class="text-end">
                                    <a class="btn-icon edit" title="Modifier" href="index.php?page=clients&action=edit&id=<?= e($client['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                                    <a class="btn-icon delete" title="Supprimer" onclick="return confirm('Supprimer ce client ?')" href="index.php?page=clients&action=delete&id=<?= e($client['id']) ?>"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
