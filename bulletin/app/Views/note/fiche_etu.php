<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="page-header">
  <h2>Fiche de l'Étudiant: <?= esc($etudiant['nom']) ?></h2>
  <p style="color: #64748b; font-size: 14px;">ID: <?= esc($etudiant['id']) ?></p>
</div>

<!-- Onglets de navigation -->
<div style="display: flex; gap: 0; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0;">
  <button class="tab-btn active" data-tab="s3" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">S3</button>
  <button class="tab-btn" data-tab="s4-dev" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">S4 - Dev</button>
  <button class="tab-btn" data-tab="s4-bddres" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">S4 - BddRes</button>
  <button class="tab-btn" data-tab="s4-web" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">S4 - Web</button>
  <button class="tab-btn" data-tab="l2-dev" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">L2 - Dev</button>
  <button class="tab-btn" data-tab="l2-bddres" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">L2 - BddRes</button>
  <button class="tab-btn" data-tab="l2-web" style="padding: 12px 16px; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent;">L2 - Web</button>
</div>

<!-- Tab S3 -->
<div class="tab-content active" id="tab-s3">
  <div class="table-card">
    <table>
      <thead>
        <tr>
          <th>Matière</th>
          <th>Coef</th>
          <th>Note</th>
          <th class="td-actions">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $notes_s3 = array_filter($notes, function($n) { return $n['id_semestre'] == 1 && !$n['optional']; });
        if (empty($notes_s3)): ?>
          <tr><td colspan="4" style="text-align: center; padding: 20px;">Aucune note pour S3</td></tr>
        <?php else: ?>
          <?php foreach ($notes_s3 as $note): ?>
            <tr>
              <td><?= esc($note['matiere_nom']) ?></td>
              <td><?= esc($note['coef']) ?></td>
              <td><strong><?= number_format($note['note'], 2) ?>/20</strong></td>
              <td class="td-actions">
                <?php if (!empty($note['id'])): ?>
                  <a href="<?= base_url('note/supprimer/' . $note['id'] . '/' . $note['id_etu']) ?>" class="action-btn del" onclick="return confirm('Supprimer cette note ?')" title="Supprimer">
                    <svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h16zM10 11v6M14 11v6"/></svg>
                  </a>
                <?php else: ?>
                  <span style="color:#94a3b8;">-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if (!empty($moyennesSemestre)): ?>
    <?php $moy_s3 = array_filter($moyennesSemestre, function($m) { return $m['id_semestre'] == 1; }); ?>
    <?php if (!empty($moy_s3)): ?>
      <div style="margin-top: 16px; padding: 16px; background: rgba(37, 99, 235, 0.08); border-radius: 8px;">
        <strong>Moyenne S3:</strong> <?= number_format(reset($moy_s3)['moyenne'], 2) ?>/20
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<!-- Tab S4 - Dev -->
<div class="tab-content" id="tab-s4-dev">
  <?= view('note/ficheoptionsemestre', ['notes' => $notes, 'semestre' => 2, 'option' => 'dev', 'moyennesSemestre' => $moyennesSemestre]) ?>
</div>

<!-- Tab S4 - BddRes -->
<div class="tab-content" id="tab-s4-bddres">
  <?= view('note/ficheoptionsemestre', ['notes' => $notes, 'semestre' => 2, 'option' => 'bddres', 'moyennesSemestre' => $moyennesSemestre]) ?>
</div>

<!-- Tab S4 - Web -->
<div class="tab-content" id="tab-s4-web">
  <?= view('note/ficheoptionsemestre', ['notes' => $notes, 'semestre' => 2, 'option' => 'web', 'moyennesSemestre' => $moyennesSemestre]) ?>
</div>

<!-- Tab L2 - Dev -->
<div class="tab-content" id="tab-l2-dev">
  <?= view('note/fichel2option', ['notes' => $notes, 'option' => 'dev', 'moyenneAnnee' => $moyenneAnnee]) ?>
</div>

<!-- Tab L2 - BddRes -->
<div class="tab-content" id="tab-l2-bddres">
  <?= view('note/fichel2option', ['notes' => $notes, 'option' => 'bddres', 'moyenneAnnee' => $moyenneAnnee]) ?>
</div>

<!-- Tab L2 - Web -->
<div class="tab-content" id="tab-l2-web">
  <?= view('note/fichel2option', ['notes' => $notes, 'option' => 'web', 'moyenneAnnee' => $moyenneAnnee]) ?>
</div>

<style>
  .tab-btn {
    border-color: transparent !important;
    transition: all 0.2s;
  }
  
  .tab-btn.active {
    color: #2563eb !important;
    border-bottom-color: #2563eb !important;
  }
  
  .tab-content {
    display: none;
  }
  
  .tab-content.active {
    display: block;
  }
</style>

<script>
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const tab = this.getAttribute('data-tab');
      
      // Désactiver tous les onglets et contenus
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      
      // Activer le nouvel onglet
      this.classList.add('active');
      document.getElementById('tab-' + tab).classList.add('active');
    });
  });
</script>

<?php $this->endSection(); ?>
