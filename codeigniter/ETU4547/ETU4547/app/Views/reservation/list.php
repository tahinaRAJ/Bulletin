<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="reservations-list-container">
    <h1>📚 Mes Réservations</h1>

    <?php if (empty($reservations)) : ?>
        <div class="alert alert-info">
            Vous n'avez pas de réservations en cours.
            <a href="<?= base_url('/livres') ?>">Consulter le catalogue</a>
        </div>
    <?php else : ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Livre</th>
                    <th>Auteur</th>
                    <th>Position en File</th>
                    <th>Date de Réservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r) : ?>
                    <tr>
                        <td><strong><?= esc($r['titre']) ?></strong></td>
                        <td><?= esc($r['auteur']) ?></td>
                        <td>
                            <span class="badge"><?= $r['position_file'] ?></span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                        <td>
                            <form method="post" action="<?= base_url('reservations/' . $r['id'] . '/annuler') ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                    ✕ Annuler
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">← Retour au profil</a>
</div>

<style>
    .reservations-list-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .reservations-list-container h1 {
        font-size: 2em;
        margin-bottom: 20px;
        color: #333;
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

    .alert a {
        color: #0c5460;
        font-weight: bold;
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
        padding: 15px;
        text-align: left;
        font-weight: bold;
        color: #555;
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .badge {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: bold;
    }

    .btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        border: none;
        cursor: pointer;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 0.9em;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-secondary {
        background-color: #6c757d;
        margin-top: 20px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

<?= $this->endSection() ?>
