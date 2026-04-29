-- ============================================================================
-- GESTION DES NOTES - PHASE 3 : VUES SQL
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- Vue: v_note_par_option
-- Logique:
-- - Pour chaque (id_etu, id_option), retourner MAX(note) par matière non optionnelle
-- - Pour les matières optionnelles: prendre la matière avec la note la plus haute
-- - Pour S3: pas d'option (NULL)
-- - Pour S4: group by option
DROP VIEW IF EXISTS v_note_par_option;
CREATE VIEW v_note_par_option AS
SELECT 
  nmax.id,
  e.id AS id_etu,
  COALESCE(nmax.note, 0) AS note,
  m.id AS id_matiere,
  m.coef,
  CASE 
    WHEN m.id_semestre = 1 THEN NULL  -- S3: pas d'option
    WHEN m.optional = 0 THEN NULL      -- Matière non optionnelle: pas d'option
    ELSE o.id                           -- Matière optionnelle: option id
  END AS id_option,
  o.label AS option_label,
  m.id_semestre,
  m.nom AS matiere_nom,
  m.numero,
  m.optional
FROM etudiant e
CROSS JOIN matiere m
LEFT JOIN (
  SELECT n1.id, n1.id_etu, n1.id_matiere, n1.note
  FROM note n1
  INNER JOIN (
    SELECT id_etu, id_matiere, MAX(note) AS max_note
    FROM note
    GROUP BY id_etu, id_matiere
  ) nmax_note ON nmax_note.id_etu = n1.id_etu
    AND nmax_note.id_matiere = n1.id_matiere
    AND nmax_note.max_note = n1.note
) nmax ON nmax.id_matiere = m.id AND nmax.id_etu = e.id
LEFT JOIN option o ON (
  (m.numero = 9 AND o.label = 'dev') OR
  (m.numero = 10 AND o.label = 'bddres') OR
  (m.numero = 11 AND o.label = 'web')
)
;

-- Vue: v_moyenne_par_option
-- Logique: Moyennes pondérées par option (ou NULL pour S3)
DROP VIEW IF EXISTS v_moyenne_par_option;
CREATE VIEW v_moyenne_par_option AS
SELECT 
  id_etu,
  id_option,
  option_label,
  id_semestre,
  CASE 
    WHEN SUM(coef) = 0 THEN 0
    ELSE ROUND(SUM(note * coef) / SUM(coef), 2)
  END AS moyenne,
  SUM(coef) AS coef_total
FROM v_note_par_option
GROUP BY id_etu, id_option, option_label, id_semestre;

-- Vue: v_moyenne_annee_par_option (L2)
-- Logique: (moy_S3 + moy_S4) / 2 pour chaque option
DROP VIEW IF EXISTS v_moyenne_annee_par_option;
CREATE VIEW v_moyenne_annee_par_option AS
SELECT 
  mp1.id_etu,
  mp1.id_option,
  mp1.option_label,
  ROUND((mp1.moyenne + COALESCE(mp2.moyenne, 0)) / 2, 2) AS moyenne_annee,
  mp1.moyenne AS moyenne_s3,
  COALESCE(mp2.moyenne, 0) AS moyenne_s4
FROM v_moyenne_par_option mp1
LEFT JOIN v_moyenne_par_option mp2 ON mp1.id_etu = mp2.id_etu 
  AND mp1.id_option = mp2.id_option 
  AND mp2.id_semestre = 2
WHERE mp1.id_semestre = 1;

-- Vue: v_notes_etudiant
-- Vue simple pour afficher les notes par étudiant et semestre
DROP VIEW IF EXISTS v_notes_etudiant;
CREATE VIEW v_notes_etudiant AS
SELECT 
  e.id,
  e.nom,
  m.id AS id_matiere,
  m.nom AS matiere_nom,
  m.numero,
  m.coef,
  m.id_semestre,
  m.optional,
  COALESCE(MAX(n.note), 0) AS note_max,
  COUNT(n.id) AS nb_notes
FROM etudiant e
LEFT JOIN matiere m ON 1 = 1
LEFT JOIN note n ON e.id = n.id_etu AND n.id_matiere = m.id
GROUP BY e.id, e.nom, m.id, m.nom, m.numero, m.coef, m.id_semestre, m.optional;

-- ============================================================================
-- FIN PHASE 3 : VUES CRÉÉES
-- ============================================================================
