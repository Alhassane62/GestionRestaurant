<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="bi bi-egg-fried"></i></div>
            <div>
                <div class="stat-label">Total plats</div>
                <div class="stat-value"><?= e($stats['total_plats'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-label">Total clients</div>
                <div class="stat-value"><?= e($stats['total_clients'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card green">
            <div class="stat-icon green"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="stat-label">Total commandes</div>
                <div class="stat-value"><?= e($stats['total_commandes'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card red">
            <div class="stat-icon red"><i class="bi bi-calendar-day"></i></div>
            <div>
                <div class="stat-label">Commandes du jour</div>
                <div class="stat-value"><?= e($stats['commandes_jour'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Recettes totales</div>
                <div class="stat-value"><?= money($stats['recettes_totales'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-label">Recettes du jour</div>
                <div class="stat-value"><?= money($stats['recettes_jour'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-speedometer2 me-2"></i>Activite du restaurant</h5>
        <a href="index.php?page=commandes&action=create" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Nouvelle commande</a>
    </div>
    <div class="card-body-custom">
        <div class="row g-3">
            <div class="col-md-4"><a class="btn-secondary-custom w-100 justify-content-center" href="index.php?page=plats"><i class="bi bi-egg-fried"></i> Gerer les plats</a></div>
            <div class="col-md-4"><a class="btn-secondary-custom w-100 justify-content-center" href="index.php?page=clients"><i class="bi bi-people"></i> Gerer les clients</a></div>
            <div class="col-md-4"><a class="btn-secondary-custom w-100 justify-content-center" href="index.php?page=statistiques"><i class="bi bi-bar-chart"></i> Voir les statistiques</a></div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-xl-6">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-trophy me-2"></i>Plats les plus commandes</h5>
            </div>
            <div class="card-body-custom">
                <?php if (empty($platsPlusCommandes)): ?>
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>Aucune commande enregistree.</p></div>
                <?php else: ?>
                    <div style="height: 280px;"><canvas id="chartPlats"></canvas></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-cash-stack me-2"></i>Recettes par jour</h5>
            </div>
            <div class="card-body-custom">
                <?php if (empty($recettesParJour)): ?>
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>Aucun paiement enregistre.</p></div>
                <?php else: ?>
                    <div style="height: 280px;"><canvas id="chartRecettes"></canvas></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-pie-chart me-2"></i>Commandes par statut</h5>
            </div>
            <div class="card-body-custom">
                <?php if (empty($commandesParStatut)): ?>
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>Aucune commande enregistree.</p></div>
                <?php else: ?>
                    <div style="height: 280px;"><canvas id="chartStatuts"></canvas></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const platsData = <?= json_encode($platsPlusCommandes, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) ?>;
    const recettesData = <?= json_encode($recettesParJour, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) ?>;
    const statutsData = <?= json_encode($commandesParStatut, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) ?>;

    if (document.getElementById('chartPlats') && platsData.length > 0) {
        new Chart(document.getElementById('chartPlats'), {
            type: 'bar',
            data: {
                labels: platsData.map(item => item.nom),
                datasets: [{
                    label: 'Quantite commandee',
                    data: platsData.map(item => Number(item.quantite_totale)),
                    backgroundColor: '#2450a0'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    if (document.getElementById('chartRecettes') && recettesData.length > 0) {
        new Chart(document.getElementById('chartRecettes'), {
            type: 'line',
            data: {
                labels: recettesData.map(item => item.jour),
                datasets: [{
                    label: 'Recettes',
                    data: recettesData.map(item => Number(item.total)),
                    borderColor: '#1a7a4a',
                    backgroundColor: 'rgba(26, 122, 74, 0.12)',
                    fill: true,
                    tension: 0.25
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    if (document.getElementById('chartStatuts') && statutsData.length > 0) {
        new Chart(document.getElementById('chartStatuts'), {
            type: 'doughnut',
            data: {
                labels: statutsData.map(item => item.statut),
                datasets: [{
                    data: statutsData.map(item => Number(item.total)),
                    backgroundColor: ['#2450a0', '#e8af30', '#1a7a4a', '#c0392b']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
});
</script>
