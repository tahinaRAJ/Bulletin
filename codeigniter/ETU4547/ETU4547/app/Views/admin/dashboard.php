<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="admin-dashboard">
    <h1>📊 Tableau de Bord Admin</h1>

    <!-- Statistiques Principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Emprunts</h3>
            <p class="stat-value"><?= $stats['total_emprunts'] ?></p>
        </div>
        <div class="stat-card">
            <h3>En Cours</h3>
            <p class="stat-value" style="color: #007bff;"><?= $stats['emprunts_actifs'] ?></p>
        </div>
        <div class="stat-card">
            <h3>En Retard</h3>
            <p class="stat-value" style="color: #dc3545;"><?= $stats['emprunts_retardes'] ?></p>
        </div>
        <div class="stat-card">
            <h3>Retournés</h3>
            <p class="stat-value" style="color: #28a745;"><?= $stats['emprunts_retournes'] ?></p>
        </div>
        <div class="stat-card">
            <h3>Emprunts ce mois</h3>
            <p class="stat-value" style="color: #6f42c1;"><?= $empruntsMois ?? 0 ?></p>
        </div>
        <div class="stat-card">
            <h3>Taux de retard</h3>
            <p class="stat-value" style="color: #fd7e14;"><?= number_format((float) ($tauxRetard ?? 0), 1) ?>%</p>
        </div>
        <div class="stat-card">
            <h3>Reservations</h3>
            <p class="stat-value" style="color: #17a2b8;"><?= $reservationsEnAttente ?? 0 ?></p>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="actions-section">
        <h2>Actions</h2>
        <a href="<?= base_url('admin/emprunts-retard') ?>" class="btn btn-warning">📋 Liste des Retards</a>
        <a href="<?= base_url('admin/relances') ?>" class="btn btn-danger">📧 Envoyer Relances</a>
        <a href="<?= base_url('admin/reservations') ?>" class="btn btn-info">📚 Réservations en Attente</a>
    </div>

    <!-- Livres les Plus Empruntés -->
    <div class="section">
        <h2>📈 Top 10 Livres les Plus Empruntés</h2>
        <?php if (!empty($livresPlusEmpruntes)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Emprunts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livresPlusEmpruntes as $livre) : ?>
                        <tr>
                            <td><?= esc($livre['titre']) ?></td>
                            <td><?= esc($livre['auteur']) ?></td>
                            <td><strong><?= $livre['nb_emprunts'] ?? 0 ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>📊 Graphiques</h2>
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Top livres empruntes</h3>
                <canvas id="chartLivres"></canvas>
            </div>
            <div class="chart-card">
                <h3>Top emprunteurs</h3>
                <canvas id="chartEmprunteurs"></canvas>
            </div>
        </div>
    </div>

    <!-- Emprunteurs les Plus Actifs -->
    <div class="section">
        <h2>👥 Top 10 Emprunteurs les Plus Actifs</h2>
        <?php if (!empty($emprunteurs)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Emprunteur</th>
                        <th>Nombre d'Emprunts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunteurs as $e) : ?>
                        <tr>
                            <td><?= esc($e['nom_emprunteur']) ?></td>
                            <td><strong><?= $e['nb_emprunts'] ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Emprunts en Retard -->
    <div class="section">
        <h2>⚠️ Emprunts en Retard (<?= count($empruntEnRetard) ?>)</h2>
        <?php if (!empty($empruntEnRetard)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Emprunteur</th>
                        <th>Livre</th>
                        <th>Retour Prévu</th>
                        <th>Jours de Retard</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empruntEnRetard as $e) : ?>
                        <tr style="background-color: #fff5f5;">
                            <td><?= esc($e['nom_emprunteur']) ?></td>
                            <td><?= esc($e['titre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($e['date_retour_prevue'])) ?></td>
                            <td><strong style="color: #dc3545;"><?= $e['jours_retard'] ?? 0 ?> jours</strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-success">✅ Aucun emprunt en retard!</div>
        <?php endif; ?>
    </div>
</div>

<style>
    .admin-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .admin-dashboard h1 {
        font-size: 2.5em;
        margin-bottom: 30px;
        color: #333;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card h3 {
        margin: 0 0 10px;
        color: #666;
        font-size: 0.9em;
    }

    .stat-value {
        font-size: 2.5em;
        font-weight: bold;
        margin: 0;
        color: #000;
    }

    .actions-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 40px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .actions-section h2 {
        margin-top: 0;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin-right: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-weight: bold;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #000;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-info {
        background-color: #17a2b8;
    }

    .section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .section h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
    }

    .chart-card {
        background: #fafafa;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #eee;
    }

    .chart-card h3 {
        margin-top: 0;
        font-size: 1rem;
        color: #555;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .table th {
        padding: 12px;
        text-align: left;
        font-weight: bold;
        color: #555;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    .table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .alert {
        padding: 15px;
        border-radius: 4px;
        margin: 10px 0;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const livresLabels = <?= json_encode($chartLivresLabels ?? []) ?>;
    const livresValues = <?= json_encode($chartLivresValues ?? []) ?>;
    const emprunteursLabels = <?= json_encode($chartEmprunteursLabels ?? []) ?>;
    const emprunteursValues = <?= json_encode($chartEmprunteursValues ?? []) ?>;

    if (livresLabels.length && document.getElementById('chartLivres')) {
        new Chart(document.getElementById('chartLivres'), {
            type: 'bar',
            data: {
                labels: livresLabels,
                datasets: [{
                    label: 'Emprunts',
                    data: livresValues,
                    backgroundColor: '#5e8b7e'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    if (emprunteursLabels.length && document.getElementById('chartEmprunteurs')) {
        new Chart(document.getElementById('chartEmprunteurs'), {
            type: 'bar',
            data: {
                labels: emprunteursLabels,
                datasets: [{
                    label: 'Emprunts',
                    data: emprunteursValues,
                    backgroundColor: '#7c94c7'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
</script>

<?= $this->endSection() ?>
