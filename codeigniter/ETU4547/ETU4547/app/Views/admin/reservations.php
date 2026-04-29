<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="reservations-container">
    <h1>📚 Réservations en Attente</h1>

    <?php if (empty($reservations)) : ?>
        <div class="alert alert-success">
            ✅ Aucune réservation en attente!
        </div>
    <?php else : ?>
        <div class="reservations-info">
            <strong><?= count($reservations) ?> réservation(s) au total</strong>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Livre</th>
                    <th>Auteur</th>
                    <th>Emprunteur</th>
                    <th>Email</th>
                    <th>Position File</th>
                    <th>Date Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r) : ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><strong><?= esc($r['titre']) ?></strong></td>
                        <td><?= esc($r['auteur']) ?></td>
                        <td><?= esc($r['nom']) ?></td>
                        <td><?= esc($r['email']) ?></td>
                        <td>
                            <span class="badge-info"><?= $r['position_file'] ?></span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">← Retour</a>
</div>

<style>
    .reservations-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .reservations-container h1 {
        font-size: 2em;
        margin-bottom: 20px;
        color: #333;
    }

    .reservations-info {
        background-color: #d1ecf1;
        border-left: 4px solid #17a2b8;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-size: 1.1em;
        color: #0c5460;
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

    .badge-info {
        display: inline-block;
        background-color: #17a2b8;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: bold;
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

    .alert {
        padding: 15px;
        border-radius: 4px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<?= $this->endSection() ?>
