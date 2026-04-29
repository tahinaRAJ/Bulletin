<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Inscription</h1>
        
        <?php if (session()->getFlashdata('erreur')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('erreur') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('/auth/register') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    class="form-control <?= isset($erreurs['nom']) ? 'is-invalid' : '' ?>"
                    value="<?= old('nom') ?>"
                    required
                >
                <?php if (isset($erreurs['nom'])) : ?>
                    <small class="form-text text-danger"><?= $erreurs['nom'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control <?= isset($erreurs['email']) ? 'is-invalid' : '' ?>"
                    value="<?= old('email') ?>"
                    required
                >
                <?php if (isset($erreurs['email'])) : ?>
                    <small class="form-text text-danger"><?= $erreurs['email'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control <?= isset($erreurs['password']) ? 'is-invalid' : '' ?>"
                    required
                >
                <?php if (isset($erreurs['password'])) : ?>
                    <small class="form-text text-danger"><?= $erreurs['password'] ?></small>
                <?php endif; ?>
                <small class="form-text text-muted">Minimum 8 caractères</small>
            </div>

            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>

        <p class="text-center mt-3">
            Vous avez déjà un compte? <a href="<?= base_url('/auth/login') ?>">Se connecter</a>
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

    .form-group input.is-invalid {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .form-text.text-danger {
        color: #dc3545;
        display: block;
        margin-top: 5px;
    }

    .form-text.text-muted {
        color: #6c757d;
        font-size: 12px;
        margin-top: 5px;
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
