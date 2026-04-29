<?php
// Vue partielle pour afficher les notes d'un semestre avec option
// Variables: $notes, $semestre, $option, $option_label, $moyennesSemestre

$notes = $notes ?? [];
$semestre = $semestre ?? null;
$option = $option ?? null;
$moyennesSemestre = $moyennesSemestre ?? [];
?>

<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>Matière</th>
        <th>Coef</th>
        <th>Type</th>
        <th>Note</th>
        <th class="td-actions">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $filtered_notes = array_filter($notes, function($n) use ($semestre, $option) {
        if ($n['id_semestre'] != $semestre) return false;

        if ($n['optional'] == 0) return true;

        return ($option == 'dev' && $n['numero'] == 9) ||
               ($option == 'bddres' && $n['numero'] == 10) ||
               ($option == 'web' && $n['numero'] == 11);
      });
      
      if (empty($filtered_notes)): ?>
        <tr><td colspan="5" style="text-align: center; padding: 20px;">Aucune note pour cette option</td></tr>
      <?php else: ?>
        <?php foreach ($filtered_notes as $note): ?>
          <tr>
            <td><?= esc($note['matiere_nom']) ?></td>
            <td><?= esc($note['coef']) ?></td>
            <td>
              <?php if ($note['optional']): ?>
                <span class="badge badge-blue">Optionnelle</span>
              <?php else: ?>
                <span class="badge badge-gray">Obligatoire</span>
              <?php endif; ?>
            </td>
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
  <?php $moy = array_filter($moyennesSemestre, function($m) use ($semestre) { return $m['id_semestre'] == $semestre; }); ?>
  <?php if (!empty($moy)): ?>
    <div style="margin-top: 16px; padding: 16px; background: rgba(37, 99, 235, 0.08); border-radius: 8px;">
      <strong>Moyenne:</strong> <?= number_format(reset($moy)['moyenne'], 2) ?>/20
    </div>
  <?php endif; ?>
<?php endif; ?>
