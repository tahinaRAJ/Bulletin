<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Ajouter un livre<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .form-shell {
        display: grid;
        grid-template-columns: 0.95fr 1.05fr;
        gap: 1rem;
        align-items: start;
    }

    .form-intro {
        background: linear-gradient(145deg, var(--forest), #234834);
        color: var(--cream);
        border-radius: 1rem;
        padding: 1.2rem;
    }

    .form-kicker {
        margin: 0 0 0.8rem;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: rgba(247, 240, 226, 0.72);
    }

    .form-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 3.5vw, 3rem);
        line-height: 1.02;
        margin: 0 0 0.85rem;
        font-weight: 500;
    }

    .form-desc {
        margin: 0;
        color: rgba(247, 240, 226, 0.84);
        line-height: 1.8;
        max-width: 56ch;
    }

    .form-benefits {
        margin-top: 1.4rem;
        padding-top: 1.2rem;
        border-top: 1px solid rgba(247, 240, 226, 0.14);
        display: grid;
        gap: 0.65rem;
    }

    .field {
        margin-bottom: 12px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
    }

    input, select, textarea, button {
        width: 100%;
        box-sizing: border-box;
    }

    .form-card {
        padding: 1.2rem;
    }

    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.9rem;
    }

    .mini-note {
        font-size: 0.78rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--gold);
    }

    @media (max-width: 640px) {
        .form-shell,
        .row-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-shell">
    <div class="form-intro">
        <p class="form-kicker">Nouveau livre</p>
        <h1 class="form-title">Ajouter un ouvrage dans le catalogue.</h1>
        <p class="form-desc">Formulaire simple pour saisir un nouveau livre sans surcharge visuelle.</p>
        <div class="form-benefits">
            <div class="mini-note">Champs obligatoires validates</div>
            <div class="mini-note">Image limitee a 2 Mo</div>
            <div class="mini-note">Annee limitee cote client</div>
        </div>
    </div>

    <div class="form-card card">
        <?php $errors = session('errors') ?? []; ?>
        <a href="<?= base_url('/livres') ?>" class="btn btn-secondary" style="margin-bottom:1rem; display:inline-block;">Retour au catalogue</a>

        <h1 class="page-title" style="font-size: 2.2rem; margin-bottom: 1rem;">Formulaire d'ajout</h1>

        <form method="post" action="<?= base_url('/livres') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="field">
                <label for="isbn">ISBN</label>
                <input id="isbn" name="isbn" type="text" value="<?= esc((string) old('isbn')) ?>" required>
                <?php if (isset($errors['isbn'])): ?>
                    <div class="error"><?= esc((string) $errors['isbn']) ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="titre">Titre</label>
                <input id="titre" name="titre" type="text" value="<?= esc((string) old('titre')) ?>" required>
                <?php if (isset($errors['titre'])): ?>
                    <div class="error"><?= esc((string) $errors['titre']) ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="auteur">Auteur</label>
                <input id="auteur" name="auteur" type="text" value="<?= esc((string) old('auteur')) ?>" required>
                <?php if (isset($errors['auteur'])): ?>
                    <div class="error"><?= esc((string) $errors['auteur']) ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="auteurs">Auteurs (selection multiple)</label>
                <?php $auteursSelectionnes = old('auteurs') ?? []; ?>
                <select id="auteurs" name="auteurs[]" multiple size="5">
                    <?php foreach (($auteurs ?? []) as $auteur): ?>
                        <?php
                            $nomComplet = trim((string) ($auteur['prenom'] ?? '') . ' ' . (string) ($auteur['nom'] ?? ''));
                            $selected = in_array((string) $auteur['id'], array_map('strval', (array) $auteursSelectionnes), true);
                        ?>
                        <option value="<?= esc((string) $auteur['id']) ?>" <?= $selected ? 'selected' : '' ?>>
                            <?= esc($nomComplet !== '' ? $nomComplet : (string) $auteur['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="mini-note" style="margin-top:0.4rem;">Maintenez Ctrl/Cmd pour selectionner plusieurs auteurs</div>
            </div>

            <div class="field">
                <label for="nouveaux_auteurs">Nouveaux auteurs (un par ligne: Prenom Nom)</label>
                <textarea id="nouveaux_auteurs" name="nouveaux_auteurs" rows="3" placeholder="Ex: Victor Hugo\nMary Shelley"><?= esc((string) old('nouveaux_auteurs')) ?></textarea>
            </div>

            <div class="field">
                <label for="categorie">Categorie</label>
                <input id="categorie" name="categorie" type="text" list="categories-suggestions" value="<?= esc((string) old('categorie')) ?>" placeholder="Saisir une categorie" required>
                <datalist id="categories-suggestions">
                    <?php foreach (($categories ?? []) as $category): ?>
                        <option value="<?= esc($category) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <?php if (! empty($categories ?? [])): ?>
                    <div class="mini-note" style="margin-top:0.4rem;">Suggestions disponibles selon les categories deja en base</div>
                <?php else: ?>
                    <div class="mini-note" style="margin-top:0.4rem;">Aucune categorie en base: saisissez-en une manuellement</div>
                <?php endif; ?>
                <?php if (isset($errors['categorie'])): ?>
                    <div class="error"><?= esc((string) $errors['categorie']) ?></div>
                <?php endif; ?>
            </div>

            <div class="row-2">
                <div class="field" style="flex: 1;">
                    <label for="annee_publication">Annee de publication</label>
                    <input id="annee_publication" name="annee_publication" type="number" min="0" max="<?= esc((string) date('Y')) ?>" value="<?= esc((string) old('annee_publication')) ?>" required>
                    <?php if (isset($errors['annee_publication'])): ?>
                        <div class="error"><?= esc((string) $errors['annee_publication']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="field">
                <label for="resume">Resume</label>
                <textarea id="resume" name="resume" rows="5" placeholder="Resume du livre"><?= esc((string) old('resume')) ?></textarea>
                <?php if (isset($errors['resume'])): ?>
                    <div class="error"><?= esc((string) $errors['resume']) ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="couverture">Couverture (JPEG, PNG ou WEBP, max 2 Mo)</label>
                <input id="couverture" name="couverture" type="file" accept="image/jpeg,image/png,image/webp">
                <?php if (isset($errors['couverture'])): ?>
                    <div class="error"><?= esc((string) $errors['couverture']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
