<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\LivreModel;

class ReservationController extends BaseController
{
    protected $reservationModel;
    protected $livreModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->livreModel = new LivreModel();
    }

    /**
     * Créer une réservation pour un livre
     */
    public function creerReservation(int $livreId)
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        $livre = $this->livreModel->find($livreId);

        if (!$livre) {
            return redirect()->back()->with('error', 'Livre introuvable');
        }

        // Vérifier si l'utilisateur a déjà une réservation pour ce livre
        $existant = $this->reservationModel
            ->where('livre_id', $livreId)
            ->where('user_id', $user['id'])
            ->first();

        if ($existant) {
            return redirect()->back()->with('error', 'Vous avez déjà une réservation pour ce livre');
        }

        // Créer la réservation
        if ($this->reservationModel->creerReservation($livreId, $user['id'])) {
            return redirect()->back()->with('success', 'Réservation créée avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la création de la réservation');
    }

    /**
     * Afficher mes réservations
     */
    public function mesReservations()
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        $reservations = $this->reservationModel->getReservationsUtilisateur($user['id']);

        return view('reservation/list', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Annuler une réservation
     */
    public function annulerReservation(int $reservationId)
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        $reservation = $this->reservationModel->find($reservationId);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation introuvable');
        }

        // Vérifier que c'est la réservation de l'utilisateur
        if ($reservation['user_id'] !== $user['id']) {
            return redirect()->back()->with('error', 'Accès refusé');
        }

        if ($this->reservationModel->annulerReservation($reservationId)) {
            return redirect()->back()->with('success', 'Réservation annulée avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'annulation');
    }
}
