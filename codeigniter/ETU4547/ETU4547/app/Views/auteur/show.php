<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width:900px; margin:0 auto;">
    <?php
        $prenom = trim((string) ($auteur['prenom'] ?? ''));
        $nom = trim((string) ($auteur['nom'] ?? ''));
        $nomComplet = trim($prenom . ' ' . $nom);
    ?>
    <a href="<?= base_url('/livres') ?>" class="btn btn-secondary" style="margin-bottom:1rem; display:inline-block;">Retour au catalogue</a>
    <h1 style="margin-top:0;"><?= esc($nomComplet !== '' ? $nomComplet : $nom) ?></h1>

    <?php if (! empty($auteur['biographie'])): ?>
        <p style="color:var(--ink-light); line-height:1.7;"><?= nl2br(esc((string) $auteur['biographie'])) ?></p>
    <?php endif; ?>

    <h2 style="margin-top:2rem;">Livres associes</h2>

    <?php if (empty($livres)): ?>
        <p style="color:var(--ink-light);">Aucun livre associe a cet auteur.</p>
    <?php else: ?>
        <ul style="display:grid; gap:0.6rem; padding-left:1rem;">
            <?php foreach ($livres as $livre): ?>
                <li>
                    <a href="<?= base_url('/livres/' . $livre['id']) ?>">
                        <?= esc((string) $livre['titre']) ?>
                    </a>
                    <span style="color:var(--ink-light);">(<?= esc((string) ($livre['annee_publication'] ?? '-')) ?>)</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
