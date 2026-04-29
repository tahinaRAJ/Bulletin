<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= esc($livre['titre']) ?></h1>
    <a href="<?= site_url('livres') ?>" class="btn btn-outline-secondary">Retour</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-1"><strong>Auteur:</strong> <?= esc($livre['auteur_nom']) ?></p>
                <p class="mb-1"><strong>Categorie:</strong> <?= esc($livre['categorie_nom']) ?></p>
                <p class="mb-1"><strong>ISBN:</strong> <?= esc($livre['isbn']) ?></p>
                <p class="mb-1"><strong>Date de publication:</strong> <?= esc($livre['date_publication']) ?></p>
                <p class="mb-3"><strong>Etat:</strong>
                    <span class="badge <?= $etat === 'DISPONIBLE' ? 'text-bg-success' : 'text-bg-danger' ?>">
                        <?= $etat === 'DISPONIBLE' ? 'Disponible' : 'Prete' ?>
                    </span>
                </p>
                <h2 class="h5">Resume</h2>
                <p class="mb-0 text-muted"><?= nl2br(esc($livre['resume'] ?: 'Aucun resume fourni.')) ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6">Dernier emprunteur</h2>
                <p class="mb-0"><?= esc($dernierEmprunteur ?? 'Aucun') ?></p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($etat === 'DISPONIBLE'): ?>
                    <form method="post" action="<?= site_url('mouvements/' . $livre['id'] . '/emprunter') ?>" class="d-grid gap-2">
                        <?= csrf_field() ?>
                        <input type="text" name="nom_emprunteur" class="form-control" placeholder="Nom de l'emprunteur" required minlength="2" maxlength="100">
                        <button type="submit" class="btn btn-warning">Preter le livre</button>
                    </form>
                <?php else: ?>
                    <form method="post" action="<?= site_url('mouvements/' . $livre['id'] . '/retour') ?>" class="d-grid">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success">Enregistrer le retour</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h5">Historique des mouvements</h2>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Emprunteur</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($historique === []): ?>
                    <tr>
                        <td colspan="3" class="text-muted">Aucun mouvement pour le moment.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($historique as $mouvement): ?>
                    <tr>
                        <td><?= esc($mouvement['date_mouvement']) ?></td>
                        <td>
                            <span class="badge <?= $mouvement['type_mouvement'] === 'EMPRUNT' ? 'text-bg-warning' : 'text-bg-success' ?>">
                                <?= esc($mouvement['type_mouvement']) ?>
                            </span>
                        </td>
                        <td><?= esc($mouvement['nom_emprunteur'] ?: '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
