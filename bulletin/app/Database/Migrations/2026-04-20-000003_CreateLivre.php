<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivre extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'titre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'isbn' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'date_publication' => [
                'type' => 'DATE',
            ],
            'resume' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'couverture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'id_auteur' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_categorie' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('isbn');
        $this->forge->addForeignKey('id_auteur', 'auteur', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_categorie', 'categorie', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('livre');
    }

    public function down()
    {
        $this->forge->dropTable('livre');
    }
}
