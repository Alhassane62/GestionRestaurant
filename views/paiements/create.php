<?php $selectedCommandeId = old('commande_id', $_GET['commande_id'] ?? ''); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-cash-coin me-2"></i>Enregistrer un paiement</h5>
            </div>
            <div class="card-body-custom">
                <div class="form-section-title">Informations du paiement</div>
                <form method="post" action="index.php?page=paiements&action=create">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                            <label class="form-label mb-0">Commande <span class="text-danger">*</span></label>
                            <a href="index.php?page=commandes&action=create" class="btn-secondary-custom py-1 px-2"><i class="bi bi-plus-lg"></i> Nouvelle commande</a>
                        </div>
                        <select name="commande_id" class="form-select" required>
                            <option value="">-- Choisir une commande --</option>
                            <?php foreach ($commandes as $commande): ?>
                                <?php
                                $total = (float) $commande['total_commande'];
                                $paye = (float) $commande['total_paye'];
                                $reste = max(0, $total - $paye);
                                ?>
                                <option value="<?= e($commande['id']) ?>" <?= $selectedCommandeId == $commande['id'] ? 'selected' : '' ?>>
                                    #<?= e($commande['id']) ?> - <?= e($commande['client_nom']) ?> - reste <?= money($reste) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Montant paye <span class="text-danger">*</span></label>
                            <input type="text" inputmode="decimal" name="montant_paye" class="form-control" value="<?= e(old('montant_paye')) ?>" placeholder="Ex : 5000 ou 5000,50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date paiement <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date_paiement" class="form-control" value="<?= e(old('date_paiement', date('Y-m-d\TH:i'))) ?>" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Valider</button>
                        <a href="index.php?page=paiements" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
