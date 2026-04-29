<?php

namespace App\Models;

use CodeIgniter\Model;

class EtuModel extends Model
{
    protected $table = 'etudiant';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['id', 'nom'];

    /**
     * Récupérer tous les étudiants
     */
    public function findAllEtudiants()
    {
        return $this->findAll();
    }

    /**
     * Récupérer un étudiant par ID
     */
    public function getEtudiant($id)
    {
        return $this->find($id);
    }
}
