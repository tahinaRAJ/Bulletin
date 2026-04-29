<?php

namespace App\Models;

use CodeIgniter\Model;
use InvalidArgumentException;

class LivreModel extends Model
{
    private const SORTABLE_COLUMNS = ['titre', 'auteur', 'annee_publication'];

    protected $table = 'livres';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'titre',
        'auteur',
        'isbn',
        'annee_publication',
        'categorie',
        'resume',
        'couverture',
        'statut',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'titre' => 'required|min_length[3]',
        'auteur' => 'required',
        'isbn' => 'required|is_unique[livres.isbn,id,{id}]',
        'annee_publication' => 'required|integer',
    ];

    protected $validationMessages = [
        'titre' => [
            'required' => 'Le titre est obligatoire.',
            'min_length' => 'Le titre doit contenir au moins 3 caracteres.',
        ],
        'auteur' => [
            'required' => "L'auteur est obligatoire.",
        ],
        'isbn' => [
            'required' => "L'ISBN est obligatoire.",
            'is_unique' => "L'ISBN existe deja en base de donnees.",
        ],
        'annee_publication' => [
            'required' => "L'annee de publication est obligatoire.",
            'integer' => "L'annee de publication doit etre un nombre entier.",
        ],
    ];

    protected $beforeInsert = ['validerAnneePublicationAvantEcriture'];
    protected $beforeUpdate = ['validerAnneePublicationAvantEcriture'];

    public function anneePublicationValide(int $annee): bool
    {
        return $annee <= (int) date('Y');
    }

    public function rechercher(?string $motCle = null, ?string $categorie = null, string $sort = 'titre', string $order = 'asc'): array
    {
        $builder = $this->builder();
        $builder->select('*');

        $motCle = trim((string) $motCle);
        $categorie = trim((string) $categorie);

        if ($motCle !== '') {
            $builder->like('titre', $motCle);
        }

        if ($categorie !== '') {
            $builder->where('categorie', $categorie);
        }

        $this->appliquerTri($builder, $sort, $order);

        return $builder->get()->getResultArray();
    }

    public function getLivresPagines(int $parPage = 10, string $sort = 'titre', string $order = 'asc'): array
    {
        $sortColonne = $this->normaliserTri($sort);
        $direction = $this->normaliserOrdre($order);

        return $this->orderBy($sortColonne, $direction)->paginate($parPage);
    }

    public function getAuteursPourLivre(int $livreId): array
    {
        return $this->db->table('auteurs')
            ->select('auteurs.*')
            ->join('livre_auteur', 'livre_auteur.auteur_id = auteurs.id')
            ->where('livre_auteur.livre_id', $livreId)
            ->orderBy('auteurs.nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getNoeurstrait(int $livreId): array
    {
        $resultat = $this->db->table('notations')
            ->select('AVG(note) as moyenne, COUNT(*) as total')
            ->where('livre_id', $livreId)
            ->get()
            ->getRowArray();

        return [
            'moyenne' => $resultat['moyenne'] !== null ? (float) $resultat['moyenne'] : 0.0,
            'total' => $resultat['total'] !== null ? (int) $resultat['total'] : 0,
        ];
    }

    public function getCommentairesRecents(int $livreId, int $limit = 5): array
    {
        return $this->db->table('commentaires')
            ->select('commentaires.*, users.nom as user_nom')
            ->join('users', 'users.id = commentaires.user_id')
            ->where('commentaires.livre_id', $livreId)
            ->orderBy('commentaires.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    protected function validerAnneePublicationAvantEcriture(array $data): array
    {
        if (! isset($data['data']['annee_publication'])) {
            return $data;
        }

        $annee = (int) $data['data']['annee_publication'];

        if (! $this->anneePublicationValide($annee)) {
            throw new InvalidArgumentException("L'annee de publication ne peut pas etre dans le futur.");
        }

        return $data;
    }

    private function appliquerTri($builder, string $sort, string $order): void
    {
        $sortColonne = $this->normaliserTri($sort);
        $direction = $this->normaliserOrdre($order);
        $builder->orderBy($sortColonne, $direction);
    }

    private function normaliserTri(string $sort): string
    {
        $sort = trim($sort);

        if (! in_array($sort, self::SORTABLE_COLUMNS, true)) {
            return 'titre';
        }

        return $sort;
    }

    private function normaliserOrdre(string $order): string
    {
        $order = strtolower(trim($order));

        if (! in_array($order, ['asc', 'desc'], true)) {
            return 'asc';
        }

        return $order;
    }
}
