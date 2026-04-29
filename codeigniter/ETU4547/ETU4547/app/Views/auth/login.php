<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Connexion</h1>
        
        <?php if (session()->getFlashdata('erreur')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('erreur') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('/auth/login') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>

        <p class="text-center mt-3">
            Pas encore inscrit? <a href="<?= base_url('/auth/register') ?>">S'inscrire</a>
        </p>
    </div>
</div>

<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
        padding: 20px;
    }

    .auth-card {
        background: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .auth-card h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .btn-block {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
    }

    .text-center {
        text-align: center;
    }
</style>

<?= $this->endSection() ?>
