# Architecture d'Authentification et Autorisation

## Vue d'ensemble

Le système utilise des **Filters** (middlewares) pour protéger les routes selon l'état d'authentification et le rôle de l'utilisateur.

### Flux d'authentification

```
Requête entrante
    │
    ▼
AuthFilter → Utilisateur connecté ? ──non──► /auth/login
    │
    ▼ (oui)
RoleFilter → Rôle autorisé ? ──non──► / (accès refusé)
    │
    ▼ (oui)
Contrôleur → Traitement normal
```

## 1. Rôles

Trois rôles sont définis:

| Rôle | Permissions | Cas d'usage |
|------|---------|-----------|
| `utilisateur` | Emprunter, rendre, consulter profil | Lecteur classique |
| `bibliothecaire` | + Ajouter/Supprimer livres, gestion avancée | Personnel bibliothèque |
| `admin` | + Tableau de bord statistiques, gestion users | Administrateur |

## 2. Filtres

### AuthFilter (`app/Filters/AuthFilter.php`)

Vérifie que l'utilisateur est **connecté**.

```php
if (!$session->get('user')) {
    return redirect()->to('/auth/login');
}
```

**Utilisation:**
```php
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/livres/new', 'LibraryController::new');
});
```

### RoleFilter (`app/Filters/RoleFilter.php`)

Vérifie que l'utilisateur a un des rôles autorisés.

```php
if (!$user || !in_array($user['role'], $arguments)) {
    return redirect()->to('/')->with('erreur', 'Accès refusé');
}
```

**Utilisation - Rôle unique:**
```php
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    $routes->get('dashboard', 'AdminDashboardController::index');
});
```

**Utilisation - Plusieurs rôles:**
```php
$routes->group('', ['filter' => 'role:admin,bibliothecaire'], function($routes) {
    $routes->post('/livres', 'LibraryController::store');
});
```

## 3. Session

Les données non-sensibles de l'utilisateur sont stockées en session:

```php
session()->set('user', [
    'id'    => 1,
    'nom'   => 'John Doe',
    'email' => 'john@example.com',
    'role'  => 'bibliothecaire'  // ← Clé importante
]);
```

**Accès dans les contrôleurs:**
```php
$user = session()->get('user');
if ($user['role'] === 'admin') {
    // ...
}
```

**Accès dans les vues:**
```php
<?php if (session()->get('user')['role'] === 'admin') : ?>
    <a href="/admin/dashboard">Tableau de bord</a>
<?php endif; ?>
```

## 4. Routes protégées

### Routes publiques (sans filtre)

```php
$routes->get('/auth/login', 'AuthController::loginForm');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/livres', 'LibraryController::index');
```

### Routes protégées - Utilisateur connecté

```php
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/profile', 'UserController::profile');
    $routes->post('/livres/(:num)/loan', 'MouvementController::loan/$1');
});
```

### Routes protégées - Rôles spécifiques

```php
// Bibliothécaire ET Admin
$routes->group('', ['filter' => 'role:admin,bibliothecaire'], function($routes) {
    $routes->get('/livres/new', 'LibraryController::new');
    $routes->post('/livres', 'LibraryController::store');
});

// Admin seulement
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    $routes->get('dashboard', 'AdminDashboardController::index');
});
```

## 5. Contrôleur d'authentification

### AuthController

**Méthode `login()`:**
1. Récupère email et password du formulaire
2. Cherche l'utilisateur dans la BD
3. Vérifie le hash du mot de passe
4. Crée la session utilisateur
5. Redirige vers /livres

```php
public function login()
{
    $user = $this->userModel->verifyCredentials($email, $password);
    
    if (!$user) {
        return view('auth/login', ['erreur' => '...']);
    }
    
    session()->set('user', [
        'id' => $user['id'],
        'nom' => $user['nom'],
        'email' => $user['email'],
        'role' => $user['role']
    ]);
    
    return redirect()->to('/livres');
}
```

**Méthode `register()`:**
1. Récupère nom, email, password du formulaire
2. Valide les données (UserModel)
3. Crée l'utilisateur (rôle par défaut: 'utilisateur')
4. Redirige vers login

**Méthode `logout()`:**
1. Détruit la session
2. Redirige vers login

## 6. UserModel

Hérite de `CodeIgniter\Model`.

### Validations

```php
protected $validationRules = [
    'nom'      => 'required|min_length[3]',
    'email'    => 'required|valid_email|is_unique[users.email]',
    'password' => 'required|min_length[8]',
    'role'     => 'required|in_list[utilisateur,bibliothecaire,admin]',
];
```

### Hachage du mot de passe

```php
public function beforeInsert(array $data)
{
    if (isset($data['data']['password'])) {
        $data['data']['password'] = password_hash(
            $data['data']['password'], 
            PASSWORD_BCRYPT
        );
    }
    return $data;
}
```

### Vérification des credentials

```php
public function verifyCredentials(string $email, string $password)
{
    $user = $this->where('email', $email)->first();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    
    return null;
}
```

## 7. Tableau des routes protégées

| Route | Méthode | Filtre | Rôles |
|-------|---------|--------|-------|
| /auth/login | GET, POST | — | Public |
| /auth/register | GET, POST | — | Public |
| /auth/logout | GET | — | Public (mais logout) |
| /livres | GET | — | Public |
| /livres/new | GET | auth + role | admin, bibliothecaire |
| /livres | POST | auth + role | admin, bibliothecaire |
| /livres/:id | GET | auth | Connecté |
| /livres/:id/loan | POST | auth | Connecté |
| /livres/:id/return | POST | auth | Connecté |
| /livres/:id/delete | POST | auth + role | admin, bibliothecaire |
| /profile | GET | auth | Connecté |

## 8. Flux de connexion

1. Utilisateur clique sur "Connexion"
2. Affichage du formulaire (view `auth/login.php`)
3. Soumission du formulaire vers POST `/auth/login`
4. `AuthController::login()`:
   - Valide les credentials
   - Crée la session si OK
   - Redirige vers /livres
5. Les filtres s'appliquent automatiquement aux routes suivantes

## 9. Déclaration des filtres

Dans `app/Config/Filters.php`:

```php
public array $aliases = [
    'csrf' => CSRF::class,
    'auth' => AuthFilter::class,
    'role' => RoleFilter::class,
];
```

Les filtres sont appliqués:
- **Au niveau global** (toutes les routes)
- **Par groupe de routes** (plusieurs routes)
- **Par route individuelle** (option)

## 10. Points clés à retenir

| Concept | Détail |
|---------|---------|
| FilterInterface | Contrat que tout filtre doit implémenter |
| before() | S'exécute avant le contrôleur (vérifications) |
| $arguments | Paramètres passés dans la route (ex: `role:admin`) |
| session()->get('user') | Source de vérité de l'identité |
| password_hash() | **Toujours** hacher les mots de passe |
| password_verify() | Vérifier le hash, jamais en plain-text |
| redirect()->to() | Sortie propre du filtre en cas de refus |

## Sécurité

### ✅ Points forts

1. **Hachage BCRYPT** des mots de passe
2. **Filtres appliqués avant le contrôleur** (vérification préventive)
3. **Session limitée** (données non-sensibles)
4. **CSRF global** sur tous les formulaires

### ⚠️ À améliorer

1. Implémenter **rate limiting** sur les tentatives de connexion
2. Ajouter **2FA** (double authentification)
3. Implémenter **audit logging** des accès
4. Ajouter **token refresh** pour les sessions longues
5. Implémenter **logout sur inactivité**
