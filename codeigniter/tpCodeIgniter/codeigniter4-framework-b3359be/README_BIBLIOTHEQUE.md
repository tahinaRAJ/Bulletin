# Bibliotheque - Guide rapide

## Prerequis

- PHP 8.2+
- Composer
- MySQL (ou autre SGBD configure dans CodeIgniter)

## Installation

```bash
composer install
cp env .env
```

Configurer ensuite la base de donnees dans `.env`:

- `database.default.hostname`
- `database.default.database`
- `database.default.username`
- `database.default.password`
- `database.default.DBDriver`

## Commandes utiles

```bash
php spark migrate
php spark db:seed BibliothequeSeeder
php spark serve
```

## Fonctionnalites implementees

- Catalogue des livres avec recherche et filtre categorie
- Pagination (10 livres par page)
- Fiche detaillee avec historique des mouvements
- Emprunt/retour avec verification de disponibilite
- Validation des formulaires (ISBN unique, titre min 3, date non future)
- Upload optionnel de couverture avec controle image + taille max 2 Mo
