<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => 'Informatique'],
            ['nom' => 'Roman'],
            ['nom' => 'Finance'],
            ['nom' => 'Histoire'],
        ];

        $this->db->table('categorie')->insertBatch($data);
    }
}
