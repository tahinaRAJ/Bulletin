<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom'       => 'Admin User',
                'email'     => 'admin@bibliotheque.fr',
                'password'  => 'password123',
                'role'      => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom'       => 'Bibliothecaire Test',
                'email'     => 'bibliothecaire@bibliotheque.fr',
                'password'  => 'password123',
                'role'      => 'bibliothecaire',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom'       => 'Jean Dupont',
                'email'     => 'jean@example.com',
                'password'  => 'password123',
                'role'      => 'utilisateur',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom'       => 'Marie Martin',
                'email'     => 'marie@example.com',
                'password'  => 'password123',
                'role'      => 'utilisateur',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
