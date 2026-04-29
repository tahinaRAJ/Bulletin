<?php

namespace App\Models;

use CodeIgniter\Model;

class AuteurModel extends Model
{
    protected $table = 'auteurs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = ['nom', 'prenom', 'biographie'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nom' => 'required|min_length[2]',
    ];

    public function findOrCreate(string $nom, ?string $prenom = null, ?string $biographie = null): int
    {
        $nom = trim($nom);
        $prenom = $prenom !== null ? trim($prenom) : null;

        $existant = $this->where('nom', $nom)
            ->where('prenom', $prenom)
            ->first();

        if ($existant) {
            return (int) $existant['id'];
        }

        return (int) $this->insert([
            'nom' => $nom,
            'prenom' => $prenom,
            'biographie' => $biographie,
        ]);
    }

    public function getLivresParAuteur(int $auteurId): array
    {
        return $this->db->table('livres')
            ->select('livres.*')
            ->join('livre_auteur', 'livre_auteur.livre_id = livres.id')
            ->where('livre_auteur.auteur_id', $auteurId)
            ->orderBy('livres.titre', 'ASC')
            ->get()
            ->getResultArray();
    }
}
