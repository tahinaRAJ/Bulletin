create database bibliotheque character set utf8mb4 collate utf8mb4_general_ci;
use bibliotheque;

create table livres (

	id int unsigned auto_increment primary key,
	titre varchar(255) not null,
	auteur varchar(255) not null,
	isbn varchar(20) not null unique,
	annee_publication year not null,
	categorie varchar(100) not null,
	resume text,
	couverture varchar(255),
	statut enum('disponible', 'prete') not null default 'disponible',
	created_at timestamp not null default current_timestamp,
	updated_at timestamp not null default current_timestamp on update current_timestamp
);

create table emprunts (
    id int unsigned auto_increment primary key,
    livre_id int unsigned not null,
    nom_emprunteur varchar(255) not null,
    date_emprunt date not null,
    date_retour date null,
    constraint fk_emprunts_livre
        foreign key (livre_id) references livres(id)
        on update cascade
        on delete cascade
);

set foreign_key_checks = 0;
truncate table emprunts;
truncate table livres;
set foreign_key_checks = 1;

insert into livres (titre, auteur, isbn, annee_publication, categorie, resume, couverture, statut) values
('Le Petit Prince', 'Antoine de Saint-Exupery', '9782070612758', 1943, 'Classique', 'Un pilote rencontre un enfant venu d une autre planete.', null, 'disponible'),
('1984', 'George Orwell', '9780451524935', 1949, 'Dystopie', 'Un roman sur la surveillance, le controle et la manipulation.', null, 'prete'),
('L Etranger', 'Albert Camus', '9782070360024', 1942, 'Philosophie', 'Le destin de Meursault dans une Algerie solaire et derangeante.', null, 'disponible'),
('Notre-Dame de Paris', 'Victor Hugo', '9782253004226', 1931, 'Classique', 'Une fresque historique autour de la cathedrale de Paris.', null, 'prete'),
('Sapiens', 'Yuval Noah Harari', '9782081385928', 2015, 'Essai', 'Une histoire globale de l humanite, de ses origines a aujourd hui.', null, 'disponible');

insert into emprunts (livre_id, nom_emprunteur, date_emprunt, date_retour) values
(2, 'Amina Diallo', '2026-04-18', null),
(4, 'Mehdi Benali', '2026-04-10', null),
(1, 'Sarah Martin', '2026-03-22', '2026-04-02');