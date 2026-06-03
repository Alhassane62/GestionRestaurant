<form method="post" action="index.php?page=commandes&action=create">
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="content-card">
                <div class="card-head">
                    <h5><i class="bi bi-person me-2"></i>Client et date</h5>
                </div>
                <div class="card-body-custom">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                            <label class="form-label mb-0">Client <span class="text-danger">*</span></label>
                            <button type="button" class="btn-secondary-custom py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalClient">
                                <i class="bi bi-person-plus"></i> Nouveau client
                            </button>
                        </div>
                        <select name="client_id" class="form-select" required>
                            <option value="">-- Choisir un client --</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= e($client['id']) ?>" <?= old('client_id') == $client['id'] ? 'selected' : '' ?>><?= e($client['nom']) ?> - <?= e($client['telephone']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Date commande <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="date_commande" class="form-control" value="<?= e(old('date_commande', date('Y-m-d\TH:i'))) ?>" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
                        <a href="index.php?page=commandes" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="content-card">
                <div class="card-head">
                    <h5><i class="bi bi-egg-fried me-2"></i>Plats commandes</h5>
                    <a href="index.php?page=plats&action=create" class="btn-secondary-custom"><i class="bi bi-plus-lg"></i> Ajouter un plat</a>
                </div>
                <div class="card-body-custom p-0">
                    <?php if (empty($plats)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Aucun plat disponible.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Choix</th>
                                        <th>Plat</th>
                                        <th>Categorie</th>
                                        <th>Prix</th>
                                        <th style="width: 130px;">Quantite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plats as $plat): ?>
                                        <?php
                                        $checked = in_array($plat['id'], $_POST['plats'] ?? []) ? 'checked' : '';
                                        $quantite = $_POST['quantites'][$plat['id']] ?? 0;
                                        ?>
                                        <tr>
                                            <td><input class="form-check-input" type="checkbox" name="plats[]" value="<?= e($plat['id']) ?>" <?= $checked ?>></td>
                                            <td><?= e($plat['nom']) ?></td>
                                            <td><span class="badge-filiere"><?= e($plat['categorie_nom']) ?></span></td>
                                            <td><?= money($plat['prix']) ?></td>
                                            <td><input type="number" min="0" name="quantites[<?= e($plat['id']) ?>]" class="form-control" value="<?= e($quantite) ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="modalClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="index.php?page=clients&action=quick_create">
                <input type="hidden" name="return_url" value="index.php?page=commandes&action=create">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Telephone <span class="text-danger">*</span></label>
                        <input type="text" name="telephone" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Annuler</button>
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
