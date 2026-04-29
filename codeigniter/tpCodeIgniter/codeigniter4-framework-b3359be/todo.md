## Bibliothèque

### 1. Base de donnees

- [x] Creer les migrations CodeIgniter pour: `auteur`, `categorie`, `livre`, `mouvement_livre`
- [x] Ajouter les cles etrangeres dans la migration de `livre` et `mouvement_livre`
- [x] Creer des seeders de base (auteurs, categories, livres)
- [ ] Verifier que `spark migrate` puis `spark db:seed` passent sans erreur

### 2. Models

- [x] Creer `AuteurModel`, `CategorieModel`, `LivreModel`, `MouvementLivreModel`
- [x] Configurer `table`, `primaryKey`, `allowedFields`, `returnType`
- [x] Ajouter dans `LivreModel` une methode pour lister les livres avec auteur + categorie
- [x] Ajouter dans `MouvementLivreModel` une methode pour recuperer le dernier mouvement d'un livre
- [x] Ajouter une methode qui donne l'etat courant d'un livre: `DISPONIBLE` ou `EMPRUNTE`

### 3. Controllers

- [x] Creer `LivresController` (liste, details)
- [x] Creer `MouvementsController` (formulaire emprunt, formulaire retour)
- [x] Emprunt: verifier que le livre est disponible avant insertion du mouvement
- [x] Retour: verifier que le dernier mouvement est un emprunt avant insertion du retour
- [x] Afficher des messages flash success/erreur apres chaque action

### 4. Routes

- [x] Ajouter les routes GET/POST pour livres et mouvements
- [x] Proteger les routes POST avec le filtre CSRF
- [x] Definir une page d'accueil qui redirige vers la liste des livres

### 5. Validation

- [x] Regles: `nom_emprunteur` requis pour un emprunt, longueur min/max
- [x] Regles: `id_livre` requis et numerique
- [x] Centraliser les regles dans `app/Config/Validation.php` ou dans les controllers

### 6. Views (Bootstrap simple)

- [x] Vue liste livres (titre, auteur, categorie, etat actuel)
- [x] Vue details d'un livre + historique des mouvements
- [x] Formulaire emprunt (nom emprunteur)
- [x] Bouton/formulaire retour
- [x] Zone d'affichage des erreurs de validation et des flash messages

### 7. Historique et logique metier

- [x] Trier l'historique des mouvements par date decroissante
- [x] Interdire un double emprunt sans retour intermediaire
- [x] Permettre le retour meme si le nom n'est pas fourni

### 8. Tests minimum

- [x] Test modele: etat courant d'un livre selon les mouvements
- [ ] Test controller: emprunt refuse si livre deja emprunte
- [ ] Test controller: retour refuse si livre deja disponible

### 9. Finition

- [x] Nettoyer les noms de methodes et messages
- [x] Ajouter un README court (installation + commandes utiles)
- [ ] Verifier tout le flux: creer livre -> emprunter -> retourner -> historique




