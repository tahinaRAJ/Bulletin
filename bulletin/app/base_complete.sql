-- ============================================================================
-- GESTION DES NOTES - SCHEMA COMPLET
-- Système de gestion des notes pour L2, S3 et S4
-- ============================================================================

-- PHASE 1: Création des tables de base

-- Table: semestre (S3=1, S4=2, L2=3 ou combinaison)
CREATE TABLE IF NOT EXISTS semestre (
  id INT PRIMARY KEY AUTO_INCREMENT,
  label VARCHAR(50) NOT NULL
);

INSERT IGNORE INTO semestre (id, label) VALUES 
(1, 'S3'),
(2, 'S4'),
(3, 'L2');

-- Table: option (Dev, BddRes, Web)
CREATE TABLE IF NOT EXISTS option (
  id INT PRIMARY KEY AUTO_INCREMENT,
  label VARCHAR(50) NOT NULL UNIQUE
);

INSERT IGNORE INTO option (label) VALUES 
('dev'),
('bddres'),
('web');

-- Table: user (pour l'authentification)
CREATE TABLE IF NOT EXISTS user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL UNIQUE,
  mdp VARCHAR(255) NOT NULL
);

INSERT IGNORE INTO user (nom, mdp) VALUES 
('admin', '1234');

-- Table: etudiant
CREATE TABLE IF NOT EXISTS etudiant (
  id VARCHAR(10) PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
);

INSERT IGNORE INTO etudiant (id, nom) VALUES 
('003469', 'Rakoto Jean'),
('003470', 'Rabe Marie');

-- Table: matiere (avec coef et semestre)
CREATE TABLE IF NOT EXISTS matiere (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero INT NOT NULL,
  nom VARCHAR(100) NOT NULL,
  coef DECIMAL(5,2) NOT NULL,
  id_semestre INT NOT NULL,
  optional TINYINT(1) DEFAULT 0,
  FOREIGN KEY (id_semestre) REFERENCES semestre(id)
);

-- Insertions de matières S3 (non optionnelles)
INSERT IGNORE INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(1, 'PHP Avancé', 3, 1, 0),
(2, 'Base de Données', 4, 1, 0),
(3, 'Web Dynamique', 3, 1, 0),
(4, 'Système d\'Exploitation', 2, 1, 0),
(5, 'Anglais', 1, 1, 0);

-- Insertions de matières S4 (mix optionnelles et non optionnelles)
-- Non optionnelles pour tous
INSERT IGNORE INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(6, 'Framework Web', 3, 2, 0),
(7, 'API REST', 3, 2, 0),
(8, 'Sécurité', 2, 2, 0);

-- Optionnelles (une par option)
INSERT IGNORE INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(9, 'DevOps & CI/CD', 2, 2, 1),      -- Dev
(10, 'Optimisation SQL', 2, 2, 1),   -- BddRes
(11, 'UX/UI Design', 2, 2, 1);       -- Web

-- Table: note
CREATE TABLE IF NOT EXISTS note (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_etu VARCHAR(10) NOT NULL,
  id_matiere INT NOT NULL,
  note DECIMAL(5,2) NOT NULL,
  FOREIGN KEY (id_etu) REFERENCES etudiant(id),
  FOREIGN KEY (id_matiere) REFERENCES matiere(id),
  CHECK (note >= 0 AND note <= 20)
);

-- ============================================================================
-- PHASE 2: Création des vues SQL
-- ============================================================================

-- Vue: v_note_par_option
-- Logique:
-- - Pour chaque (id_etu, id_option), retourner MAX(note) par matière non optionnelle
-- - Pour les matières optionnelles: prendre la matière avec la note la plus haute
-- - Pour S3: pas d'option (NULL)
-- - Pour S4: group by option
DROP VIEW IF EXISTS v_note_par_option;
CREATE VIEW v_note_par_option AS
SELECT 
  n.id_etu,
  n.note,
  n.id_matiere,
  m.coef,
  CASE 
    WHEN m.id_semestre = 1 THEN NULL  -- S3: pas d'option
    WHEN m.optional = 0 THEN NULL      -- Matière non optionnelle: pas d'option
    ELSE o.id                           -- Matière optionnelle: option id
  END AS id_option,
  o.label AS option_label,
  m.id_semestre,
  m.nom AS matiere_nom,
  m.optional
FROM note n
JOIN matiere m ON n.id_matiere = m.id
LEFT JOIN option o ON m.id = (
  -- Récupérer la matière optionnelle avec la meilleure note pour cette option
  SELECT m2.id 
  FROM matiere m2
  LEFT JOIN note n2 ON n2.id_matiere = m2.id AND n2.id_etu = n.id_etu
  WHERE m2.optional = 1 
    AND m2.id_semestre = m.id_semestre
    AND m2.numero IN (9, 10, 11)  -- Les options dev, bddres, web
  ORDER BY n2.note DESC
  LIMIT 1
) 
WHERE n.note = (
  -- Prendre la note MAX pour chaque (id_etu, id_matiere)
  SELECT MAX(n2.note) 
  FROM note n2 
  WHERE n2.id_etu = n.id_etu AND n2.id_matiere = n.id_matiere
);

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

-- ============================================================================
-- Alternativement, une vue simple pour les notes par semestre
-- ============================================================================
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
  MAX(n.note) AS note_max,
  COUNT(n.id) AS nb_notes
FROM etudiant e
LEFT JOIN note n ON e.id = n.id_etu
LEFT JOIN matiere m ON n.id_matiere = m.id
GROUP BY e.id, e.nom, m.id, m.nom, m.numero, m.coef, m.id_semestre, m.optional;
