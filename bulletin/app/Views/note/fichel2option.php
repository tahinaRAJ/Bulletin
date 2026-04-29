<?php
// Vue partielle pour L2 (moyennes annuelles)
// Variables: $notes, $option, $option_label, $moyenneAnnee

$notes = $notes ?? [];
$option = $option ?? null;
$moyenneAnnee = $moyenneAnnee ?? null;
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
      $filtered_notes = array_filter($notes, function($n) use ($option) {
        if ($n['optional'] == 0) return true;

        return ($option == 'dev' && $n['numero'] == 9) ||
               ($option == 'bddres' && $n['numero'] == 10) ||
               ($option == 'web' && $n['numero'] == 11);
      });
      
      if (empty($filtered_notes)): ?>
        <tr><td colspan="5" style="text-align: center; padding: 20px;">Aucune note</td></tr>
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

<?php if ($moyenneAnnee): ?>
  <div style="margin-top: 16px; padding: 16px; background: rgba(37, 99, 235, 0.08); border-radius: 8px;">
    <strong>Moyenne Annuelle (S3 + S4) / 2:</strong> <?= number_format($moyenneAnnee, 2) ?>/20
  </div>
<?php endif; ?>
