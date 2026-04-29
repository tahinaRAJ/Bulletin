<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BibliothequeSeeder extends Seeder
{
    public function run()
    {
        $this->call('AuteurSeeder');
        $this->call('CategorieSeeder');
        $this->call('LivreSeeder');
    }
}
