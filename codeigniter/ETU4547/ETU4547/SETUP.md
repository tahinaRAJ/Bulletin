# Setup - Gestion de Bibliothèque

## Installation et Configuration

### 1. Prérequis
- PHP 8.0+
- MySQL/MariaDB
- Composer

### 2. Installation des dépendances

```bash
composer install
```

### 3. Configuration de la base de données

Créez une base de données `bibliotheque`:

```sql
CREATE DATABASE IF NOT EXISTS bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Vérifiez/modifiez la configuration dans `app/Config/Database.php` :

```php
public array $default = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'bibliotheque',
    'DBDriver' => 'MySQLi',
];
```

### 4. Exécuter les migrations

```bash
php spark migrate
```

Cela créera les tables:
- `livres`
- `emprunts`
- `users`

### 5. Seed les données de test (optionnel)

```bash
php spark db:seed UserSeeder
```

Cela crée les utilisateurs de test:
- **Admin**: admin@bibliotheque.fr / password123
- **Bibliothécaire**: bibliothecaire@bibliotheque.fr / password123
- **Utilisateur 1**: jean@example.com / password123
- **Utilisateur 2**: marie@example.com / password123

### 6. Lancer le serveur de développement

```bash
php spark serve
```

L'application sera disponible à: http://localhost:8080

## Accès initial

### À titre d'admin:
1. Allez à http://localhost:8080/auth/login
2. Connectez-vous avec:
   - Email: `admin@bibliotheque.fr`
   - Mot de passe: `password123`

### Fonctionnalités disponibles:

#### Pour tous les utilisateurs connectés:
- Consulter le catalogue des livres
- Emprunter un livre disponible
- Rendre un livre
- Voir son profil et historique d'emprunts

#### Pour les bibliothécaires et admins:
- Ajouter de nouveaux livres
- Supprimer des livres

#### Pour les admins:
- Accès au tableau de bord (future)

## Architecture

```
app/
├── Controllers/
│   ├── AuthController.php       # Authentification
│   ├── LibraryController.php    # Gestion des livres
│   ├── MouvementController.php  # Emprunts/Retours
│   └── UserController.php       # Profil utilisateur
├── Models/
│   ├── UserModel.php            # Utilisateurs
│   ├── LivreModel.php           # Livres
│   └── EmpruntModel.php         # Emprunts
├── Filters/
│   ├── AuthFilter.php           # Vérifier connexion
│   └── RoleFilter.php           # Vérifier rôle
├── Views/
│   ├── auth/                    # Pages auth
│   ├── library/                 # Pages catalogue
│   ├── user/                    # Pages profil
│   └── layouts/main.php         # Layout principal
└── Database/
    ├── Migrations/              # Schéma BD
    └── Seeds/                   # Données de test
```

## Filtres et Routes

### Filtres disponibles:
- `auth`: Vérifier que l'utilisateur est connecté
- `role:admin,bibliothecaire`: Vérifier le rôle

### Routes principales:

**Publiques:**
- GET `/` → Redirects vers /livres
- GET `/auth/login` → Formulaire connexion
- GET `/auth/register` → Formulaire inscription
- GET `/livres` → Catalogue

**Protégées (auth requis):**
- GET `/livres/new` → Formulaire ajout (admin, bibliothécaire)
- POST `/livres` → Créer livre (admin, bibliothécaire)
- GET `/livres/:id` → Détail livre
- POST `/livres/:id/loan` → Emprunter livre
- POST `/livres/:id/return` → Rendre livre
- GET `/profile` → Mon profil

**Réservées (admin ou bibliothécaire):**
- POST `/livres/:id/delete` → Supprimer livre

## Dépannage

### Migration échoue
- Vérifiez que la base de données existe
- Vérifiez les credentials dans `app/Config/Database.php`
- Vérifiez les permissions MySQL

### Login échoue
- Les lettres minuscules/majuscules comptent pour l'email
- Utilisez les credentials du seeder: `admin@bibliotheque.fr`

### Fichiers de connexion refusés
- Vérifiez les permissions du dossier `writable/` (755+)
- Le dossier `public/uploads/` doit être writable

## Prochaines étapes

Voir `todo.md` pour les fonctionnalités à implémenter:
- Gestion avancée des emprunts (retards, réservations)
- Catalogue avancé (auteurs, notations, commentaires)
- Tableau de bord statistiques
- Export CSV/PDF
