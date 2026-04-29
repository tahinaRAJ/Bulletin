<?php

namespace App\Models;

use CodeIgniter\Model;

class AuteurModel extends Model
{
    protected $table = 'auteur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['nom'];
}
