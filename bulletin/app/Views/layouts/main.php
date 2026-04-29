<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? 'Gestion des Notes - SysInfo' ?></title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />
</head>
<body>

<div class="app">

  <!-- ── Sidebar ──────────────────────────────────────────────────────────── -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="logo-icon">
        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </div>
      <div>
        <div class="brand-name">SysInfo</div>
        <div class="brand-sub">v1.0 Notes</div>
      </div>
    </div>

    <div class="sidebar-section">Navigation</div>

    <a href="<?= base_url('list') ?>" class="nav-item <?= ($page ?? '') === 'list' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
      Étudiants
    </a>
    <a href="<?= base_url('insert') ?>" class="nav-item <?= ($page ?? '') === 'insert' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Ajouter Note
    </a>

    <div class="sidebar-bottom">
      <div class="user-row">
        <div class="avatar">
          <?= strtoupper(substr(session('user_nom', 'A'), 0, 1)) ?>
        </div>
        <div class="user-info">
          <div class="name"><?= session('user_nom', 'Utilisateur') ?></div>
          <div class="role">Admin</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ── Main ──────────────────────────────────────────────────────────── -->
  <div class="main">

    <!-- ── Topbar ────────────────────────────────────────────────────────── -->
    <div class="topbar">
      <div class="topbar-title"><?= $page_title ?? 'Gestion des Notes' ?></div>
      <div class="topbar-actions">
        <a href="<?= base_url('login/logout') ?>" class="btn btn-secondary btn-sm">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
          Déconnexion
        </a>
      </div>
    </div>

    <!-- ── Content ────────────────────────────────────────────────────────── -->
    <div class="content">
      <!-- Messages d'alerte -->
      <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
          <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
          <div><?= $success ?></div>
        </div>
      <?php endif; ?>

      <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          <div><?= $error ?></div>
        </div>
      <?php endif; ?>

      <!-- Contenu spécifique à la page -->
      <?= $this->renderSection('content') ?>
    </div>

  </div>

</div>

<style>
  .alert-success {
    background: rgba(34, 197, 94, 0.08);
    color: #15803d;
    border-left: 3px solid #22c55e;
  }
  
  .alert-error {
    background: rgba(239, 68, 68, 0.08);
    color: #b91c1c;
    border-left: 3px solid #ef4444;
  }
  
  .alert svg {
    stroke: currentColor;
    fill: none;
  }
</style>

</body>
</html>
