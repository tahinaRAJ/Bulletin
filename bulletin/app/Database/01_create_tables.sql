-- ============================================================================
-- GESTION DES NOTES - SCHEMA COMPLET - PHASE 1 : TABLES
-- Système de gestion des notes pour L2, S3 et S4
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- Table: semestre (S3=1, S4=2, L2=3 ou combinaison)
CREATE TABLE IF NOT EXISTS semestre (
  id INT PRIMARY KEY AUTO_INCREMENT,
  label VARCHAR(50) NOT NULL
);

-- Table: option (Dev, BddRes, Web)
CREATE TABLE IF NOT EXISTS option (
  id INT PRIMARY KEY AUTO_INCREMENT,
  label VARCHAR(50) NOT NULL UNIQUE
);

-- Table: user (pour l'authentification)
CREATE TABLE IF NOT EXISTS user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL UNIQUE,
  mdp VARCHAR(255) NOT NULL
);

-- Table: etudiant
CREATE TABLE IF NOT EXISTS etudiant (
  id VARCHAR(10) PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
);

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

-- Table: note
CREATE TABLE IF NOT EXISTS note (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_etu VARCHAR(10) NOT NULL,
  id_matiere INT NOT NULL,
  note DECIMAL(5,2) NOT NULL,
  FOREIGN KEY (id_etu) REFERENCES etudiant(id),
  FOREIGN KEY (id_matiere) REFERENCES matiere(id)
);

-- ============================================================================
-- FIN PHASE 1
-- ============================================================================
