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

-- Insertion de matières fictives pour tester S3 et S4
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES 
('INF301', 'Algorithmique', 4, 3, 0),
('INF302', 'Bases de données', 4, 3, 0),
('INF401', 'Développement Web', 3, 4, 0),
('INF402', 'Administration Systèmes', 3, 4, 0),
('OPT410', 'Programmation Mobile', 2, 4, 1),
('OPT411', 'Intelligence Artificielle', 2, 4, 1),
('OPT412', 'Big Data', 2, 4, 1);

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

-- Associations (fictives)
INSERT INTO matiere_semestre (id_semestre, id_matiere, id_option) VALUES 
(4, 3, 1), (4, 3, 2), (4, 3, 3),
(4, 4, 1), (4, 4, 2), (4, 4, 3),
(4, 5, 1), (4, 6, 2), (4, 7, 3);

INSERT INTO matiere_semestre (id_semestre, id_matiere, id_option) VALUES 
(3, 1, 0),
(3, 2, 0);

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