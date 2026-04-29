-- PHASE 0 & 1 - Création des tables

DROP DATABASE gestion_notes;
CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- 1.1 Table semestre
CREATE TABLE semestre (
  id INT PRIMARY KEY 
);
INSERT INTO semestre VALUES (3), (4);

-- 1.2 Table option
CREATE TABLE `option` (
  id INT PRIMARY KEY ,
  label VARCHAR(50) NOT NULL
);
INSERT INTO `option` (id, label) VALUES (0, 'aucun');
INSERT INTO `option` (id,label) VALUES (1,'dev'), (2,'bddres'), (3,'web');

-- 1.3 Table user
CREATE TABLE user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  mdp VARCHAR(255) NOT NULL
);
INSERT INTO user (nom, mdp) VALUES ('admin', '1234');

-- 1.4 Table etudiant
CREATE TABLE etudiant (
  id VARCHAR(10) PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
);
INSERT INTO etudiant VALUES ('003469', 'Rakoto Jean'), ('003470', 'Rabe Marie');

-- 1.5 Table matiere
CREATE TABLE matiere (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero VARCHAR(20),
  nom VARCHAR(100) NOT NULL,
  coef FLOAT NOT NULL DEFAULT 1,
  id_semestre INT NOT NULL,
  optional TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (id_semestre) REFERENCES semestre(id)
);

-- Matières réelles issues du programme L2 Informatique
-- Les crédits ECTS sont utilisés comme coefficients

-- ── S3 : tronc commun ── (id 1 → 6, toutes optional=0)
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES 
('INF201', 'Programmation orientée objet',   6, 3, 0),  -- id=1
('INF202', 'Bases de données objets',        6, 3, 0),  -- id=2
('INF203', 'Programmation système',          4, 3, 0),  -- id=3
('INF208', 'Réseaux informatiques',          6, 3, 0),  -- id=4
('MTH201', 'Méthodes numériques',            4, 3, 0),  -- id=5
('ORG201', 'Bases de gestion',               4, 3, 0);  -- id=6

-- ── S4 : matières obligatoires par parcours ── (id 7 → 13, optional=0)
-- Note : INF207 est obligatoire pour Dev ; INF205 est obligatoire pour BddRes.
-- Ces matières apparaissent aussi comme choix optionnel dans d'autres parcours
-- mais le schéma actuel les traite globalement : elles sont donc exclues
-- des groupes "1 UE parmi" pour éviter une incohérence.
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES 
('INF207', 'Eléments d''algorithmique',       6, 4, 0),  -- id=7  (oblig. Dev)
('INF210', 'Mini-projet de développement',  10, 4, 0),  -- id=8  (oblig. Dev)
('INF205', 'Système d''information',          6, 4, 0),  -- id=9  (oblig. BddRes)
('INF211', 'Mini-projet BDD/Réseaux',       10, 4, 0),  -- id=10 (oblig. BddRes)
('INF209', 'Web dynamique',                   6, 4, 0),  -- id=11 (oblig. Web)
('INF212', 'Mini-projet Web et design',     10, 4, 0),  -- id=12 (oblig. Web)
('MTH203', 'MAO',                             4, 4, 0);  -- id=13 (oblig. tous parcours S4)

-- ── S4 : matières optionnelles "1 UE parmi" ── (id 14 → 19, optional=1)
-- Groupe INF  : 1 parmi {INF204, INF206}  → 6 crédits
-- Groupe MTH  : selon parcours             → 4 crédits
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES 
('INF204', 'Système d''information géographique', 6, 4, 1),  -- id=14
('INF206', 'Interface Homme/Machine',             6, 4, 1),  -- id=15
('MTH202', 'Analyse des données',                 4, 4, 1),  -- id=16
('MTH204', 'Géométrie',                           4, 4, 1),  -- id=17
('MTH205', 'Equations différentielles',           4, 4, 1),  -- id=18
('MTH206', 'Optimisation',                        4, 4, 1);  -- id=19

-- 1.6 Table matiere_semestre
CREATE TABLE matiere_semestre (
  id_semestre INT NOT NULL,
  id_matiere INT NOT NULL,
  id_option INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id_semestre, id_matiere, id_option),
  FOREIGN KEY (id_semestre) REFERENCES semestre(id),
  FOREIGN KEY (id_matiere) REFERENCES matiere(id),
  FOREIGN KEY (id_option) REFERENCES `option`(id)
);

-- ── S3 : tronc commun (option=0 = aucun) ──
INSERT INTO matiere_semestre (id_semestre, id_matiere, id_option) VALUES 
(3, 1, 0),  -- INF201 Programmation orientée objet
(3, 2, 0),  -- INF202 Bases de données objets
(3, 3, 0),  -- INF203 Programmation système
(3, 4, 0),  -- INF208 Réseaux informatiques
(3, 5, 0),  -- MTH201 Méthodes numériques
(3, 6, 0);  -- ORG201 Bases de gestion

-- ── S4 Parcours Développement (option=1) ──
-- Obligatoires : INF207(7), INF210(8), MTH203(13)
-- 1 parmi INF (optionnel) : INF204(14), INF206(15)
-- 1 parmi MTH (optionnel) : MTH204(17), MTH205(18), MTH206(19)
INSERT INTO matiere_semestre (id_semestre, id_matiere, id_option) VALUES 
(4,  7, 1),  -- INF207 Eléments d'algorithmique    [obligatoire]
(4,  8, 1),  -- INF210 Mini-projet développement    [obligatoire]
(4, 13, 1),  -- MTH203 MAO                          [obligatoire]
(4, 14, 1),  -- INF204 SIG                          [1 parmi INF]
(4, 15, 1),  -- INF206 Interface Homme/Machine      [1 parmi INF]
(4, 17, 1),  -- MTH204 Géométrie                   [1 parmi MTH]
(4, 18, 1),  -- MTH205 Equations différentielles    [1 parmi MTH]
(4, 19, 1);  -- MTH206 Optimisation                 [1 parmi MTH]

-- ── S4 Parcours Bases de Données et Réseaux (option=2) ──
-- Obligatoires : INF205(9), INF211(10), MTH203(13)
-- 1 parmi INF (optionnel) : INF204(14), INF206(15)
-- 1 parmi MTH (optionnel) : MTH202(16), MTH205(18), MTH206(19)
INSERT INTO matiere_semestre (id_semestre, id_matiere, id_option) VALUES 
(4,  9, 2),  -- INF205 Système d'information        [obligatoire]
(4, 10, 2),  -- INF211 Mini-projet BDD/Réseaux      [obligatoire]
(4, 13, 2),  -- MTH203 MAO                          [obligatoire]
(4, 14, 2),  -- INF204 SIG                          [1 parmi INF]
(4, 15, 2),  -- INF206 Interface Homme/Machine      [1 parmi INF]
(4, 16, 2),  -- MTH202 Analyse des données          [1 parmi MTH]
(4, 18, 2),  -- MTH205 Equations différentielles    [1 parmi MTH]
(4, 19, 2);  -- MTH206 Optimisation                 [1 parmi MTH]

-- ── S4 Parcours Web et Design (option=3) ──
-- Obligatoires : INF209(11), INF212(12), MTH203(13)
-- 1 parmi INF (optionnel) : INF204(14), INF206(15)
-- 1 parmi MTH (optionnel) : MTH202(16), MTH204(17), MTH206(19)
INSERT INTO matiere_semestre (id_semestre, id_matiere, id_option) VALUES 
(4, 11, 3),  -- INF209 Web dynamique                [obligatoire]
(4, 12, 3),  -- INF212 Mini-projet Web et design    [obligatoire]
(4, 13, 3),  -- MTH203 MAO                          [obligatoire]
(4, 14, 3),  -- INF204 SIG                          [1 parmi INF]
(4, 15, 3),  -- INF206 Interface Homme/Machine      [1 parmi INF]
(4, 16, 3),  -- MTH202 Analyse des données          [1 parmi MTH]
(4, 17, 3),  -- MTH204 Géométrie                   [1 parmi MTH]
(4, 19, 3);  -- MTH206 Optimisation                 [1 parmi MTH]

-- 1.7 Table note
CREATE TABLE note (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_etu VARCHAR(10) NOT NULL,
  id_matiere INT NOT NULL,
  note FLOAT NOT NULL,
  FOREIGN KEY (id_etu) REFERENCES etudiant(id),
  FOREIGN KEY (id_matiere) REFERENCES matiere(id)
);

-- PHASE 2 - Vues (Calcul des moyennes & notes)

-- 2.1 v_note_par_option
-- Logique: on retourne le MAX de la note par étudiant et par matière non optionnelle.
-- Pour les optionnelles, on récupère le maximum dans tout le bloc optionnel.
CREATE OR REPLACE VIEW v_note_par_option AS
SELECT 
    n.id_etu,
    MAX(n.note) as note,
    n.id_matiere,
    m.coef,
    ms.id_option
FROM note n
JOIN matiere m ON n.id_matiere = m.id
JOIN matiere_semestre ms ON m.id = ms.id_matiere
WHERE m.optional = 0
GROUP BY n.id_etu, n.id_matiere, m.coef, ms.id_option
UNION ALL
SELECT 
    sub.id_etu,
    sub.max_note as note,
    sub.id_matiere,
    sub.coef,
    sub.id_option
FROM (
    SELECT 
        n.id_etu, 
        MAX(n.note) as max_note,
        n.id_matiere,
        m.coef,
        ms.id_option,
        RANK() OVER (PARTITION BY n.id_etu, ms.id_option ORDER BY MAX(n.note) DESC, n.id_matiere ASC) as rnk
    FROM note n
    JOIN matiere m ON n.id_matiere = m.id
    JOIN matiere_semestre ms ON m.id = ms.id_matiere
    WHERE m.optional = 1
    GROUP BY n.id_etu, n.id_matiere, m.coef, ms.id_option
) sub
WHERE sub.rnk = 1;


-- 2.2 v_moyenne_par_option
-- Somme(note*coef)/Somme(coef) avec regroupement par etudiant et par option + semestre
CREATE OR REPLACE VIEW v_moyenne_par_option AS
SELECT 
    v.id_etu,
    SUM(v.note * v.coef) / SUM(v.coef) as moyenne,
    SUM(v.coef) as coef_total,
    v.id_option,
    m.id_semestre
FROM v_note_par_option v
JOIN matiere m ON v.id_matiere = m.id
GROUP BY v.id_etu, v.id_option, m.id_semestre;


-- 2.3 v_moyenne_annee_par_option
-- Moyenne (S3+S4) / 2
CREATE OR REPLACE VIEW v_moyenne_annee_par_option AS 
SELECT 
    id_etu,
    id_option,
    AVG(moyenne) as moyenne_annuelle
FROM v_moyenne_par_option
GROUP BY id_etu, id_option;