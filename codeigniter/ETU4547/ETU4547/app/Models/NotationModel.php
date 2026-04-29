<?php

namespace App\Models;

use CodeIgniter\Model;

class NotationModel extends Model
{
    protected $table = 'notations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = ['livre_id', 'user_id', 'note'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    protected $validationRules = [
        'livre_id' => 'required|integer',
        'user_id' => 'required|integer',
        'note' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
    ];

    public function upsertNote(int $livreId, int $userId, int $note): bool
    {
        $note = max(1, min(5, $note));

        $existant = $this->where('livre_id', $livreId)
            ->where('user_id', $userId)
            ->first();

        if ($existant) {
            return (bool) $this->update($existant['id'], [
                'note' => $note,
            ]);
        }

        return (bool) $this->insert([
            'livre_id' => $livreId,
            'user_id' => $userId,
            'note' => $note,
        ]);
    }

    public function getMoyennePourLivre(int $livreId): array
    {
        $resultat = $this->select('AVG(note) as moyenne, COUNT(*) as total')
            ->where('livre_id', $livreId)
            ->first();

        return [
            'moyenne' => $resultat['moyenne'] !== null ? (float) $resultat['moyenne'] : 0.0,
            'total' => $resultat['total'] !== null ? (int) $resultat['total'] : 0,
        ];
    }
}
