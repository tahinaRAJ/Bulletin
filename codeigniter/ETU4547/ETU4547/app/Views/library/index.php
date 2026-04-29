<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Catalogue Bibliotheque<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .hero-card {
        display: grid;
        grid-template-columns: 1fr 0.9fr;
        gap: 1rem;
        align-items: stretch;
        padding: 0;
        overflow: hidden;
        border-radius: 1rem;
    }

    .hero-left,
    .hero-right {
        padding: 1.2rem;
    }

    .hero-left {
        background: linear-gradient(145deg, var(--forest), #244b38);
        color: var(--cream);
        position: relative;
        overflow: hidden;
    }

    .eyebrow {
        margin: 0 0 0.8rem;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: rgba(247, 240, 226, 0.72);
    }

    .hero-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.1rem, 3.5vw, 3.2rem);
        line-height: 1.02;
        font-weight: 500;
        margin: 0 0 0.85rem;
    }

    .hero-desc {
        max-width: 56ch;
        line-height: 1.8;
        color: rgba(247, 240, 226, 0.82);
        margin: 0 0 1.4rem;
    }

    .hero-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .hero-note {
        margin-top: 1.4rem;
        padding-top: 1.1rem;
        border-top: 1px solid rgba(247, 240, 226, 0.14);
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .hero-note div {
        min-width: 8rem;
    }

    .hero-note .num {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        line-height: 1;
        color: var(--cream);
        margin-bottom: 0.2rem;
    }

    .hero-note .label {
        font-size: 0.72rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: rgba(247, 240, 226, 0.55);
    }

    .search-shell {
        background: rgba(247, 240, 226, 0.92);
        border: 1px solid var(--line);
        border-radius: 1rem;
        padding: 0.95rem;
        margin-bottom: 1rem;
    }

    .search-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: var(--gold);
        margin-bottom: 0.85rem;
    }

    .search-row {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr auto auto;
        gap: 0.75rem;
        align-items: start;
    }

    .search-row input,
    .search-row select {
        width: 100%;
    }

    .collection-card {
        background: rgba(255, 250, 242, 0.9);
        border-radius: 1rem;
        overflow: hidden;
        padding: 0;
    }

    .table-shell {
        overflow: hidden;
        padding: 0;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .table-headline {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 1rem;
        margin: 0 0 1rem;
    }

    .table-headline h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(1.7rem, 2.5vw, 2rem);
        font-weight: 500;
        margin: 0;
    }

    .table-headline p {
        margin: 0;
        color: var(--ink-light);
        line-height: 1.7;
        max-width: 55ch;
    }

    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        background: rgba(255, 250, 242, 0.92);
    }

    col.col-title { width: 18%; }
    col.col-author { width: 16%; }
    col.col-year { width: 8%; }
    col.col-category { width: 12%; }
    col.col-isbn { width: 14%; }
    col.col-status { width: 10%; }
    col.col-action { width: 22%; }

    th, td {
        text-align: left;
        border-bottom: 1px solid rgba(26, 23, 16, 0.10);
        padding: 0.85rem 0.8rem;
        vertical-align: top;
        word-break: break-word;
    }

    th {
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--ink-light);
        background: rgba(237, 227, 206, 0.58);
    }

    .pager {
        margin-top: 1rem;
    }

    .action-group {
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        align-items: flex-start;
    }

    .action-group form {
        margin: 0;
    }

    .action-row {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-group .btn,
    .action-group button {
        white-space: nowrap;
        padding: 0.6rem 0.8rem;
        font-size: 0.84rem;
    }

    .loan-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .loan-form input {
        width: 160px;
        min-width: 160px;
    }

    .delete-form button {
        padding-left: 0.8rem;
        padding-right: 0.8rem;
    }

    @media (max-width: 900px) {
        .hero-card,
        .table-headline,
        .search-row {
            grid-template-columns: 1fr;
        }

        .table-wrap {
            overflow-x: visible;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead {
            display: none;
        }

        tr {
            border: 1px solid rgba(26, 23, 16, 0.10);
            border-radius: 0.95rem;
            margin-bottom: 0.85rem;
            padding: 0.85rem;
            background: rgba(255, 250, 242, 0.92);
        }

        td {
            border: 0;
            padding: 0.45rem 0;
        }

        td[data-label]::before {
            content: attr(data-label) ": ";
            display: block;
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--ink-light);
            margin-bottom: 0.2rem;
        }
    }
</style>

    <div class="card hero-card">
        <div class="hero-left">
            <p class="eyebrow">Bibliotheque Lumiere</p>
            <h1 class="hero-title">Un catalogue de livres<br>sobre, clair et apaisant.</h1>
            <p class="hero-desc">Explorez les ouvrages, consultez leur fiche et gerez les mouvements dans une interface plus elegante, inspiree d'un univers editorial classique.</p>
            <div class="hero-actions">
                <a class="btn" href="<?= base_url('/livres/new') ?>">Ajouter un livre</a>
                <a class="btn btn-secondary" href="<?= base_url('/livres') ?>">Voir le catalogue</a>
            </div>
            <div class="hero-note">
                <div>
                    <div class="num">10</div>
                    <div class="label">Resultats par page</div>
                </div>
                <div>
                    <div class="num">CSRF</div>
                    <div class="label">Protection active</div>
                </div>
                <div>
                    <div class="num">XSS</div>
                    <div class="label">Donnees echappees</div>
                </div>
            </div>
        </div>
        <div class="hero-right">
            <div class="search-shell">
                <p class="search-label">Rechercher dans le catalogue</p>
                <form method="get" action="<?= base_url('/livres') ?>" class="search-row">
                    <input type="text" name="mot_cle" placeholder="Titre" value="<?= esc($motCle ?? '') ?>">
                    <select name="categorie">
                        <option value="">Toutes les categories</option>
                        <?php foreach (($categories ?? []) as $category): ?>
                            <option value="<?= esc($category) ?>" <?= ($categorieSelectionnee ?? '') === $category ? 'selected' : '' ?>>
                                <?= esc($category) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Filtrer</button>
                    <a class="btn btn-secondary" href="<?= base_url('/livres') ?>">Reinitialiser</a>
                </form>
            </div>
            <div class="card" style="margin:0; background: rgba(237, 227, 206, 0.36); border-style:dashed;">
                <p class="eyebrow" style="color: var(--forest); margin-bottom: 0.5rem;">Conseil de lecture</p>
                <p style="margin:0; line-height:1.8; color: var(--ink-light);">Gardez la fiche detaillee pour consulter le dernier emprunteur, puis utilisez les actions rapides directement dans la liste.</p>
            </div>
        </div>
    </div>

    <div class="table-headline" style="margin-top: 1.2rem;">
        <div>
            <h2>Catalogue des ouvrages</h2>
            <p>Vue d'ensemble des livres disponibles et pretes, avec recherche et actions rapides directement depuis la liste.</p>
        </div>
    </div>

    <?php
        $currentQuery = $_GET ?? [];
        $currentSort = strtolower((string) ($sort ?? ''));
        $currentOrder = strtolower((string) ($order ?? 'asc'));
        $buildSortLink = static function (string $column) use ($currentQuery, $currentSort, $currentOrder): string {
            $nextOrder = ($currentSort === $column && $currentOrder === 'asc') ? 'desc' : 'asc';
            $query = $currentQuery;
            $query['sort'] = $column;
            $query['order'] = $nextOrder;
            return base_url('/livres') . '?' . http_build_query($query);
        };
        $sortIndicator = static function (string $column) use ($currentSort, $currentOrder): string {
            if ($currentSort !== $column) {
                return '';
            }
            return $currentOrder === 'asc' ? ' (ASC)' : ' (DESC)';
        };
    ?>

    <div class="card table-shell">
        <div class="table-wrap">
        <table>
            <colgroup>
                <col class="col-title">
                <col class="col-author">
                <col class="col-year">
                <col class="col-category">
                <col class="col-isbn">
                <col class="col-status">
                <col class="col-action">
            </colgroup>
            <thead>
            <tr>
                <th><a href="<?= $buildSortLink('titre') ?>">Titre<?= $sortIndicator('titre') ?></a></th>
                <th><a href="<?= $buildSortLink('auteur') ?>">Auteur<?= $sortIndicator('auteur') ?></a></th>
                <th><a href="<?= $buildSortLink('annee_publication') ?>">Annee<?= $sortIndicator('annee_publication') ?></a></th>
                <th>Categorie</th>
                <th>ISBN</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (! empty($livres)): ?>
                <?php foreach ($livres as $livre): ?>
                    <?php $isAvailable = (($livre['statut'] ?? 'disponible') === 'disponible'); ?>
                    <tr>
                        <td data-label="Titre"><?= esc($livre['titre']) ?></td>
                        <td data-label="Auteur"><?= esc($livre['auteur']) ?></td>
                        <td data-label="Annee"><?= esc((string) $livre['annee_publication']) ?></td>
                        <td data-label="Categorie"><?= esc((string) ($livre['categorie'] ?? '-')) ?></td>
                        <td data-label="ISBN"><?= esc($livre['isbn']) ?></td>
                        <td data-label="Statut">
                            <?php if ($isAvailable): ?>
                                <span class="status ok">Disponible</span>
                            <?php else: ?>
                                <span class="status ko">Prete</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Action">
                            <div class="action-group">
                                <div class="action-row">
                                    <a class="btn btn-secondary" href="<?= base_url('/livres/' . $livre['id']) ?>">Voir fiche</a>
                                    <?php if (! $isAvailable): ?>
                                        <form method="post" action="<?= base_url('/livres/' . $livre['id'] . '/return') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-secondary">Retour</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isAvailable): ?>
                                    <form class="loan-form" method="post" action="<?= base_url('/livres/' . $livre['id'] . '/loan') ?>">
                                        <?= csrf_field() ?>
                                        <input type="text" name="nom_emprunteur" placeholder="Nom emprunteur" required>
                                        <button type="submit">Preter</button>
                                    </form>
                                <?php endif; ?>
                                <form class="delete-form" method="post" action="<?= base_url('/livres/' . $livre['id'] . '/delete') ?>" onsubmit="return confirm('Confirmer la suppression de ce livre ?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Aucun livre trouve pour ce filtre.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>

        <?php if (isset($pager) && $pager !== null): ?>
            <div class="pager">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>
