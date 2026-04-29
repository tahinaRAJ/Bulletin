# TP CodeIgniter 4 - Todo

## Objectif

- [x] Realiser une application de gestion de livres et d'emprunts avec CodeIgniter 4
- [x] Respecter une structure MVC simple conforme au sujet

## Model, Service

- [x] Configurer la connexion base de donnees dans app/Config/Database.php
  - [x] Base cible: bibliotheque
  - [x] Driver: MySQLi

- [x] Creer les tables SQL
  - [x] Table livres
    - [x] id auto-incremente
    - [x] titre
    - [x] auteur
    - [x] isbn unique
    - [x] annee_publication
    - [x] categorie
    - [x] resume
    - [x] couverture
    - [x] statut (disponible / prete)
    - [x] created_at, updated_at
  - [x] Table emprunts
    - [x] id auto-incremente
    - [x] livre_id
    - [x] nom_emprunteur
    - [x] date_emprunt
    - [x] date_retour nullable
    - [x] Cle etrangere vers livres(id) avec on update/on delete cascade

- [x] Creer les modeles
  - [x] LivreModel
    - [x] Table, cle primaire, allowedFields
    - [x] Timestamps automatiques
    - [x] Regles de validation
    - [x] Messages d'erreur en francais
    - [x] Validation metier: annee non future
    - [x] Fonction rechercher(motCle, categorie)
    - [x] Pagination (10 par page)
  - [x] EmpruntModel
    - [x] Table et allowedFields
    - [x] Fonction getDernierEmpruntPourLivre(livreId)

- [x] Creer les controleurs
  - [x] LibraryController
    - [x] Liste des livres
    - [x] Formulaire d'ajout
    - [x] Enregistrement d'un livre
    - [x] Detail d'un livre
    - [x] Suppression d'un livre
    - [x] Recuperation des categories
  - [x] MouvementController
    - [x] Emprunter un livre
    - [x] Rendre un livre
    - [x] Verifier l'existence du livre
    - [x] Verifier le statut disponible/prete
    - [x] Inserer emprunt + mise a jour statut
    - [x] Mettre a jour la date_retour de l'emprunt actif

- [x] Creer les routes
  - [x] Route liste livres
  - [x] Route detail livre
  - [x] Route formulaire ajout
  - [x] Route POST ajout livre
  - [x] Route POST suppression livre
  - [x] Route POST emprunter
  - [x] Route POST rendre

- [x] Creer les vues
  - [x] Layout principal (navbar + flash + section content)
  - [x] Vue index catalogue
    - [x] Formulaire de recherche
    - [x] Tableau des livres
    - [x] Statut colore
    - [x] Actions emprunt/rendu/suppression
    - [x] Pagination
  - [x] Vue detail
    - [x] Infos completes du livre
    - [x] Dernier emprunteur
    - [x] Date du dernier emprunt
  - [x] Vue formulaire ajout
    - [x] Champs du formulaire
    - [x] old() pour repopuler
    - [x] Gestion des erreurs
    - [x] CSRF
    - [x] Limite annee cote client

- [x] Securite
  - [x] Activer le filtre CSRF global
  - [x] Ajouter csrf_field() dans tous les formulaires POST
  - [x] Echappement des sorties avec esc()

## Validation et debug

- [x] Verifier les erreurs dans writable/logs
- [x] Corriger l'erreur historique Etudiant_Liste.php (module hors sujet supprime)
- [x] Corriger la connexion a la base inconnue bibliotheque
- [x] Corriger la syntaxe SQL de la cle etrangere de emprunts

## TP CodeIgniter 4 - Suite

### Authentification et autorisation

#### Infrastructure
- [x] Creation de AuthFilter (verifier l'authentification)
- [x] Creation de RoleFilter (verifier le rôle utilisateur)
- [x] Déclaration des filtres dans app/Config/Filters.php
- [x] Table users avec champs: id, email, password, role (utilisateur|bibliothécaire|admin), created_at, updated_at
- [x] UserModel avec validations (mot de passe en clair)
- [x] AuthController
  - [x] Formulaire de connexion
  - [x] Verifier les credentials
  - [x] Créer la session
  - [x] Formulaire d'inscription
  - [x] Créer nouvel utilisateur
  - [x] Deconnexion

#### Routes protégées
- [x] GET /auth/login - formulaire connexion
- [x] POST /auth/login - traiter connexion
- [x] GET /auth/register - formulaire inscription
- [x] POST /auth/register - traiter inscription
- [x] GET /auth/logout - deconnexion
- [x] GET /livres/new - pages ajout (bibliothécaire + admin uniquement)
- [x] POST /livres - enregistrement livre (bibliothécaire + admin uniquement)
- [x] POST /livres/(:num)/delete - suppression (bibliothécaire + admin uniquement)

### Gestion avancée des emprunts

#### Schema de donnees
- [x] Ajouter colonnes a `emprunts`
  - [x] date_retour_prevue (date calculée + X jours)
  - [x] nombre_jours_emprunt (parametre config, ex: 15)
  - [x] statut (actif|retourne|retard)
  - [x] jours_retard (calculé si retard)
- [x] Table reservations avec
  - [x] id, livre_id, user_id, date_reservation, position_file
  - [x] Clé étrangère vers livres et users

#### Modèles
- [x] EmpruntModel
  - [x] Calculer date_retour_prevue automatiquement
  - [x] Methode getEmpruntEnRetard() - liste des emprunts non retournés avec date_retour_prevue depassée
  - [x] Methode getHistoriqueParLivre(livreId)
  - [x] Methode getHistoriqueParEmprunteur(userId)
  - [x] Methode marquerCommeRetourne(empruntId)
- [x] ReservationModel
  - [x] Creer reservation
  - [x] Recuperer file d'attente pour un livre
  - [x] Notifier prochain utilisateur quand livre revient disponible

#### Controleurs
- [x] MouvementController - enrichir
  - [x] returnBook() - calculer retard et mettre a jour statut
  - [x] loanBook() - creer rendu avec date_retour_prevue
- [x] EmpruntController - nouveau
  - [x] historiquePourUtilisateur(userId) - emprunts personnels
  - [x] detailsEmprunt(empruntId) - voir les details
- [x] ReservationController - nouveau
  - [x] creerReservation(livreId) - creer une reservation
  - [x] annulerReservation(reservationId)
  - [x] maFileReservation() - mes reservations
- [x] AdminController - nouveau
  - [x] listeEmpruntEnRetard() - rapport retards
  - [x] envoiRelances() - notifier emprunteurs en retard

### Profil utilisateur
#### Vues
- [x] app/Views/emprunt/historique.php - historique personnel avec séparation actifs/passés
- [x] app/Views/emprunt/details.php - détails d'un emprunt
- [x] app/Views/reservation/list.php - mes réservations
- [x] app/Views/admin/dashboard.php - tableau de bord admin
- [x] app/Views/admin/emprunts_retard.php - rapport des emprunts en retard
- [x] app/Views/admin/reservations.php - gestion des réservations admin


- [x] Page de profil (/profile)
  - [x] Infos utilisateur
  - [x] Historique personnel de tous les emprunts
  - [x] Emprunts actifs en cours
  - [x] Emprunts en retard avec nombre de jours
  - [x] Mes reservations en cours

### Catalogue avancé

#### Schema de donnees
- [x] Table auteurs (separation de l'entité)
  - [x] id, nom, prenom, biographie, created_at, updated_at
- [x] Table livre_auteur (relation N:N)
  - [x] livre_id, auteur_id (cle composite ou des cles etrangeres)
- [x] Table notations
  - [x] id, livre_id, user_id, note (1-5), created_at
- [x] Table commentaires
  - [x] id, livre_id, user_id, texte, created_at, updated_at

#### Modèles
- [x] AuteurModel - gestion des auteurs
- [x] LivreModel - enrichir
  - [x] Relation avec auteurs (many-to-many)
  - [x] getNoeurstrait() - moyenne des notes
  - [x] getCommentairesRecents()
- [x] NotationModel
- [x] CommentaireModel

#### Controleurs
- [x] LibraryController - enrichir
  - [x] show() - ajouter notes et commentaires
  - [x] ajouterLivre() - selection multiple d'auteurs
- [x] NotationController - nouveau
  - [x] ajouter/modifier une note sur un livre
- [x] CommentaireController - nouveau
  - [x] ajouter un commentaire
  - [x] supprimer son propre commentaire
- [x] AuteurController - nouveau
  - [x] listeLivresParAuteur(auteurId)

#### Export
- [x] ExportController - nouveau
  - [x] exporterEnCSV() - telecharger catalogue en CSV
  - [x] exporterEnPDF() - telecharger catalogue en PDF (utiliser dompdf ou mpdf)

### IHM - Interactions avancées

- [x] Tri des colonnes du tableau
  - [x] Tri par titre (A-Z / Z-A)
  - [x] Tri par auteur (A-Z / Z-A)
  - [x] Tri par année (croissant / decroissant)
  - [x] Paramètres GET: sort=titre&order=asc
  - [x] Icones ou chevrons pour indiquer l'ordre

### Statistiques - Tableau de bord admin

- [x] Route /admin/dashboard (admin only)
- [x] AdminDashboardController - nouveau
  - [x] Livres les plus empruntés (top 10)
  - [x] Emprunteurs les plus actifs (top 10)
  - [x] Total emprunts ce mois
  - [x] Taux de retard
  - [x] Reservations en attente
- [x] Vue dashboard avec
  - [x] Graphiques (utiliser Chart.js ou similar)
  - [x] Statistiques en cards
  - [x] Listes de top emprunteurs/livres 

