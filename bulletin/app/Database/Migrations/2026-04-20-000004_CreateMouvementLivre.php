<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateMouvementLivre extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_livre' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'type_mouvement' => [
                'type' => 'ENUM',
                'constraint' => ['EMPRUNT', 'RETOUR'],
            ],
            'nom_emprunteur' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'date_mouvement' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
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
        $this->forge->addKey('id_livre');
        $this->forge->addForeignKey('id_livre', 'livre', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('mouvement_livre');
    }

    public function down()
    {
        $this->forge->dropTable('mouvement_livre');
    }
}
