<div class="row">
    <div class="col-lg-7">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-pencil-square me-2"></i>Modifier le client</h5>
            </div>
            <div class="card-body-custom">
                <div class="form-section-title">Informations du client</div>
                <form method="post" action="index.php?page=clients&action=edit&id=<?= e($client['id']) ?>">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="<?= e(old('nom', $client['nom'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telephone <span class="text-danger">*</span></label>
                            <input type="text" name="telephone" class="form-control" value="<?= e(old('telephone', $client['telephone'])) ?>" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Mettre a jour</button>
                        <a href="index.php?page=clients" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
