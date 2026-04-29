# Guide de Test - Système d'Authentification

## Prérequis

- Serveur lancé: `php spark serve`
- Base de données avec migrations exécutées
- Seed UserSeeder exécuté

## Scénarios de test

### 1. Test Public (sans connexion)

#### 1.1 Accès au catalogue public
- **URL:** http://localhost:8080/livres
- **Résultat attendu:** Page du catalogue visible
- **Navbar:** Liens "Connexion" et "S'inscrire" visibles

#### 1.2 Tentative d'accès page ajout
- **URL:** http://localhost:8080/livres/new
- **Résultat attendu:** Redirection vers /auth/login avec message d'erreur

#### 1.3 Tentative d'accès profil
- **URL:** http://localhost:8080/profile
- **Résultat attendu:** Redirection vers /auth/login

### 2. Test Inscription

#### 2.1 Inscription valide
1. Allez à http://localhost:8080/auth/register
2. Remplissez:
   - Nom: "Test User"
   - Email: "test@example.com"
   - Mot de passe: "TestPass123"
3. Cliquez "S'inscrire"
4. **Résultat attendu:** 
   - Message "Inscription réussie"
   - Redirection vers /auth/login

#### 2.2 Inscription - Email déjà existant
1. Tentez de re-inscrire avec "admin@bibliotheque.fr"
2. **Résultat attendu:** Message d'erreur "Cet email est déjà utilisé"

#### 2.3 Inscription - Mot de passe trop court
1. Remplissez avec mot de passe "short"
2. **Résultat attendu:** Message d'erreur "minimum 8 caractères"

### 3. Test Connexion

#### 3.1 Connexion Admin
1. Allez à http://localhost:8080/auth/login
2. Remplissez:
   - Email: `admin@bibliotheque.fr`
   - Mot de passe: `password123`
3. Cliquez "Se connecter"
4. **Résultat attendu:**
   - Redirection vers /livres
   - Session créée
   - Navbar affiche: "Ajouter", "Mon Profil", "Déconnexion"

#### 3.2 Connexion Bibliothécaire
- Email: `bibliothecaire@bibliotheque.fr`
- Mot de passe: `password123`
- **Résultat attendu:** Mêmes droits que ci-dessus

#### 3.3 Connexion Utilisateur
- Email: `jean@example.com`
- Mot de passe: `password123`
- **Résultat attendu:**
   - Peut emprunter/rendre livres
   - Peut voir son profil
   - **NE PEUT PAS** ajouter/supprimer livres

#### 3.4 Connexion - Identifiants incorrects
- Email: `admin@bibliotheque.fr`
- Mot de passe: `wrongpassword`
- **Résultat attendu:** Message d'erreur "Email ou mot de passe incorrect"

### 4. Test Droits d'accès

#### 4.1 Admin/Bibliothécaire peuvent ajouter des livres
1. Connecté en tant qu'admin
2. Allez à http://localhost:8080/livres/new
3. **Résultat attendu:** Formulaire d'ajout affichage

#### 4.2 Utilisateur simple ne peut pas ajouter
1. Connecté en tant qu'utilisateur (jean@example.com)
2. Accédez à http://localhost:8080/livres/new
3. **Résultat attendu:** Redirection vers / avec message "Accès refusé"

#### 4.3 Utilisateur simple ne peut pas supprimer
1. Connecté en tant qu'utilisateur
2. Tentez POST vers `/livres/1/delete`
3. **Résultat attendu:** Erreur d'accès refusé

### 5. Test Profil Utilisateur

#### 5.1 Vue profil de l'utilisateur
1. Connecté en tant qu'utilisateur (jean@example.com)
2. Cliquez "Mon Profil" dans la navbar
3. **Résultat attendu:**
   - Affichage du nom, email, rôle
   - Liste d'emprunts précédents

#### 5.2 Modification du profil (futur)
- **État:** Non implémenté
- **À faire:** Route PUT /profile avec validation

### 6. Test Déconnexion

#### 6.1 Déconnexion simple
1. Connecté à n'importe quel compte
2. Cliquez "Déconnexion" dans la navbar
3. **Résultat attendu:**
   - Session détruite
   - Redirection vers /auth/login
   - Message "Déconnexion réussie"

#### 6.2 Accès après déconnexion
1. Après déconnexion
2. Tentez d'accéder /profile
3. **Résultat attendu:** Redirection vers /auth/login

### 7. Test Sécurité

#### 7.1 CSRF Protection
1. Créez un formulaire POST externe
2. Tentez à envoyer sans token CSRF
3. **Résultat attendu:** Erreur CSRF

#### 7.2 Hachage des mots de passe
1. Accédez à la base de données
2. Consultez la table `users`
3. **Résultat attendu:** Les mots de passe sont hashés (jamais en clair)

#### 7.3 Session Expiration (futur)
- **État:** Non implémenté
- **À faire:** Logout automatique après X minutes

### 8. Test Rôles Spécifiques

#### 8.1 Différenciation Admin
1. Connecté en tant qu'admin
2. Vérifiez que le rôle affiche "Admin" dans le profil
3. Accédez à /admin/dashboard (futur)
4. **Résultat attendu:** Accès autorisé

#### 8.2 Différenciation Bibliothécaire
1. Connecté en tant que bibliothécaire
2. Vérifié que le rôle affiche "Bibliothecaire"
3. **NE PEUT PAS** accéder /admin/dashboard
4. **Résultat attendu:** Redirection vers / avec erreur

#### 8.3 Différenciation Utilisateur
1. Connecté en tant qu'utilisateur
2. Vérifié que le rôle affiche "Utilisateur"
3. **NE PEUT PAS** accéder /livres/new ou /admin
4. **Résultat attendu:** Redirection vers / avec erreur

## Checklist de test complet

- [ ] Accès public au catalogue OK
- [ ] Redirection vers login sans connexion OK
- [ ] Inscription avec validation OK
- [ ] Email dupliqué refuse OK
- [ ] Connexion admin OK
- [ ] Connexion bibliothécaire OK
- [ ] Connexion utilisateur OK
- [ ] Admin peut ajouter livres OK
- [ ] Bibliothécaire peut ajouter livres OK
- [ ] Utilisateur NE peut PAS ajouter OK
- [ ] Admin peut supprimer livres OK
- [ ] Utilisateur NE peut PAS supprimer OK
- [ ] Profil affiche l'historique OK
- [ ] Déconnexion OK
- [ ] Session détruite après logout OK
- [ ] CSRF protection OK
- [ ] Mots de passe hashés OK
- [ ] Rôles affichés correctement OK
- [ ] Messages d'erreur génériques OK

## Commandes SQL pour inspection

```sql
-- Voir les utilisateurs
SELECT id, nom, email, role FROM users;

-- Voir les emprunts d'un utilisateur
SELECT e.*, l.titre 
FROM emprunts e 
JOIN livres l ON e.livre_id = l.id 
WHERE e.nom_emprunteur = 'Jean Dupont';

-- Verifier le hash d'un mot de passe
SELECT email, password FROM users WHERE email = 'admin@bibliotheque.fr';
```

## Logs à consulter

- **Erreurs:** `writable/logs/log-*.log`
- **Affichage:** http://localhost:8080 (débug toolbar en bas à droite)

## Rapports de bug possibles

### Bug: "Undefined variable $erreurs"
- **Cause:** Vue register reçoit variable absence si pas d'erreur
- **Fix:** Ajouter vérification `isset($erreurs)`

### Bug: "CSRF mismatch"
- **Cause:** Token CSRF manquant dans formulaire
- **Fix:** Ajouter `<?= csrf_field() ?>` dans forms

### Bug: "Access denied" au lieu de redirection
- **Cause:** Filtre role mal configuré
- **Fix:** Vérifier la syntaxe `role:admin,bibliothecaire`

## Performance

- Les filtres are stateless (pas de queries supplémentaires)
- Session est stockée en PHP (`$_SESSION`)
- Pas de N+1 queries sur le profil

## Prochaines améliorations à tester

- [ ] Rate limiting login (test 100+ tentatives)
- [ ] 2FA (SMS/Email)
- [ ] Session expiration (test après 30 min inactivité)
- [ ] Audit logging (vérifier logs)
- [ ] API authentication (JWT tokens)
