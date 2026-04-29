<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['livre_id', 'user_id', 'date_reservation', 'position_file'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Créer une réservation pour un livre
     */
    public function creerReservation(int $livreId, int $userId) : bool
    {
        // Calculer la position dans la file
        $position = $this->where('livre_id', $livreId)
            ->countAllResults() + 1;

        return $this->insert([
            'livre_id'           => $livreId,
            'user_id'            => $userId,
            'date_reservation'   => date('Y-m-d H:i:s'),
            'position_file'      => $position,
        ]);
    }

    /**
     * Obtenir la file d'attente pour un livre
     */
    public function getFileAttentePourLivre(int $livreId) : array
    {
        return $this->where('livre_id', $livreId)
            ->orderBy('position_file', 'ASC')
            ->findAll();
    }

    /**
     * Obtenir les réservations d'un utilisateur
     */
    public function getReservationsUtilisateur(int $userId) : array
    {
        return $this->select('reservations.*, livres.titre, livres.auteur')
            ->join('livres', 'livres.id = reservations.livre_id')
            ->where('user_id', $userId)
            ->orderBy('reservations.position_file', 'ASC')
            ->findAll();
    }

    /**
     * Annuler une réservation
     */
    public function annulerReservation(int $reservationId) : bool
    {
        $reservation = $this->find($reservationId);

        if (!$reservation) {
            return false;
        }

        // Supprimer la réservation
        $this->delete($reservationId);

        // Réajuster les positions des réservations suivantes
        $this->where('livre_id', $reservation['livre_id'])
            ->where('position_file >', $reservation['position_file'])
            ->set('position_file = position_file - 1', false)
            ->update();

        return true;
    }

    /**
     * Obtenir la première réservation (prochain à avertir)
     */
    public function getProchainePourLivre(int $livreId) : ?array
    {
        return $this->where('livre_id', $livreId)
            ->orderBy('position_file', 'ASC')
            ->first();
    }
}
