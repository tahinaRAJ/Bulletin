<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="emprunt-details-container">
    <a href="<?= base_url('/mes-emprunts') ?>" class="btn-back">← Retour à l'historique</a>

    <div class="details-card">
        <div class="book-section">
            <h1><?= esc($livre['titre'] ?? ($emprunt['titre'] ?? '')) ?></h1>
            <p class="author">Par <strong><?= esc($livre['auteur'] ?? ($emprunt['auteur'] ?? '')) ?></strong></p>
            <p class="isbn">ISBN: <?= esc($livre['isbn'] ?? ($emprunt['isbn'] ?? 'N/A')) ?></p>
        </div>

        <div class="loan-info">
            <h2>Informations d'Emprunt</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Date d'Emprunt:</label>
                    <span><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></span>
                </div>

                <div class="info-item">
                    <label>Date de Retour Prévue:</label>
                    <span><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></span>
                </div>

                <div class="info-item">
                    <label>Date de Retour Réelle:</label>
                    <span><?= $emprunt['date_retour'] ? date('d/m/Y', strtotime($emprunt['date_retour'])) : 'Non retourné' ?></span>
                </div>

                <div class="info-item">
                    <label>Statut:</label>
                    <span>
                        <?php if ($emprunt['statut'] === 'actif') : ?>
                            <span class="status-badge status-active">🔄 En cours</span>
                        <?php elseif ($emprunt['statut'] === 'retourne') : ?>
                            <span class="status-badge status-returned">✓ Retourné</span>
                        <?php elseif ($emprunt['statut'] === 'retard') : ?>
                            <span class="status-badge status-overdue">⚠ En Retard</span>
                        <?php endif; ?>
                    </span>
                </div>

                <?php if ($emprunt['jours_retard'] !== null && $emprunt['jours_retard'] > 0) : ?>
                    <div class="info-item alert-retard">
                        <label>Jours de Retard:</label>
                        <span class="retard-value"><?= $emprunt['jours_retard'] ?> jours</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($emprunt['statut'] === 'actif') : ?>
            <div class="actions">
                <p class="reminder">
                    Pensez à retourner ce livre avant le <strong><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></strong> 
                    pour éviter les retards.
                </p>
            </div>
        <?php elseif ($emprunt['statut'] === 'retard') : ?>
            <div class="actions alert-retard">
                <p>
                    ⚠️ <strong>Vous êtes en retard de <?= $emprunt['jours_retard'] ?> jour(s)</strong> pour ce livre. 
                    Veuillez le retourner dès que possible.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .emprunt-details-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .btn-back {
        display: inline-block;
        padding: 8px 16px;
        margin-bottom: 20px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }

    .details-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .book-section {
        margin-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 20px;
    }

    .book-section h1 {
        font-size: 1.8em;
        margin: 0 0 10px 0;
        color: #333;
    }

    .book-section .author {
        font-size: 1.1em;
        margin: 5px 0;
        color: #666;
    }

    .book-section .isbn {
        font-size: 0.9em;
        color: #999;
    }

    .loan-info h2 {
        font-size: 1.3em;
        margin-bottom: 20px;
        color: #555;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-item label {
        font-weight: bold;
        color: #666;
        font-size: 0.9em;
        margin-bottom: 5px;
    }

    .info-item span {
        color: #333;
        font-size: 1em;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 0.9em;
    }

    .status-active {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-returned {
        background-color: #d4edda;
        color: #155724;
    }

    .status-overdue {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-retard {
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
        padding: 15px;
        border-radius: 4px;
        margin-top: 20px;
    }

    .alert-retard .retard-value {
        color: #dc3545;
        font-weight: bold;
        font-size: 1.1em;
    }

    .actions {
        margin-top: 30px;
        padding: 15px;
        background-color: #e7f3ff;
        border-left: 4px solid #2196F3;
        border-radius: 4px;
    }

    .actions p {
        margin: 0;
        color: #1565c0;
    }

    .reminder {
        color: #333 !important;
    }

    @media (max-width: 600px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .details-card {
            padding: 20px;
        }
    }
</style>

<?= $this->endSection() ?>
