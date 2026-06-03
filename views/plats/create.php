<div class="row">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-plus-circle me-2"></i>Ajouter un plat</h5>
            </div>
            <div class="card-body-custom">
                <div class="form-section-title">Informations du plat</div>
                <form method="post" action="index.php?page=plats&action=create">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="<?= e(old('nom')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prix <span class="text-danger">*</span></label>
                            <input type="number" name="prix" step="0.01" min="0" class="form-control" value="<?= e(old('prix')) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                            <label class="form-label mb-0">Categorie <span class="text-danger">*</span></label>
                            <button type="button" class="btn-secondary-custom py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalCategorie">
                                <i class="bi bi-plus-lg"></i> Nouvelle categorie
                            </button>
                        </div>
                        <select name="categorie_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= e($categorie['id']) ?>" <?= old('categorie_id') == $categorie['id'] ? 'selected' : '' ?>><?= e($categorie['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= e(old('description')) ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
                        <a href="index.php?page=plats" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCategorie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="index.php?page=categories&action=quick_create">
                <input type="hidden" name="return_url" value="index.php?page=plats&action=create">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle categorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Annuler</button>
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
