<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="page-header">
  <h2>Ajouter une Note</h2>
</div>

<div class="form-card">
  <form method="POST" action="<?= base_url('insert') ?>">
    <?= csrf_field() ?>

    <div class="form-section-title">Informations de la Note</div>

    <div class="form-grid cols-2">
      <div class="section-gap">
        <label class="field-label">Étudiant <span class="required">*</span></label>
        <select name="id_etu" required>
          <option value="">-- Sélectionner un étudiant --</option>
          <?php foreach ($etudiants as $etu): ?>
            <option value="<?= esc($etu['id']) ?>"><?= esc($etu['id']) ?> - <?= esc($etu['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="section-gap">
        <label class="field-label">Matière <span class="required">*</span></label>
        <select name="id_matiere" required>
          <option value="">-- Sélectionner une matière --</option>
          <?php foreach ($matieres as $matiere): ?>
            <option value="<?= esc($matiere['id']) ?>">
              <?= esc($matiere['nom']) ?> (<?= esc($matiere['semestre_label']) ?>) - Coef: <?= esc($matiere['coef']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-grid cols-1">
      <div class="section-gap">
        <label class="field-label">Note (0-20) <span class="required">*</span></label>
        <input type="number" name="note" placeholder="Entrer la note" step="0.5" min="0" max="20" required />
        <div class="field-hint">La note doit être entre 0 et 20. Vous pouvez saisir plusieurs fois la même matière pour un étudiant.</div>
      </div>
    </div>

    <div class="form-footer">
      <a href="<?= base_url('list') ?>" class="btn btn-secondary">Annuler</a>
      <button type="submit" class="btn btn-primary">Ajouter la Note</button>
    </div>
  </form>
</div>

<!-- Messages récents -->
<?php if (!empty($notes_recentes)): ?>
  <div style="margin-top: 30px;">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Dernières notes ajoutées</h3>
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Étudiant</th>
            <th>Matière</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (array_slice($notes_recentes, 0, 5) as $note): ?>
            <tr>
              <td><?= esc($note['etudiant']) ?></td>
              <td><?= esc($note['matiere']) ?></td>
              <td><strong><?= esc($note['note']) ?>/20</strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php $this->endSection(); ?>
