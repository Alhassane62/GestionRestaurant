<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-cash-coin me-2"></i>Liste des paiements</h5>
        <a href="index.php?page=paiements&action=create" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Ajouter</a>
    </div>
    <div class="card-body-custom p-0">
        <?php if (empty($paiements)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Aucun paiement enregistre.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Commande</th>
                            <th>Client</th>
                            <th>Date paiement</th>
                            <th>Montant paye</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paiements as $paiement): ?>
                            <tr>
                                <td><?= e($paiement['id']) ?></td>
                                <td><a href="index.php?page=commandes&action=show&id=<?= e($paiement['commande_id']) ?>">#<?= e($paiement['commande_id']) ?></a></td>
                                <td><?= e($paiement['client_nom']) ?></td>
                                <td><?= e(date('d/m/Y H:i', strtotime($paiement['date_paiement']))) ?></td>
                                <td><?= money($paiement['montant_paye']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
