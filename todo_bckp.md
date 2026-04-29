## [] Base de données et Configuration
- [ ] [cite_start]Initialisation de la base de données **MySQL** [cite: 9]
- [ ] Création de la table **users** (Authentification)
    - `id` (Clé primaire, auto-incrément)
    - `username` (Nom d'utilisateur)
    - `password` 
- [ ] [cite_start]Création de la table **etudiants** [cite: 13]
    - `id_etudiant` (Clé primaire)
    - `nom`
    - `prenom`
    - [cite_start]`parcours` (Option : Dev, BDDRes, ou Web) [cite: 16, 17, 18]
- [ ] [cite_start]Création de la table **matieres** [cite: 44, 47, 49, 52]
    - `code_ue` (ex: INF201, INF210)
    - `intitule`
    - `credits`
    - `semestre` (3 ou 4)
- [ ] [cite_start]Création de la table **notes** [cite: 12]
    - `id_note`
    - `id_etudiant`
    - `code_ue`
    - `valeur_note`
- [ ] Configuration de CodeIgniter 4
    - [cite_start]Paramétrage du fichier `.env` (Identifiants MySQL) [cite: 9]
    - Définition des routes dans `Config/Routes.php`

## [] Design et Intégration
- [ ] [cite_start]Personnalisation du thème avec le fichier **SCSS** fourni [cite: 27]
    - Compilation du SCSS vers CSS
    - Liaison du fichier CSS dans le template principal
- [ ] Création du Layout (Modèles de vue)
    - [cite_start]Header (Logo et Navigation) [cite: 5]
    - [cite_start]Sidebar (Accès rapides : S3, S4, L2) [cite: 15, 22]
    - Footer

## [] Authentification et Login
- [ ] [cite_start]Création de la page de **Login** [cite: 11]
    - Adaptation du template au formulaire
    - [cite_start]Insertion des **valeurs par défaut** (admin/admin123) directement dans les `value` des inputs [cite: 11]
- [ ] Contrôleur Auth
    - Vérification des accès en base de données
    - Gestion de la session utilisateur

## [] Gestion des Étudiants et Notes
- [ ] [cite_start]Affichage de la **Liste des étudiants** [cite: 13]
    - Tableau avec nom, prénom et lien vers les notes
- [ ] [cite_start]Formulaire d'**Ajout de Note** [cite: 12]
    - Sélection de l'étudiant et de la matière
    - [cite_start]Possibilité de saisir plusieurs fois une note pour un même étudiant [cite: 12]
- [ ] [cite_start]Page des **Notes de l'étudiant** (au clic sur son nom) [cite: 14]
    - Affichage chronologique des notes saisies

## [] Logique Métier et Règles de Gestion
- [ ] [cite_start]Règle de la **Note Maximale** par matière [cite: 24]
    - Algorithme SQL/PHP pour ne retenir que la meilleure note pour une UE donnée
- [ ] [cite_start]Gestion des **Matières Optionnelles** [cite: 25]
    - [cite_start]Pour les groupes d'options (ex: S4), retenir l'UE ayant la meilleure note [cite: 25]
- [ ] [cite_start]Calculs de Moyennes [cite: 22]
    - [cite_start]Moyenne du **Semestre 3** [cite: 22, 29]
    - [cite_start]Moyenne du **Semestre 4** [cite: 22, 30]
    - [cite_start]Moyenne générale **L2** (Moyenne S3 + S4) [cite: 22, 33]

## [] Finalisation et Affichage
- [ ] [cite_start]Lien **S3 / S4** : Affichage des notes filtrées par semestre [cite: 22]
- [ ] [cite_start]Lien **L2** : Affichage complet (2 semestres + moyenne + mention) [cite: 22, 34, 35]
- [ ] [cite_start]Vérification finale de l'intégration design [cite: 5, 26]
- [ ] Nettoyage du code et suppression des fichiers inutiles