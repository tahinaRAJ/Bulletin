<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Catalogue des livres</h1>
    <a href="<?= site_url('livres/nouveau') ?>" class="btn btn-primary">Ajouter un livre</a>
</div>

<form method="get" action="<?= site_url('livres') ?>" class="row g-2 mb-3">
    <div class="col-md-5">
        <input
            type="text"
            name="q"
            value="<?= esc($recherche) ?>"
            class="form-control"
            placeholder="Rechercher par titre"
        >
    </div>
    <div class="col-md-4">
        <select name="categorie_id" class="form-select">
            <option value="">Toutes les categories</option>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie['id'] ?>" <?= (string) $categorieId === (string) $categorie['id'] ? 'selected' : '' ?>>
                    <?= esc($categorie['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3 d-grid">
        <button type="submit" class="btn btn-outline-secondary">Filtrer</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
        <tr>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Annee</th>
            <th>Categorie</th>
            <th>Etat</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($livres === []): ?>
            <tr>
                <td colspan="6" class="text-center text-muted">Aucun livre trouve.</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($livres as $livre): ?>
            <?php $estDisponible = $livre['dernier_mouvement'] !== 'EMPRUNT'; ?>
            <tr>
                <td><?= esc($livre['titre']) ?></td>
                <td><?= esc($livre['auteur_nom']) ?></td>
                <td><?= esc(date('Y', strtotime($livre['date_publication']))) ?></td>
                <td><?= esc($livre['categorie_nom']) ?></td>
                <td>
                    <span class="badge <?= $estDisponible ? 'text-bg-success' : 'text-bg-danger' ?>">
                        <?= $estDisponible ? 'Disponible' : 'Prete' ?>
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= site_url('livres/' . $livre['id']) ?>" class="btn btn-sm btn-outline-primary">Details</a>

                        <?php if ($estDisponible): ?>
                            <form method="post" action="<?= site_url('mouvements/' . $livre['id'] . '/emprunter') ?>" class="d-flex gap-1">
                                <?= csrf_field() ?>
                                <input type="text" name="nom_emprunteur" class="form-control form-control-sm" placeholder="Nom" required minlength="2" maxlength="100">
                                <button class="btn btn-sm btn-warning" type="submit">Preter</button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="<?= site_url('mouvements/' . $livre['id'] . '/retour') ?>">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-success" type="submit">Retour</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (isset($pager)): ?>
    <div class="mt-3">
        <?= $pager->links() ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
