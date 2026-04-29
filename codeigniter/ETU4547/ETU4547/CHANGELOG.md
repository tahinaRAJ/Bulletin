# CHANGELOG - Noyau d'Authentification

## Version 1.0.0 - Système d'Authentification et d'Autorisation (28/04/2026)

### ✨ Nouvelles fonctionnalités

#### Authentification
- ✅ **AuthFilter** - Middleware vérifiant la connexion utilisateur
- ✅ **RoleFilter** - Middleware vérifiant le rôle de l'utilisateur
- ✅ **AuthController** - Gestion login, registration, logout
- ✅ **UserModel** - Modèle utilisateur avec validations et hachage BCRYPT
- ✅ **Table `users`** - Migration créant la table avec contraintes

#### Routes protégées
- ✅ Public: `/auth/login`, `/auth/register`, `/livres` (consultation)
- ✅ Auth requis: `/livres/(:num)`, `/livres/(:num)/loan`, `/livres/(:num)/return`, `/profile`
- ✅ Role requis: `/livres/new`, POST `/livres`, POST `/livres/(:num)/delete` (admin, bibliothécaire)

#### Vues
- ✅ `auth/login.php` - Page de connexion responsive
- ✅ `auth/register.php` - Page d'inscription avec validation
- ✅ `user/profile.php` - Profil utilisateur et historique d'emprunts
- ✅ Navbar mis à jour - Navigation dynamique selon authentification

#### Données de test
- ✅ **UserSeeder** - 4 utilisateurs de test (admin, bibliothécaire, 2 utilisateurs)
- ✅ Credentials de test inclus dans `SETUP.md`

### 📋 Fichiers créés

**Controllers:**
- `app/Controllers/AuthController.php`
- `app/Controllers/UserController.php`

**Models:**
- `app/Models/UserModel.php`

**Filters:**
- `app/Filters/AuthFilter.php`
- `app/Filters/RoleFilter.php`

**Migrations:**
- `app/Database/Migrations/2026-04-28-000001_CreateUsersTable.php`

**Seeds:**
- `app/Database/Seeds/UserSeeder.php`

**Views:**
- `app/Views/auth/login.php`
- `app/Views/auth/register.php`
- `app/Views/user/profile.php`

**Documentation:**
- `SETUP.md` - Guide d'installation et configuration
- `ARCHITECTURE.md` - Documentation technique complète
- `CHANGELOG.md` - Ce fichier

### 🔧 Modifications

**Configuration:**
- `app/Config/Filters.php` - Ajout des aliases `auth` et `role`
- `app/Config/Routes.php` - Ajout des routes d'auth et protection des routes
- `app/Views/layouts/main.php` - Navbar dynamique selon authentification

### 🔒 Sécurité

- ✅ Hachage BCRYPT des mots de passe
- ✅ CSRF protection sur tous les formulaires
- ✅ Validation des données côté serveur
- ✅ Vérification d'authentification avant chaque action protégée
- ✅ Messages d'erreur génériques pour login échoué
- ✅ Destruction complète de session lors du logout

### 📊 Rôles et permissions

| Rôle | Permissions |
|------|------------|
| Public | Consulter catalogue |
| Utilisateur | Emprunter, rendre, voir profil |
| Bibliothécaire | + Ajouter/Supprimer livres |
| Admin | + Tableau de bord (futur) |

### 🚀 Quick Start

```bash
# 1. Installation
composer install

# 2. Migrations
php spark migrate

# 3. Seed (optionnel)
php spark db:seed UserSeeder

# 4. Serveur
php spark serve

# 5. Accès
# http://localhost:8080/auth/login
# admin@bibliotheque.fr / password123
```

### 📝 Cas d'utilisation couverts

1. **Inscription** - Un nouvel utilisateur s'inscrit (rôle: utilisateur)
2. **Connexion** - Activation de session avec `email` et `password`
3. **Consultation catalogue** - Disponible sans connexion
4. **Emprunt de livre** - Nécessite connexion + livre disponible
5. **Profil utilisateur** - Historique personnel des emprunts
6. **Ajout de livre** - Réservé aux bibliothécaires et admins
7. **Suppression de livre** - Réservé aux bibliothécaires et admins

### 📚 Dépendances

- **CodeIgniter 4** (framework)
- **Spark CLI** (commandes)
- **MySQLi** (base de données)
- **BCrypt** (natif PHP)

### 🔄 Intégration avec module existant

L'authentification s'intègre avec:
- `LibraryController` - Catalogue (routes protégées)
- `MouvementController` - Emprunts (routes protégées)
- `EmpruntModel` - Historique utilisateur

### ⚠️ Limitations connues

1. **Session HTTP seulement** - Pas de JWT/token API
2. **Pas de 2FA** - À implémenter
3. **Pas de rate limiting** - À implémenter
4. **Logout sur inactivité** - À implémenter
5. **Audit logging** - À implémenter

### 🔮 Prochaines étapes (À faire)

Voir `todo.md` pour la feuille de route complète:
- [ ] Gestion avancée des emprunts (retards, réservations)
- [ ] Catalogue avancé (auteurs, notations, commentaires)
- [ ] Tableau de bord admin (statistiques)
- [ ] Export CSV/PDF
- [ ] Tri des colonnes
- [ ] 2FA et rate limiting

### 👨‍💻 Auteur

Développé selon le sujet du TP et le PDF tutoriel `CI4-Middleware-Auth-Roles.md`

### 📅 Date de release

28 avril 2026

---

**Notes:**
- Architecture MVC respectée
- Code légèrement documenté pour la clarté
- Patterns CodeIgniter 4 suivis
- Tests unitaires: À implémenter
