<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="emprunt-historique-container">
    <h1>📖 Historique de Mes Emprunts</h1>

    <!-- Emprunts Actifs -->
    <div class="section">
        <h2>En Cours (<?= count($empruntActifs) ?>)</h2>
        <?php if (empty($empruntActifs)) : ?>
            <div class="alert alert-info">
                Vous n'avez pas d'emprunt en cours.
            </div>
        <?php else : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Date d'Emprunt</th>
                        <th>Retour Prévu</th>
                        <th>Jours Restants</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empruntActifs as $e) : ?>
                        <tr class="actif-row">
                            <td><strong><?= esc($e['titre']) ?></strong></td>
                            <td><?= esc($e['auteur']) ?></td>
                            <td><?= date('d/m/Y', strtotime($e['date_emprunt'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($e['date_retour_prevue'])) ?></td>
                            <td>
                                <?php 
                                    $now = new DateTime();
                                    $retourPrevue = new DateTime($e['date_retour_prevue']);
                                    $diff = $retourPrevue->diff($now);
                                    $jours = $diff->days;
                                    
                                    if ($retourPrevue < $now) {
                                        echo '<span class="badge-danger">EN RETARD: ' . $jours . ' j</span>';
                                    } else {
                                        echo '<span class="badge-success">' . $jours . ' j</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Emprunts Passés -->
    <div class="section">
        <h2>Historique (<?= count($empruntPasses) ?>)</h2>
        <?php if (empty($empruntPasses)) : ?>
            <div class="alert alert-info">
                Pas d'historique d'emprunts.
            </div>
        <?php else : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Date d'Emprunt</th>
                        <th>Date de Retour</th>
                        <th>Statut</th>
                        <th>Retard</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empruntPasses as $e) : ?>
                        <tr>
                            <td><?= esc($e['titre']) ?></td>
                            <td><?= esc($e['auteur']) ?></td>
                            <td><?= date('d/m/Y', strtotime($e['date_emprunt'])) ?></td>
                            <td><?= $e['date_retour'] ? date('d/m/Y', strtotime($e['date_retour'])) : '-' ?></td>
                            <td>
                                <?php if ($e['statut'] === 'retourne') : ?>
                                    <span class="badge-success">✓ Retourné</span>
                                <?php elseif ($e['statut'] === 'retard') : ?>
                                    <span class="badge-danger">⚠ En Retard</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $e['jours_retard'] ? $e['jours_retard'] . ' j' : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">← Retour au profil</a>
</div>

<style>
    .emprunt-historique-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .emprunt-historique-container h1 {
        font-size: 2em;
        margin-bottom: 30px;
        color: #333;
    }

    .section {
        margin-bottom: 40px;
    }

    .section h2 {
        font-size: 1.3em;
        margin-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
        color: #555;
    }

    .alert {
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .table th {
        padding: 12px;
        text-align: left;
        font-weight: bold;
        color: #555;
        font-size: 0.9em;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    .table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .actif-row {
        background-color: #f0f8ff;
    }

    .badge-success {
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 0.85em;
    }

    .badge-danger {
        display: inline-block;
        background-color: #dc3545;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 0.85em;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-weight: bold;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

<?= $this->endSection() ?>
