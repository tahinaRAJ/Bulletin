<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentaireModel extends Model
{
    protected $table = 'commentaires';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = ['livre_id', 'user_id', 'texte'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'livre_id' => 'required|integer',
        'user_id' => 'required|integer',
        'texte' => 'required|min_length[2]',
    ];

    public function getCommentairesRecents(int $livreId, int $limit = 10): array
    {
        return $this->select('commentaires.*, users.nom as user_nom')
            ->join('users', 'users.id = commentaires.user_id')
            ->where('commentaires.livre_id', $livreId)
            ->orderBy('commentaires.created_at', 'DESC')
            ->findAll($limit);
    }

    public function estAuteur(int $commentaireId, int $userId): bool
    {
        return $this->where('id', $commentaireId)
            ->where('user_id', $userId)
            ->countAllResults() > 0;
    }
}
