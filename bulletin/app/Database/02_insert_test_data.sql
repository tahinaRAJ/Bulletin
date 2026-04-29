-- ============================================================================
-- GESTION DES NOTES - PHASE 2 : DONNÉES DE TEST
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- Insérer les semestres
INSERT INTO semestre (id, label) VALUES 
(1, 'S3'),
(2, 'S4'),
(3, 'L2');

-- Insérer les options
INSERT INTO option (label) VALUES 
('dev'),
('bddres'),
('web');

-- Insérer les utilisateurs (admin)
INSERT INTO user (nom, mdp) VALUES 
('admin', '1234');

-- Insérer les étudiants de test
INSERT INTO etudiant (id, nom) VALUES 
('003469', 'Rakoto Jean'),
('003470', 'Rabe Marie'),
('003471', 'Rakoto Tiana'),
('003472', 'Razafindra Paul'),
('003473', 'Rakoto Hery');



-- Insérer les matières S3 (non optionnelles)
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(1, 'PHP Avancé', 3, 1, 0),
(2, 'Base de Données', 4, 1, 0),
(3, 'Web Dynamique', 3, 1, 0),
(4, 'Système d Exploitation', 2, 1, 0),
(5, 'Anglais', 1, 1, 0);

-- Insérer les matières S4 (non optionnelles pour tous)
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(6, 'Framework Web', 3, 2, 0),
(7, 'API REST', 3, 2, 0),
(8, 'Sécurité', 2, 2, 0);

-- Insérer les matières S4 optionnelles (une par option)
INSERT INTO matiere (numero, nom, coef, id_semestre, optional) VALUES
(9, 'DevOps & CI/CD', 2, 2, 1),      -- Dev
(10, 'Optimisation SQL', 2, 2, 1),   -- BddRes
(11, 'UX/UI Design', 2, 2, 1);       -- Web

-- ============================================================================
-- FIN PHASE 2 : DONNÉES DE TEST INSÉRÉES
-- ============================================================================
