<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Catalogue - Export PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 20px; margin: 0 0 10px; }
        .meta { margin-bottom: 15px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f3f3; }
        .small { color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Catalogue de la bibliotheque</h1>
    <div class="meta">Date d'export: <?= esc($date) ?></div>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteurs</th>
                <th>ISBN</th>
                <th>Categorie</th>
                <th>Annee</th>
                <th>Statut</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($catalogue)): ?>
                <tr>
                    <td colspan="7">Aucun livre dans le catalogue.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($catalogue as $livre): ?>
                    <tr>
                        <td><?= esc($livre['titre']) ?></td>
                        <td><?= esc($livre['auteurs']) ?></td>
                        <td><?= esc($livre['isbn']) ?></td>
                        <td><?= esc($livre['categorie']) ?></td>
                        <td><?= esc((string) $livre['annee']) ?></td>
                        <td><?= esc($livre['statut']) ?></td>
                        <td><?= esc($livre['note']) ?>/5 (<?= esc((string) $livre['nb_notes']) ?>)</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p class="small">Export genere automatiquement depuis le module bibliotheque.</p>
</body>
</html>
