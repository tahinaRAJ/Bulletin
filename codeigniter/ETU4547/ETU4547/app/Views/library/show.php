<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Fiche livre<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .detail-hero {
        display: grid;
        grid-template-columns: 1fr 0.95fr;
        gap: 1rem;
        padding: 0;
        overflow: hidden;
    }

    .detail-intro {
        background: linear-gradient(145deg, var(--forest), #234834);
        color: var(--cream);
        padding: 1.2rem;
    }

    .detail-kicker {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: rgba(247, 240, 226, 0.72);
        margin: 0 0 0.8rem;
    }

    .detail-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 3.5vw, 3rem);
        line-height: 1.02;
        font-weight: 500;
        margin: 0 0 0.8rem;
    }

    .detail-summary {
        margin: 0;
        max-width: 60ch;
        line-height: 1.8;
        color: rgba(247, 240, 226, 0.84);
    }

    .detail-meta {
        padding: 1.2rem;
        background: rgba(255, 250, 242, 0.92);
    }

    .cover {
        width: 100%;
        border-radius: 1rem;
        border: 1px solid rgba(26, 23, 16, 0.12);
        box-shadow: none;
    }

    .grid {
        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        gap: 1rem;
    }

    .meta-list {
        display: grid;
        gap: 0.9rem;
    }

    .meta-item {
        padding-bottom: 0.9rem;
        border-bottom: 1px solid rgba(26, 23, 16, 0.10);
    }

    .meta-label {
        display: block;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: var(--gold);
        margin-bottom: 0.2rem;
    }

    .meta-value {
        color: var(--ink);
        line-height: 1.6;
    }

    .section-card {
        background: rgba(255, 250, 242, 0.92);
        border: 1px solid rgba(26, 23, 16, 0.10);
        border-radius: 1rem;
        padding: 1rem;
    }

    @media (max-width: 780px) {
        .detail-hero,
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<a href="<?= base_url('/livres') ?>" class="btn btn-secondary" style="margin-bottom:1rem; display:inline-block;">Retour au catalogue</a>

<div class="card detail-hero">
    <div class="detail-intro">
        <p class="detail-kicker">Fiche detaillee</p>
        <h1 class="detail-title"><?= esc($livre['titre']) ?></h1>
        <p class="detail-summary">Presentation concise du livre et de son etat dans le catalogue.</p>
    </div>
    <div class="detail-meta">
        <?php if (! empty($livre['couverture'])): ?>
            <img class="cover" src="<?= base_url('uploads/' . $livre['couverture']) ?>" alt="Couverture">
        <?php else: ?>
            <p style="margin:0; color: var(--ink-light);">Aucune couverture.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card grid">
    <div class="meta-list">
        <div class="meta-item">
            <span class="meta-label">Auteur</span>
            <div class="meta-value">
                <?php if (! empty($auteurs ?? [])): ?>
                    <?php
                        $liens = array_map(static function (array $auteur): string {
                            $prenom = trim((string) ($auteur['prenom'] ?? ''));
                            $nom = trim((string) ($auteur['nom'] ?? ''));
                            $nomComplet = trim($prenom . ' ' . $nom);
                            $label = $nomComplet !== '' ? $nomComplet : (string) $auteur['nom'];
                            $url = base_url('/auteurs/' . $auteur['id']);
                            return '<a href="' . esc($url) . '">' . esc($label) . '</a>';
                        }, $auteurs);
                    ?>
                    <?= implode(', ', array_filter($liens)) ?>
                <?php else: ?>
                    <?= esc($livre['auteur']) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="meta-item">
            <span class="meta-label">ISBN</span>
            <div class="meta-value"><?= esc($livre['isbn']) ?></div>
        </div>
        <div class="meta-item">
            <span class="meta-label">Categorie</span>
            <div class="meta-value"><?= esc((string) ($livre['categorie'] ?: '-')) ?></div>
        </div>
        <div class="meta-item">
            <span class="meta-label">Annee</span>
            <div class="meta-value"><?= esc((string) $livre['annee_publication']) ?></div>
        </div>
        <div class="meta-item">
            <span class="meta-label">Statut</span>
            <div class="meta-value">
                <?php if (($livre['statut'] ?? 'disponible') === 'disponible'): ?>
                    <span class="status ok">Disponible</span>
                <?php else: ?>
                    <span class="status ko">Prete</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div>
        <div class="section-card" style="margin-bottom:1rem;">
            <p class="detail-kicker" style="color: var(--forest); margin-bottom: 0.45rem;">Resume</p>
            <p style="margin:0; line-height:1.8; color: var(--ink-light);"><?= nl2br(esc((string) $livre['resume'])) ?></p>
        </div>

        <div class="section-card" style="margin-bottom:1rem;">
            <p class="detail-kicker" style="color: var(--forest); margin-bottom: 0.45rem;">Emprunt</p>
            <p style="margin:0 0 0.3rem;"><strong>Dernier emprunteur:</strong> <?= esc((string) ($dernierEmprunt['nom_emprunteur'] ?? 'Aucun')) ?></p>
            <p style="margin:0;"><strong>Date du dernier emprunt:</strong> <?= esc((string) ($dernierEmprunt['date_emprunt'] ?? '-')) ?></p>
        </div>

        <div class="section-card" style="margin-bottom:1rem;">
            <p class="detail-kicker" style="color: var(--forest); margin-bottom: 0.45rem;">Notation</p>
            <p style="margin:0 0 0.6rem;">
                Note moyenne: <strong><?= number_format((float) ($notes['moyenne'] ?? 0), 1) ?>/5</strong>
                (<?= (int) ($notes['total'] ?? 0) ?> avis)
            </p>
            <form method="post" action="<?= base_url('/livres/' . $livre['id'] . '/note') ?>">
                <?= csrf_field() ?>
                <div style="display:flex; gap:0.6rem; align-items:center; flex-wrap:wrap;">
                    <label for="note" style="margin:0;">Votre note</label>
                    <select id="note" name="note" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit">Noter</button>
                </div>
            </form>
        </div>

        <div class="section-card" style="margin-bottom:1rem;">
            <p class="detail-kicker" style="color: var(--forest); margin-bottom: 0.45rem;">Commentaires</p>
            <?php if (empty($commentaires ?? [])): ?>
                <p style="margin:0; color:var(--ink-light);">Aucun commentaire pour le moment.</p>
            <?php else: ?>
                <div style="display:grid; gap:0.75rem;">
                    <?php foreach ($commentaires as $commentaire): ?>
                        <div style="border-bottom:1px solid rgba(26, 23, 16, 0.08); padding-bottom:0.6rem;">
                            <p style="margin:0 0 0.35rem;"><strong><?= esc((string) ($commentaire['user_nom'] ?? 'Utilisateur')) ?></strong></p>
                            <p style="margin:0 0 0.35rem; color:var(--ink-light); line-height:1.6;">
                                <?= nl2br(esc((string) $commentaire['texte'])) ?>
                            </p>
                            <?php if (session()->get('user') && (int) $commentaire['user_id'] === (int) session()->get('user')['id']): ?>
                                <form method="post" action="<?= base_url('/commentaires/' . $commentaire['id'] . '/delete') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-secondary" onclick="return confirm('Supprimer ce commentaire ?');">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('/livres/' . $livre['id'] . '/commentaires') ?>" style="margin-top:0.8rem;">
                <?= csrf_field() ?>
                <label for="commentaire">Ajouter un commentaire</label>
                <textarea id="commentaire" name="texte" rows="3" required></textarea>
                <button type="submit" style="margin-top:0.6rem;">Publier</button>
            </form>
        </div>

        <?php if (($livre['statut'] ?? 'disponible') === 'disponible'): ?>
            <form method="post" action="<?= base_url('/livres/' . $livre['id'] . '/loan') ?>" class="section-card">
                <?= csrf_field() ?>
                <label for="nom_emprunteur">Nom de l'emprunteur</label>
                <input id="nom_emprunteur" name="nom_emprunteur" type="text" required>
                <?php if (($validation ?? null) && $validation->getError('nom_emprunteur')): ?>
                    <div class="error"><?= esc($validation->getError('nom_emprunteur')) ?></div>
                <?php endif; ?>
                <button type="submit">Preter ce livre</button>
            </form>
        <?php else: ?>
            <form method="post" action="<?= base_url('/livres/' . $livre['id'] . '/return') ?>" class="section-card" style="display:flex; gap:0.8rem; align-items:center; justify-content:space-between; flex-wrap:wrap;">
                <?= csrf_field() ?>
                <div>
                    <p style="margin:0; font-family:'Cormorant Garamond', serif; font-size:1.2rem;">Le livre est actuellement prete.</p>
                    <p style="margin:0; color:var(--ink-light);">Enregistrez le retour lorsqu'il revient a la bibliotheque.</p>
                </div>
                <button class="btn-secondary" type="submit">Retourner ce livre</button>
            </form>
        <?php endif; ?>

        <form method="post" action="<?= base_url('/livres/' . $livre['id'] . '/delete') ?>" style="margin-top: 1rem;">
            <?= csrf_field() ?>
            <button class="btn-danger" type="submit" onclick="return confirm('Confirmer la suppression de ce livre ?');">Supprimer le livre</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
