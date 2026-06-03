<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card green">
            <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Recettes totales</div>
                <div class="stat-value"><?= money($recettes['total'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="bi bi-calendar-day"></i></div>
            <div>
                <div class="stat-label">Recettes du jour</div>
                <div class="stat-value"><?= money($recettes['aujourd_hui'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-6">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-trophy me-2"></i>Plats les plus commandes</h5>
            </div>
            <div class="card-body-custom p-0">
                <?php if (empty($platsPlusCommandes)): ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p>Aucune donnee.</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Plat</th>
                                    <th>Categorie</th>
                                    <th>Quantite</th>
                                    <th>Ventes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($platsPlusCommandes as $plat): ?>
                                    <tr>
                                        <td><?= e($plat['nom']) ?></td>
                                        <td><span class="badge-filiere"><?= e($plat['categorie_nom']) ?></span></td>
                                        <td><?= e($plat['quantite_totale']) ?></td>
                                        <td><?= money($plat['total_vente']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-calendar-day me-2"></i>Commandes du jour</h5>
            </div>
            <div class="card-body-custom p-0">
                <?php if (empty($commandesDuJour)): ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p>Aucune commande aujourd hui.</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($commandesDuJour as $commande): ?>
                                    <tr>
                                        <td><a href="index.php?page=commandes&action=show&id=<?= e($commande['id']) ?>">#<?= e($commande['id']) ?></a></td>
                                        <td><?= e($commande['client_nom']) ?></td>
                                        <td><?= money($commande['total_commande']) ?></td>
                                        <td><?= statusBadge($commande['statut']) ?></td>
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
