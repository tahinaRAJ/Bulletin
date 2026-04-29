<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LivreSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['titre' => 'Clean Code', 'isbn' => '9780132350884', 'date_publication' => '2008-08-01', 'id_auteur' => 3, 'id_categorie' => 1, 'resume' => 'Bonnes pratiques de developpement.'],
            ['titre' => '1984', 'isbn' => '9780451524935', 'date_publication' => '1949-06-08', 'id_auteur' => 4, 'id_categorie' => 2, 'resume' => 'Roman dystopique classique.'],
            ['titre' => 'Sapiens', 'isbn' => '9780062316097', 'date_publication' => '2011-01-01', 'id_auteur' => 2, 'id_categorie' => 4, 'resume' => 'Histoire de l humanite.'],
            ['titre' => 'Les Miserables', 'isbn' => '9782253096344', 'date_publication' => '1862-01-01', 'id_auteur' => 1, 'id_categorie' => 2, 'resume' => 'Roman social majeur.'],
            ['titre' => 'Thinking Fast and Slow', 'isbn' => '9780374533557', 'date_publication' => '2011-10-25', 'id_auteur' => 5, 'id_categorie' => 3, 'resume' => 'Psychologie de la decision.'],
            ['titre' => 'Animal Farm', 'isbn' => '9780451526342', 'date_publication' => '1945-08-17', 'id_auteur' => 4, 'id_categorie' => 2, 'resume' => 'Satire politique.'],
            ['titre' => 'Homo Deus', 'isbn' => '9780062464316', 'date_publication' => '2015-01-01', 'id_auteur' => 2, 'id_categorie' => 4, 'resume' => 'Perspective sur le futur.'],
            ['titre' => 'Le Dernier Jour d un Condamne', 'isbn' => '9782070409518', 'date_publication' => '1829-01-01', 'id_auteur' => 1, 'id_categorie' => 2, 'resume' => 'Plaidoyer contre la peine de mort.'],
            ['titre' => 'Refactoring', 'isbn' => '9780134757599', 'date_publication' => '2018-11-19', 'id_auteur' => 3, 'id_categorie' => 1, 'resume' => 'Ameliorer la structure du code.'],
            ['titre' => 'La Legende des siecles', 'isbn' => '9782070413119', 'date_publication' => '1859-01-01', 'id_auteur' => 1, 'id_categorie' => 2, 'resume' => 'Recueil poetique.'],
            ['titre' => 'The Pragmatic Programmer', 'isbn' => '9780135957059', 'date_publication' => '2019-09-13', 'id_auteur' => 3, 'id_categorie' => 1, 'resume' => 'Conseils pratiques pour developpeurs.'],
            ['titre' => 'Security Engineering', 'isbn' => '9781119642787', 'date_publication' => '2020-12-01', 'id_auteur' => 3, 'id_categorie' => 1, 'resume' => 'Principes de securite applicative.'],
        ];

        $this->db->table('livre')->insertBatch($data);
    }
}
