<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-receipt me-2"></i><?= $todayOnly ? 'Commandes du jour' : 'Liste des commandes' ?></h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php?page=commandes&today=1" class="btn-secondary-custom"><i class="bi bi-calendar-day"></i> Aujourd hui</a>
            <a href="index.php?page=commandes&action=create" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Nouvelle commande</a>
        </div>
    </div>
    <div class="card-body-custom p-0">
        <?php if (empty($commandes)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Aucune commande enregistree.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Total paye</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $commande): ?>
                            <?php
                            $total = (float) $commande['total_commande'];
                            $paye = (float) $commande['total_paye'];
                            $reste = max(0, $total - $paye);
                            ?>
                            <tr>
                                <td><?= e($commande['id']) ?></td>
                                <td><?= e($commande['client_nom']) ?></td>
                                <td><?= e(date('d/m/Y H:i', strtotime($commande['date_commande']))) ?></td>
                                <td><?= money($total) ?></td>
                                <td><?= money($paye) ?></td>
                                <td><?= money($reste) ?></td>
                                <td><?= statusBadge($commande['statut']) ?></td>
                                <td class="text-end">
                                    <a class="btn-icon show" title="Voir" href="index.php?page=commandes&action=show&id=<?= e($commande['id']) ?>"><i class="bi bi-eye"></i></a>
                                    <a class="btn-icon delete" title="Supprimer" onclick="return confirm('Supprimer cette commande ?')" href="index.php?page=commandes&action=delete&id=<?= e($commande['id']) ?>"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
