<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTable extends Migration
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
            'livre_id'   => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false
            ],
            'user_id'    => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false
            ],
            'date_reservation' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'position_file' => [
                'type'       => 'INT',
                'default'    => 0
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
        $this->forge->addForeignKey('livre_id', 'livres', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations', true);
    }
}
