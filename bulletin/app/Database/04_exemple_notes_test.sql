-- ============================================================================
-- GESTION DES NOTES - SCRIPT COMPLET D'INSTALLATION
-- À exécuter dans cet ordre:
-- 1. 01_create_tables.sql
-- 2. 02_insert_test_data.sql
-- 3. 03_create_views.sql
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- Exemple de données de test supplémentaires pour S3 (Rakoto Jean - ID: 003469)
-- À exécuter après avoir inséré les données de base

INSERT INTO note (id_etu, id_matiere, note) VALUES
-- Notes S3 pour Rakoto Jean (003469)
('003469', 1, 16),   -- PHP Avancé: 16/20
('003469', 2, 15),   -- Base de Données: 15/20
('003469', 3, 14),   -- Web Dynamique: 14/20
('003469', 4, 17),   -- Système d'Exploitation: 17/20
('003469', 5, 18),   -- Anglais: 18/20

-- Notes S4 pour Rakoto Jean (003469) - option Dev
('003469', 6, 16),   -- Framework Web: 16/20
('003469', 7, 15),   -- API REST: 15/20
('003469', 8, 14),   -- Sécurité: 14/20
('003469', 9, 17),   -- DevOps & CI/CD: 17/20

-- Notes S3 pour Rabe Marie (003470)
('003470', 1, 14),   -- PHP Avancé: 14/20
('003470', 2, 13),   -- Base de Données: 13/20
('003470', 3, 15),   -- Web Dynamique: 15/20
('003470', 4, 12),   -- Système d'Exploitation: 12/20
('003470', 5, 16),   -- Anglais: 16/20

-- Notes S4 pour Rabe Marie (003470) - option BddRes
('003470', 6, 14),   -- Framework Web: 14/20
('003470', 7, 13),   -- API REST: 13/20
('003470', 8, 12),   -- Sécurité: 12/20
('003470', 10, 18);  -- Optimisation SQL: 18/20

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

-- ============================================================================
-- EXEMPLE : Pour tester l'insertion de plusieurs notes pour une même matière
-- (la plus haute sera retenue)
-- ============================================================================

-- Le 5e étudiant (003473) est inscrit mais n'a aucune note.

-- ============================================================================
-- FIN DU SCRIPT D'INSTALLATION
-- ============================================================================
