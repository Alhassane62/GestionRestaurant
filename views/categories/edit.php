<div class="row">
    <div class="col-lg-7">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-pencil-square me-2"></i>Modifier la categorie</h5>
            </div>
            <div class="card-body-custom">
                <div class="form-section-title">Informations</div>
                <form method="post" action="index.php?page=categories&action=edit&id=<?= e($categorie['id']) ?>">
                    <div class="mb-4">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= e(old('nom', $categorie['nom'])) ?>" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Mettre a jour</button>
                        <a href="index.php?page=categories" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
