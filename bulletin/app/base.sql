DROP DATABASE IF EXISTS bibliotheque;
CREATE DATABASE bibliotheque;
USE bibliotheque;

CREATE TABLE auteur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE livre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    id_auteur INT NOT NULL,
    id_categorie INT NOT NULL,
    CONSTRAINT fk_livre_auteur
        FOREIGN KEY (id_auteur) REFERENCES auteur(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_livre_categorie
        FOREIGN KEY (id_categorie) REFERENCES categorie(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- Cette table enregistre chaque mouvement du livre: EMPRUNT ou RETOUR.
-- Pas de table emprunteur: on stocke seulement le nom saisi.
CREATE TABLE mouvement_livre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_livre INT NOT NULL,
    type_mouvement ENUM('EMPRUNT', 'RETOUR') NOT NULL,
    nom_emprunteur VARCHAR(100) NULL,
    date_mouvement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mouvement_livre
        FOREIGN KEY (id_livre) REFERENCES livre(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);