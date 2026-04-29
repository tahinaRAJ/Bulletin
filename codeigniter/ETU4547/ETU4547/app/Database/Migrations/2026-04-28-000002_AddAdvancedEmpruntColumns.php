<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdvancedEmpruntColumns extends Migration
{
    public function up()
    {
        // Ajouter colonnes à la table emprunts
        $fields = [
            'date_retour_prevue' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'date_emprunt'
            ],
            'statut' => [
                'type'       => 'ENUM',
                'constraint' => ['actif', 'retourne', 'retard'],
                'default'    => 'actif',
                'after'      => 'date_retour_prevue'
            ],
            'jours_retard' => [
                'type'       => 'INT',
                'null'       => true,
                'after'      => 'statut'
            ],
        ];

        $this->forge->addColumn('emprunts', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('emprunts', ['date_retour_prevue', 'statut', 'jours_retard']);
    }
}
