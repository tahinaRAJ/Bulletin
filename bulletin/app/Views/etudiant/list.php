<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="page-header">
  <h2>Liste des Étudiants</h2>
</div>

<div class="toolbar">
  <div class="toolbar-left">
    <div class="search-box">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" placeholder="Rechercher..." />
    </div>
  </div>
</div>

<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>Numéro Étudiant</th>
        <th>Nom</th>
        <th class="td-actions">Actions</th>
      </tr>
    </thead>
    <tbody id="etuTableBody">
      <?php if (empty($etudiants)): ?>
        <tr>
          <td colspan="3" style="text-align: center; padding: 30px;">Aucun étudiant trouvé</td>
        </tr>
      <?php else: ?>
        <?php foreach ($etudiants as $etu): ?>
          <tr class="etu-row" data-nom="<?= strtolower($etu['nom']) ?>">
            <td><?= esc($etu['id']) ?></td>
            <td><?= esc($etu['nom']) ?></td>
            <td class="td-actions">
              <a href="<?= base_url('etud/' . $etu['id']) ?>" class="action-btn" title="Voir la fiche">
                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('.etu-row');
  rows.forEach(row => {
    const nom = row.getAttribute('data-nom');
    row.style.display = nom.includes(filter) ? '' : 'none';
  });
});
</script>

<?php $this->endSection(); ?>
