# 📚 Gestion des Notes - CodeIgniter 4

**Système complet de gestion des notes pour L2, S3 et S4**

---

## 🎯 Fonctionnalités

✅ **Authentification** - Login avec identifiants (admin/1234)
✅ **Gestion des étudiants** - Liste complète des étudiants
✅ **Insertion de notes** - Formulaire pour ajouter des notes par étudiant et matière
✅ **Fiche étudiant** - Consultation des notes par semestre et option
✅ **Calculs automatiques** :

- MAX note par matière (si plusieurs insertions)
- Moyennes pondérées par semestre
- Moyenne annuelle L2
- Meilleure note optionnelle par option

✅ **Interface moderne** - Design SysInfo avec sidebar et responsive

---

## 📦 Structure du Projet

```
bulletin/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php       (Login/Logout)
│   │   ├── EtuController.php        (Liste étudiants)
│   │   └── NoteController.php       (Notes et fiches)
│   ├── Models/
│   │   ├── EtuModel.php             (Requêtes étudiants)
│   │   └── NoteModel.php            (Requêtes notes)
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── main.php             (Layout principal)
│   │   │   └── login_standalone.php (Layout login)
│   │   ├── auth/
│   │   │   └── login.php            (Page login)
│   │   ├── etudiant/
│   │   │   └── list.php             (Liste étudiants)
│   │   └── note/
│   │       ├── insert.php           (Formulaire notes)
│   │       ├── fiche_etu.php        (Fiche étudiant)
│   │       ├── _fiche_semestre_option.php  (Partielle S4)
│   │       └── _fiche_l2_option.php        (Partielle L2)
│   ├── Config/
│   │   └── Routes.php               (Routes configurées)
│   └── Database/
│       ├── 01_create_tables.sql     (Création tables)
│       ├── 02_insert_test_data.sql  (Données test)
│       ├── 03_create_views.sql      (Vues SQL)
│       ├── 04_exemple_notes_test.sql (Notes test)
│       └── README.md                (Guide SQL)
├── public/
│   └── css/
│       └── style.css                (Styles SysInfo)
├── base_complete.sql                (Alternative: script complet)
└── .env                             (Configuration)
```

---

## 🚀 Installation

### 1️⃣ Prérequis

- **PHP 7.4+**
- **MySQL 5.7+**
- **CodeIgniter 4** (déjà installé)
- **Composer** (optionnel)

### 2️⃣ Créer la base de données

```bash
# Accéder au répertoire
cd /home/victus/Documents/GitHub/Bulletin/bulletin

# Créer la base
mysql -u root -p -e "CREATE DATABASE gestion_notes;"
```

### 3️⃣ Importer les fichiers SQL

Exécutez dans cet ordre **obligatoire** :

```bash
# 1. Créer les tables
mysql -u root -p gestion_notes < app/Database/01_create_tables.sql

# 2. Insérer les données de base
mysql -u root -p gestion_notes < app/Database/02_insert_test_data.sql

# 3. Créer les vues SQL
mysql -u root -p gestion_notes < app/Database/03_create_views.sql

# 4. (OPTIONNEL) Insérer des notes de test
mysql -u root -p gestion_notes < app/Database/04_exemple_notes_test.sql
```

### 4️⃣ Configurer .env

Modifiez le fichier `.env` :

```ini
database.default.hostname = localhost
database.default.database = gestion_notes
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5️⃣ Lancer le serveur

```bash
cd /home/victus/Documents/GitHub/Bulletin/bulletin
php spark serve
```

Accédez à : **http://localhost:8080**

---

## 🔑 Identifiants par défaut

```
Nom d'utilisateur : admin
Mot de passe : 1234
```

Ces valeurs sont **pré-remplies** dans le formulaire pour faciliter les tests.

---

## 📋 Flux d'utilisation

### 1. Login

```
http://localhost:8080/login
→ Clic sur "Connexion" (identifiants pré-remplis)
```

### 2. Liste des étudiants

```
http://localhost:8080/list
→ Voir tous les étudiants
→ Cliquer sur un étudiant pour voir sa fiche
```

### 3. Ajouter une note

```
http://localhost:8080/insert
→ Sélectionner étudiant, matière et note (0-20)
→ Soumettre
```

### 4. Consulter la fiche étudiant

```
http://localhost:8080/etud/003469
→ Onglets S3, S4 Dev/BddRes/Web, L2 Dev/BddRes/Web
→ Voir notes MAX et moyennes
→ Supprimer une note si nécessaire
```

---

## 🧪 Test fonctionnel (Recette)

Voir le fichier `todo.matavy` pour la liste complète des tests.

### Tests rapides :

```
✓ T1 : Connexion admin
✓ T2 : Voir liste étudiants
✓ T3 : Ajouter note 15 pour Rakoto Jean, PHP Avancé
✓ T4 : Ajouter note 18 pour Rakoto Jean, PHP Avancé → MAX = 18
✓ T5 : Voir fiche Rakoto Jean
✓ T6 : Supprimer la note de 18 → revient à 15
✓ T7 : Vérifier moyenne S3 calculée
✓ T8 : Vérifier onglet S4 Dev (matière optionnelle)
✓ T9 : Vérifier moyenne L2 (S3+S4)/2
```

---

## 📊 Architecture SQL

### Tables principales

- **semestre** : S3 (id=1), S4 (id=2), L2 (id=3)
- **option** : dev, bddres, web
- **matiere** : 13 matières (5 S3 + 3 S4 oblig + 3 S4 option)
- **note** : Toutes les notes insérées
- **etudiant** : 2 étudiants de test
- **user** : Authentification

### Vues SQL

- `v_note_par_option` → Notes MAX avec option
- `v_moyenne_par_option` → Moyennes pondérées
- `v_moyenne_annee_par_option` → Moyennes annuelles L2
- `v_notes_etudiant` → Vue utilitaire

---

## 🎨 Design

Utilise le thème **SysInfo** (Figma) :

- **Couleur primaire** : Bleu (#2563eb)
- **Sidebar** : Gris foncé (#0f1729)
- **Interface** : Moderne et responsive
- **Icônes** : SVG intégrées

---

## 🐛 Troubleshooting

### "Connexion BDD refusée"

→ Vérifier que MySQL est en cours d'exécution
→ Vérifier credentials dans `.env`

### "Tables n'existent pas"

→ Exécuter les 3 fichiers SQL dans l'ordre
→ Vérifier la base est créée : `SHOW DATABASES;`

### "Pas de données de test"

→ Exécuter `04_exemple_notes_test.sql`
→ Ou ajouter manuellement via le formulaire

### Erreur 404 sur les routes

→ Vérifier que Routes.php est bien configuré
→ Redémarrer le serveur : `php spark serve`

---

## 📝 Règles de gestion

✅ **Notes**

- Plage : 0-20
- On peut insérer plusieurs fois pour une matière
- Affichage : Note maximale

✅ **S3**

- 5 matières obligatoires
- Pas d'option
- Moyenne = (somme notes × coef) / coef total

✅ **S4**

- 3 matières obligatoires (tous les étudiants)
- 3 matières optionnelles (1 par option)
- Affichage : Meilleure note optionnelle
- Moyenne = (somme notes × coef) / coef total

✅ **L2**

- Fusionner S3 + S4
- Moyenne annuelle = (Moy S3 + Moy S4) / 2

---

## 📖 Documentation complémentaire

- **todo.matavy** : Liste complète des étapes et tests
- **app/Database/README.md** : Guide détaillé d'installation SQL
- **app/base_complete.sql** : Alternative : script SQL complet en un fichier

---

## 🔗 Fichiers importants

| Fichier                             | Rôle                    |
| ----------------------------------- | ----------------------- |
| `app/Config/Routes.php`             | Routes de l'application |
| `.env`                              | Configuration MySQL     |
| `app/Database/01_create_tables.sql` | Création des tables     |
| `public/css/style.css`              | Styles SysInfo          |

---

**Prêt à démarrer ? Exécutez les scripts SQL et lancez le serveur ! 🚀**

Pour toute question, consultez le `todo.matavy` ou les fichiers README.
