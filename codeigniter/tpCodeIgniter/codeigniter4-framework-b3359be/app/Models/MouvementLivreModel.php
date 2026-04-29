<?php

namespace App\Models;

use CodeIgniter\Model;

class MouvementLivreModel extends Model
{
    protected $table = 'mouvement_livre';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_livre',
        'type_mouvement',
        'nom_emprunteur',
        'date_mouvement',
    ];

    public function getDernierMouvementLivre(int $idLivre): ?array
    {
        return $this->where('id_livre', $idLivre)
            ->orderBy('date_mouvement', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getEtatCourantLivre(int $idLivre): string
    {
        $dernier = $this->getDernierMouvementLivre($idLivre);

        if ($dernier === null || $dernier['type_mouvement'] === 'RETOUR') {
            return 'DISPONIBLE';
        }

        return 'EMPRUNTE';
    }

    public function getHistoriqueLivre(int $idLivre): array
    {
        return $this->where('id_livre', $idLivre)
            ->orderBy('date_mouvement', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function getDernierEmprunteur(int $idLivre): ?string
    {
        $mouvement = $this->where('id_livre', $idLivre)
            ->where('type_mouvement', 'EMPRUNT')
            ->orderBy('date_mouvement', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        return $mouvement['nom_emprunteur'] ?? null;
    }
}
