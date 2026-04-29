<?php

namespace App\Models;

use CodeIgniter\Model;

class LivreModel extends Model
{
    protected $table = 'livre';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'titre',
        'isbn',
        'date_publication',
        'resume',
        'couverture',
        'id_auteur',
        'id_categorie',
    ];

    public function getPaginatedWithRelations(?string $search = null, ?int $categorieId = null, int $perPage = 10): array
    {
        $builder = $this->select("livre.*, auteur.nom AS auteur_nom, categorie.nom AS categorie_nom,
            COALESCE((
                SELECT ml.type_mouvement
                FROM mouvement_livre ml
                WHERE ml.id_livre = livre.id
                ORDER BY ml.date_mouvement DESC, ml.id DESC
                LIMIT 1
            ), 'RETOUR') AS dernier_mouvement")
            ->join('auteur', 'auteur.id = livre.id_auteur')
            ->join('categorie', 'categorie.id = livre.id_categorie')
            ->orderBy('livre.titre', 'ASC');

        if (! empty($search)) {
            $builder->like('livre.titre', $search);
        }

        if (! empty($categorieId)) {
            $builder->where('livre.id_categorie', $categorieId);
        }

        return $builder->paginate($perPage);
    }

    public function getWithRelations(int $id): ?array
    {
        return $this->select('livre.*, auteur.nom AS auteur_nom, categorie.nom AS categorie_nom')
            ->join('auteur', 'auteur.id = livre.id_auteur')
            ->join('categorie', 'categorie.id = livre.id_categorie')
            ->where('livre.id', $id)
            ->first();
    }
}
