<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="bi bi-person"></i></div>
            <div>
                <div class="stat-label">Client</div>
                <div class="stat-value"><?= e($commande['client_nom']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Total commande</div>
                <div class="stat-value"><?= money($commande['total_commande']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stat-card green">
            <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="stat-label">Reste a payer</div>
                <div class="stat-value"><?= money($resteAPayer) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-receipt me-2"></i>Commande #<?= e($commande['id']) ?> - <?= statusBadge($commande['statut']) ?></h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php?page=paiements&action=create&commande_id=<?= e($commande['id']) ?>" class="btn-primary-custom"><i class="bi bi-cash-coin"></i> Enregistrer paiement</a>
            <a href="index.php?page=commandes" class="btn-secondary-custom"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </div>
    <div class="card-body-custom p-0">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Plat</th>
                        <th>Quantite</th>
                        <th>Prix unitaire</th>
                        <th>Total ligne</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $detail): ?>
                        <tr>
                            <td><?= e($detail['plat_nom']) ?></td>
                            <td><?= e($detail['quantite']) ?></td>
                            <td><?= money($detail['prix_unitaire']) ?></td>
                            <td><?= money($detail['quantite'] * $detail['prix_unitaire']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
