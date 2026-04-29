<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuteurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => 'Victor Hugo'],
            ['nom' => 'Yuval Noah Harari'],
            ['nom' => 'Robert C. Martin'],
            ['nom' => 'George Orwell'],
            ['nom' => 'Daniel Kahneman'],
        ];

        $this->db->table('auteur')->insertBatch($data);
    }
}
