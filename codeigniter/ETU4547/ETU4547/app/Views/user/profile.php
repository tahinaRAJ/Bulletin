<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="profile-container">
    <div class="profile-header">
        <h1>Mon Profil</h1>
        <a href="<?= base_url('/auth/logout') ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr?')">
            Déconnexion
        </a>
    </div>

    <div class="profile-info">
        <div class="info-card">
            <h2>Informations personnelles</h2>
            <table>
                <tr>
                    <th>Nom</th>
                    <td><?= esc($user['nom']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= esc($user['email']) ?></td>
                </tr>
                <tr>
                    <th>Rôle</th>
                    <td>
                        <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'bibliothecaire' ? 'warning' : 'info') ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="emprunts-section">
        <h2>Historique de mes emprunts</h2>
        
        <?php if (empty($emprunts)) : ?>
            <div class="alert alert-info">
                Vous n'avez aucun historique d'emprunts.
            </div>
        <?php else : ?>
            <table class="emprunts-table">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt) : ?>
                        <tr>
                            <td><?= esc($emprunt['livre']['titre'] ?? 'N/A') ?></td>
                            <td><?= esc($emprunt['livre']['auteur'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                            <td>
                                <?php if ($emprunt['date_retour']) : ?>
                                    <?= date('d/m/Y', strtotime($emprunt['date_retour'])) ?>
                                <?php else : ?>
                                    <span class="badge badge-warning">En cours</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $statut = $emprunt['date_retour'] ? 'Retourné' : 'En cours';
                                    $badgeClass = $emprunt['date_retour'] ? 'success' : 'warning';
                                ?>
                                <span class="badge badge-<?= $badgeClass ?>"><?= $statut ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
    .profile-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 20px;
    }

    .profile-header h1 {
        margin: 0;
    }

    .profile-info {
        margin-bottom: 40px;
    }

    .info-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .info-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-card th {
        text-align: left;
        font-weight: bold;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        width: 150px;
        color: #555;
    }

    .info-card td {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        color: white;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #333;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .badge-success {
        background-color: #28a745;
    }

    .emprunts-section h2 {
        margin-top: 30px;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 10px;
    }

    .emprunts-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .emprunts-table thead {
        background-color: #f8f9fa;
    }

    .emprunts-table th {
        padding: 15px;
        text-align: left;
        font-weight: bold;
        color: #555;
        border-bottom: 2px solid #ddd;
    }

    .emprunts-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    .emprunts-table tbody tr:hover {
        background-color: #f8f9fa;
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

    .btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }
</style>

<?= $this->endSection() ?>
