<?php $this->extend('layouts/login_standalone'); ?>

<?php $this->section('content'); ?>

<div class="login-page">
  <div class="login-card">

    <div class="login-logo">
      <div class="logo-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </div>
      <div>
        <h1>SysInfo</h1>
        <span>Gestion des Notes</span>
      </div>
    </div>

    <h2>Connexion</h2>
    <p class="subtitle">Connectez-vous pour accéder à la gestion des notes</p>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert" style="background: rgba(239, 68, 68, 0.08); color: #b91c1c; margin-bottom: 20px; padding: 12px 16px; border-radius: 8px;">
        <svg viewBox="0 0 24 24" style="stroke: currentColor; fill: none; width: 16px; height: 16px; display: inline; margin-right: 8px;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('login') ?>">
      <?= csrf_field() ?>

      <div class="field-group">
        <label>Nom d'utilisateur</label>
        <div class="input-wrap">
          <div class="icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"/></svg>
          </div>
          <input type="text" name="nom" placeholder="admin" value="admin" required />
        </div>
      </div>

      <div class="field-group">
        <label>Mot de passe</label>
        <div class="input-wrap">
          <div class="icon">
            <svg viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <input type="password" name="mdp" placeholder="••••••••" value="1234" required />
        </div>
      </div>

      <div class="remember-row">
        <label>
          <input type="checkbox" checked />
          Se souvenir de moi
        </label>
      </div>

      <button type="submit" class="btn btn-primary btn-full">
        Connexion
      </button>
    </form>

    <div class="login-footer">
      <p>Identifiants de test : <strong>admin / 1234</strong></p>
    </div>

  </div>
</div>

<?php $this->endSection(); ?>
