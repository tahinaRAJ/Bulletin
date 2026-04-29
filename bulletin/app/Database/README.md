# Installation de la Base de Données - Gestion des Notes

## 📋 Fichiers SQL fournis

Tous les fichiers SQL se trouvent dans `bulletin/app/Database/` :

### 1. **01_create_tables.sql** ⭐ OBLIGATOIRE

Crée la structure complète des tables :

- `semestre` - Les semestres (S3, S4, L2)
- `option` - Les options (Dev, BddRes, Web)
- `user` - Les utilisateurs (admin)
- `etudiant` - Les étudiants
- `matiere` - Les matières avec coefficients
- `note` - Les notes des étudiants

**À exécuter en premier** :

```bash
mysql -u root -p < bulletin/app/Database/01_create_tables.sql
```

---

### 2. **02_insert_test_data.sql** ⭐ OBLIGATOIRE

Insère les données de base :

- Semestres : S3 (1), S4 (2), L2 (3)
- Options : dev, bddres, web
- Utilisateur de test : `admin` / `1234`
- Étudiants de test :
  - `003469` - Rakoto Jean
  - `003470` - Rabe Marie
- Toutes les matières (13 au total)

**À exécuter en deuxième** :

```bash
mysql -u root -p < bulletin/app/Database/02_insert_test_data.sql
```

---

### 3. **03_create_views.sql** ⭐ OBLIGATOIRE

Crée les vues SQL pour les calculs :

- `v_note_par_option` - Notes MAX par matière et option
- `v_moyenne_par_option` - Moyennes pondérées par option
- `v_moyenne_annee_par_option` - Moyennes annuelles (L2)
- `v_notes_etudiant` - Vue utilitaire

**À exécuter en troisième** :

```bash
mysql -u root -p < bulletin/app/Database/03_create_views.sql
```

---

### 4. **04_exemple_notes_test.sql** (OPTIONNEL)

Insère des notes de test pour les étudiants :

- Notes S3 pour Rakoto Jean et Rabe Marie
- Notes S4 avec options respectives
- Exemples d'insertion multiple

**À exécuter après les 3 premières** (ou manuellement via l'application) :

```bash
mysql -u root -p < bulletin/app/Database/04_exemple_notes_test.sql
```

---

## 🚀 Installation rapide

Si MySQL est configuré avec un utilisateur `root` sans mot de passe :

```bash
cd /home/victus/Documents/GitHub/Bulletin/bulletin
mysql -u root < app/Database/01_create_tables.sql
mysql -u root < app/Database/02_insert_test_data.sql
mysql -u root < app/Database/03_create_views.sql
mysql -u root < app/Database/04_exemple_notes_test.sql
```

Chaque script crée la base si besoin puis exécute `USE gestion_notes;`, donc ils peuvent être lancés sans préparation manuelle.

---

## ⚙️ Configuration du fichier .env

Avant de lancer l'application, assurez-vous que `.env` contient :

```ini
database.default.hostname = localhost
database.default.database = gestion_notes
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

---

## 🧪 Identifiants de test

**Login par défaut** (pré-remplis dans le formulaire) :

- **Nom d'utilisateur** : `admin`
- **Mot de passe** : `1234`

---

## 📊 Règles de gestion implementées

✅ **Pour les notes** :

- Note maximale = 20, minimale = 0
- On peut insérer plusieurs notes pour une même matière
- L'affichage utilise la NOTE LA PLUS ÉLEVÉE

✅ **Pour S3** :

- 5 matières obligatoires
- Pas d'options

✅ **Pour S4** :

- 3 matières obligatoires pour tous
- 3 matières optionnelles (1 par option : Dev, BddRes, Web)
- Pour chaque option, seule la meilleure note optionnelle est affichée

✅ **Pour L2** :

- Combinaison S3 + S4
- Moyenne annuelle = (Moyenne S3 + Moyenne S4) / 2

---

## 🔍 Vérification après installation

Vérifier que tout s'est bien passé :

```bash
mysql -u root gestion_notes -e "SHOW TABLES;"
mysql -u root gestion_notes -e "SELECT * FROM user;"
mysql -u root gestion_notes -e "SELECT COUNT(*) as nb_matieres FROM matiere;"
```

---

## 📝 Notes supplémentaires

- Les fichiers SQL peuvent être exécutés via **PhpMyAdmin** si MySQL n'est pas accessible en CLI
- Les migrations CodeIgniter ne sont pas utilisées ici (SQL direct)
- Les vues gèrent automatiquement les règles métier (MAX note, options, etc.)

**Prêt ? Exécutez les 4 fichiers SQL dans l'ordre ! 🎉**
