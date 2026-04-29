<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nom'        => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],
            'email'      => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'unique'     => true
            ],
            'password'   => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],
            'role'       => [
                'type'       => 'ENUM',
                'constraint' => ['utilisateur', 'bibliothecaire', 'admin'],
                'default'    => 'utilisateur',
                'null'       => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
