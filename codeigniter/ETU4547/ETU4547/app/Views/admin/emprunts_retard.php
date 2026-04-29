<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="emprunts-retard-container">
    <h1>⚠️ Emprunts en Retard</h1>

    <?php if (empty($empruntEnRetard)) : ?>
        <div class="alert alert-success">
            ✅ Aucun emprunt en retard!
        </div>
    <?php else : ?>
        <div class="total-retard">
            <strong><?= count($empruntEnRetard) ?> emprunt(s) en retard</strong>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Emprunteur</th>
                    <th>Livre</th>
                    <th>Auteur</th>
                    <th>Date de Retour Prévue</th>
                    <th>Jours de Retard</th>
                    <th>Date d'Emprunt</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empruntEnRetard as $e) : ?>
                    <tr class="retard-row">
                        <td><strong><?= esc($e['nom_emprunteur']) ?></strong></td>
                        <td><?= esc($e['titre']) ?></td>
                        <td><?= esc($e['auteur']) ?></td>
                        <td><?= date('d/m/Y', strtotime($e['date_retour_prevue'])) ?></td>
                        <td>
                            <span class="badge-danger">
                                <?= $e['jours_retard'] ?? 0 ?> jours
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($e['date_emprunt'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">← Retour</a>
</div>

<style>
    .emprunts-retard-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .emprunts-retard-container h1 {
        font-size: 2em;
        margin-bottom: 20px;
        color: #dc3545;
    }

    .total-retard {
        background-color: #fff5f5;
        border-left: 4px solid #dc3545;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-size: 1.1em;
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

    .retard-row {
        background-color: #fff9f9;
    }

    .retard-row:hover {
        background-color: #fff0f0;
    }

    .badge-danger {
        display: inline-block;
        background-color: #dc3545;
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
