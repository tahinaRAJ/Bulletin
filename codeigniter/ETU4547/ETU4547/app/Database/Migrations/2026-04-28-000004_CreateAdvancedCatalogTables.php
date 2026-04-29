<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdvancedCatalogTables extends Migration
{
    public function up()
    {
        // Table auteurs
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'prenom' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
            ],
            'biographie' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('nom');
        $this->forge->createTable('auteurs');

        // Table pivot livre_auteur (N:N)
        $this->forge->addField([
            'livre_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'auteur_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey(['livre_id', 'auteur_id'], true);
        $this->forge->addForeignKey('livre_id', 'livres', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('auteur_id', 'auteurs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('livre_auteur');

        // Table notations
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'livre_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'note' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['livre_id', 'user_id'], false, true);
        $this->forge->addForeignKey('livre_id', 'livres', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notations');

        // Table commentaires
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'livre_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'texte' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('livre_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('livre_id', 'livres', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('commentaires');
    }

    public function down()
    {
        $this->forge->dropTable('commentaires', true);
        $this->forge->dropTable('notations', true);
        $this->forge->dropTable('livre_auteur', true);
        $this->forge->dropTable('auteurs', true);
    }
}
