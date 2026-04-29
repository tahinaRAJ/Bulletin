<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title><?= esc($this->renderSection('title') ?: 'Gestion Bibliotheque') ?></title>
    <style>
        :root {
            --cream: #f7f0e2;
            --cream-dark: #ede3ce;
            --ink: #1a1710;
            --ink-light: #4a4335;
            --forest: #1c3a2b;
            --forest-mid: #2a5240;
            --gold: #b8893a;
            --gold-light: #d4a84e;
            --rust: #7a3b2e;
            --line: rgba(26, 23, 16, 0.12);
            --card: rgba(255, 250, 242, 0.78);
            --accent: var(--forest);
            --ok: #1f6e3e;
            --ko: #a63b2a;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Jost", sans-serif;
            color: var(--ink);
            background: #f7f1e6;
            min-height: 100vh;
        }

        .topbar {
            background: rgba(247, 241, 230, 0.96);
            border-bottom: 1px solid rgba(26, 23, 16, 0.08);
        }

        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.9rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .brand {
            color: var(--forest);
            text-decoration: none;
            font-family: "Cormorant Garamond", serif;
            font-size: 1.45rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .nav-links {
            display: flex;
            gap: 1.1rem;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: var(--ink-light);
            text-decoration: none;
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .nav-links a:hover {
            color: var(--forest);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.25rem 1.35rem 2.4rem;
        }

        .card {
            background: #fbf7ef;
            border: 1px solid rgba(26, 23, 16, 0.09);
            border-radius: 1rem;
            padding: 1.15rem;
            margin-bottom: 0.9rem;
            box-shadow: none;
        }

        .flash {
            padding: 10px;
            border-radius: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            font-size: 0.95rem;
        }

        .flash.success {
            background: rgba(31, 110, 62, 0.10);
            color: #175c34;
            border-color: rgba(31, 110, 62, 0.18);
        }

        .flash.error {
            background: rgba(166, 59, 42, 0.10);
            color: #8a2f24;
            border-color: rgba(166, 59, 42, 0.18);
        }

        .row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        input, select, textarea, button {
            font: inherit;
            padding: 0.78rem 0.95rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(26, 23, 16, 0.14);
            background: #fffdf8;
            color: var(--ink);
        }

        button, .btn {
            background: var(--accent);
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        button:hover, .btn:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #475467;
        }

        .btn-danger {
            background: var(--rust);
        }

        .status {
            display: inline-block;
            padding: 0.34rem 0.68rem;
            border-radius: 999px;
            color: #fff;
            font-size: 0.72rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .status.ok { background: var(--ok); }
        .status.ko { background: var(--ko); }

        .error {
            color: #8a2f24;
            font-size: 0.84rem;
            margin-top: 0.35rem;
        }

        .page-title {
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(2rem, 3.4vw, 3rem);
            line-height: 1.02;
            font-weight: 500;
            margin: 0 0 0.7rem;
        }

        .page-subtitle {
            max-width: 62ch;
            color: var(--ink-light);
            line-height: 1.8;
            font-size: 1rem;
            margin: 0;
        }

        @media (max-width: 780px) {
            .topbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .container {
                padding: 1.1rem 0.9rem 2.2rem;
            }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="<?= base_url('/livres') ?>">Bibliotheque Lumiere</a>
        <nav class="nav-links">
            <a href="<?= base_url('/livres') ?>">Catalogue</a>
            
            <?php if (session()->get('user')) : ?>
                <a href="<?= base_url('/livres/new') ?>">Ajouter</a>
                <a href="<?= base_url('/profile') ?>">Mon Profil</a>
                <a href="<?= base_url('/auth/logout') ?>">Déconnexion</a>
            <?php else : ?>
                <a href="<?= base_url('/auth/login') ?>">Connexion</a>
                <a href="<?= base_url('/auth/register') ?>">S'inscrire</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash success"><?= esc((string) session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash error"><?= esc((string) session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>
</body>
</html>
