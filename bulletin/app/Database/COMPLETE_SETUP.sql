-- ============================================================================
-- GESTION DES NOTES - SCRIPT COMPLET EN UN SEUL FICHIER
-- Exécuter ce script pour créer toute la structure complète
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- ============================================================================
-- PHASE 1 : CRÉATION DES TABLES
-- ============================================================================

CREATE TABLE IF NOT EXISTS semestre (
  id INT PRIMARY KEY AUTO_INCREMENT,
  label VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS option (
  id INT PRIMARY KEY AUTO_INCREMENT,
  label VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL UNIQUE,
  mdp VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS etudiant (
  id VARCHAR(10) PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS matiere (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero INT NOT NULL,
  nom VARCHAR(100) NOT NULL,
  coef DECIMAL(5,2) NOT NULL,
  id_semestre INT NOT NULL,
  optional TINYINT(1) DEFAULT 0,
  FOREIGN KEY (id_semestre) REFERENCES semestre(id)
);

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
-- PHASE 2 : INSERTION DES DONNÉES DE BASE
-- ============================================================================

INSERT INTO semestre (id, label) VALUES 
(1, 'S3'),
(2, 'S4'),
(3, 'L2');

INSERT INTO option (label) VALUES 
('dev'),
('bddres'),
('web');

INSERT INTO user (nom, mdp) VALUES 
('admin', '1234');

INSERT INTO etudiant (id, nom) VALUES 
('003469', 'Rakoto Jean'),
('003470', 'Rabe Marie'),
('003471', 'Rakoto Tiana'),
('003472', 'Razafindra Paul'),
('003473', 'Rakoto Hery');

-- Matières S3 (5 obligatoires)
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(1, 'PHP Avancé', 3, 1, 0),
(2, 'Base de Données', 4, 1, 0),
(3, 'Web Dynamique', 3, 1, 0),
(4, 'Système d Exploitation', 2, 1, 0),
(5, 'Anglais', 1, 1, 0);

-- Matières S4 (3 obligatoires + 3 optionnelles)
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(6, 'Framework Web', 3, 2, 0),
(7, 'API REST', 3, 2, 0),
(8, 'Sécurité', 2, 2, 0),
(9, 'DevOps & CI/CD', 2, 2, 1),      -- Optionnelle Dev
(10, 'Optimisation SQL', 2, 2, 1),   -- Optionnelle BddRes
(11, 'UX/UI Design', 2, 2, 1);       -- Optionnelle Web

-- ============================================================================
-- PHASE 3 : CRÉATION DES VUES SQL
-- ============================================================================

DROP VIEW IF EXISTS v_note_par_option;
CREATE VIEW v_note_par_option AS
SELECT 
  n.id,
  n.id_etu,
  n.note,
  n.id_matiere,
  m.coef,
  CASE 
    WHEN m.id_semestre = 1 THEN NULL
    WHEN m.optional = 0 THEN NULL
    ELSE o.id
  END AS id_option,
  o.label AS option_label,
  m.id_semestre,
  m.nom AS matiere_nom,
  m.numero,
  m.optional
FROM note n
JOIN matiere m ON n.id_matiere = m.id
LEFT JOIN option o ON (
  m.numero = 9 AND o.label = 'dev' OR
  m.numero = 10 AND o.label = 'bddres' OR
  m.numero = 11 AND o.label = 'web'
)
WHERE n.note = (
  SELECT MAX(n2.note) 
  FROM note n2 
  WHERE n2.id_etu = n.id_etu AND n2.id_matiere = n.id_matiere
);

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

-- ============================================================================
-- PHASE 4 : DONNÉES DE TEST (OPTIONNEL)
-- Décommenter les lignes ci-dessous pour ajouter des notes de test
-- ============================================================================

-- Notes S3 pour Rakoto Jean (003469)
INSERT INTO note (id_etu, id_matiere, note) VALUES
('003469', 1, 16),   -- PHP Avancé: 16/20
('003469', 2, 15),   -- Base de Données: 15/20
('003469', 3, 14),   -- Web Dynamique: 14/20
('003469', 4, 17),   -- Système d'Exploitation: 17/20
('003469', 5, 18);   -- Anglais: 18/20

-- Notes S4 pour Rakoto Jean (003469) - option Dev
INSERT INTO note (id_etu, id_matiere, note) VALUES
('003469', 6, 16),   -- Framework Web: 16/20
('003469', 7, 15),   -- API REST: 15/20
('003469', 8, 14),   -- Sécurité: 14/20
('003469', 9, 17);   -- DevOps & CI/CD: 17/20

-- Notes S3 pour Rabe Marie (003470)
INSERT INTO note (id_etu, id_matiere, note) VALUES
('003470', 1, 14),   -- PHP Avancé: 14/20
('003470', 2, 13),   -- Base de Données: 13/20
('003470', 3, 15),   -- Web Dynamique: 15/20
('003470', 4, 12),   -- Système d'Exploitation: 12/20
('003470', 5, 16);   -- Anglais: 16/20

-- Notes S4 pour Rabe Marie (003470) - option BddRes
INSERT INTO note (id_etu, id_matiere, note) VALUES
('003470', 6, 14),   -- Framework Web: 14/20
('003470', 7, 13),   -- API REST: 13/20
('003470', 8, 12),   -- Sécurité: 12/20
('003470', 10, 18),  -- Optimisation SQL: 18/20

-- Notes S3 pour Rakoto Tiana (003471)
('003471', 1, 11),   -- PHP Avancé: 11/20
('003471', 2, 12),   -- Base de Données: 12/20
('003471', 3, 10),   -- Web Dynamique: 10/20
('003471', 4, 13),   -- Système d'Exploitation: 13/20
('003471', 5, 14),   -- Anglais: 14/20

-- Notes S4 pour Rakoto Tiana (003471) - option Web
('003471', 6, 12),   -- Framework Web: 12/20
('003471', 7, 11),   -- API REST: 11/20
('003471', 8, 13),   -- Sécurité: 13/20
('003471', 11, 15),  -- UX/UI Design: 15/20

-- Notes S3 pour Razafindra Paul (003472)
('003472', 1, 9),    -- PHP Avancé: 9/20
('003472', 2, 10),   -- Base de Données: 10/20
('003472', 3, 11),   -- Web Dynamique: 11/20
('003472', 4, 12),   -- Système d'Exploitation: 12/20
('003472', 5, 13),   -- Anglais: 13/20

-- Notes S4 pour Razafindra Paul (003472) - option Dev
('003472', 6, 10),   -- Framework Web: 10/20
('003472', 7, 11),   -- API REST: 11/20
('003472', 8, 12),   -- Sécurité: 12/20
('003472', 9, 14);   -- DevOps & CI/CD: 14/20

-- Le 5e étudiant (003473) est inscrit mais n'a aucune note.

-- ============================================================================
-- SCRIPT COMPLET TERMINÉ
-- La base est maintenant prête pour l'application CodeIgniter
-- ============================================================================

-- Vérification rapide (optionnel - à décommenter)
-- SELECT "Tables créées:" as status;
-- SELECT COUNT(*) as nombre_tables FROM information_schema.tables WHERE table_schema = DATABASE();
-- SELECT "Utilisateurs:" as status;
-- SELECT * FROM user;
-- SELECT "Étudiants:" as status;
-- SELECT * FROM etudiant;
-- SELECT "Matières:" as status;
-- SELECT COUNT(*) as total_matieres FROM matiere GROUP BY id_semestre;
