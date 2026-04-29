<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Nouveau livre</h1>
    <a href="<?= site_url('livres') ?>" class="btn btn-outline-secondary">Retour au catalogue</a>
</div>

<?php $errors = session('errors') ?? []; ?>

<form method="post" action="<?= site_url('livres') ?>" enctype="multipart/form-data" class="card card-body shadow-sm">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label" for="titre">Titre</label>
        <input type="text" id="titre" name="titre" value="<?= esc(old('titre')) ?>" class="form-control <?= isset($errors['titre']) ? 'is-invalid' : '' ?>" required>
        <?php if (isset($errors['titre'])): ?>
            <div class="invalid-feedback"><?= esc($errors['titre']) ?></div>
        <?php endif; ?>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" value="<?= esc(old('isbn')) ?>" class="form-control <?= isset($errors['isbn']) ? 'is-invalid' : '' ?>" required>
            <?php if (isset($errors['isbn'])): ?>
                <div class="invalid-feedback"><?= esc($errors['isbn']) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="date_publication">Date de publication</label>
            <input type="date" id="date_publication" name="date_publication" value="<?= esc(old('date_publication')) ?>" class="form-control <?= isset($errors['date_publication']) ? 'is-invalid' : '' ?>" required>
            <?php if (isset($errors['date_publication'])): ?>
                <div class="invalid-feedback"><?= esc($errors['date_publication']) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="form-label" for="id_auteur">Auteur</label>
            <select id="id_auteur" name="id_auteur" class="form-select" required>
                <option value="">Selectionner</option>
                <?php foreach ($auteurs as $auteur): ?>
                    <option value="<?= $auteur['id'] ?>" <?= old('id_auteur') == $auteur['id'] ? 'selected' : '' ?>><?= esc($auteur['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="id_categorie">Categorie</label>
            <select id="id_categorie" name="id_categorie" class="form-select" required>
                <option value="">Selectionner</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie['id'] ?>" <?= old('id_categorie') == $categorie['id'] ? 'selected' : '' ?>><?= esc($categorie['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="mb-3 mt-3">
        <label class="form-label" for="resume">Resume</label>
        <textarea id="resume" name="resume" rows="5" class="form-control"><?= esc(old('resume')) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label" for="couverture">Couverture (image max 2 Mo)</label>
        <input type="file" id="couverture" name="couverture" class="form-control <?= isset($errors['couverture']) ? 'is-invalid' : '' ?>" accept="image/*">
        <?php if (isset($errors['couverture'])): ?>
            <div class="invalid-feedback"><?= esc($errors['couverture']) ?></div>
        <?php endif; ?>
    </div>

    <div class="d-grid d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>
<?= $this->endSection() ?>
