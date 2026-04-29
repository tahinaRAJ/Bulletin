<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmpruntModel;
use App\Models\ReservationModel;
use App\Models\LivreModel;

class AdminController extends BaseController
{
    protected $empruntModel;
    protected $reservationModel;
    protected $livreModel;

    public function __construct()
    {
        $this->empruntModel = new EmpruntModel();
        $this->reservationModel = new ReservationModel();
        $this->livreModel = new LivreModel();
    }

    /**
     * Afficher le tableau de bord admin
     */
    public function dashboard()
    {
        $user = session()->get('user');

        if (!$user || $user['role'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès réservé aux administrateurs');
        }

        // Statistiques
        $stats = $this->empruntModel->getStatistiques();
        $empruntEnRetard = $this->empruntModel->getEmpruntEnRetard();

        // Emprunts les plus actifs (par nombre d'emprunts)
        $livresPlusEmpruntes = $this->livreModel
            ->select('livres.*, COUNT(emprunts.id) as nb_emprunts')
            ->join('emprunts', 'emprunts.livre_id = livres.id', 'left')
            ->groupBy('livres.id')
            ->orderBy('nb_emprunts', 'DESC')
            ->limit(10)
            ->findAll();

        // Emprunteurs les plus actifs (par nombre d'emprunts)
        $emprunteurs = $this->empruntModel
            ->select('nom_emprunteur, COUNT(*) as nb_emprunts')
            ->groupBy('nom_emprunteur')
            ->orderBy('nb_emprunts', 'DESC')
            ->limit(10)
            ->findAll();

        return view('admin/dashboard', [
            'user'                => $user,
            'stats'               => $stats,
            'empruntEnRetard'     => $empruntEnRetard,
            'livresPlusEmpruntes' => $livresPlusEmpruntes,
            'emprunteurs'         => $emprunteurs,
        ]);
    }

    /**
     * Afficher la liste des emprunts en retard
     */
    public function listeEmpruntEnRetard()
    {
        $user = session()->get('user');

        if (!$user || $user['role'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès réservé aux administrateurs');
        }

        $empruntEnRetard = $this->empruntModel->getEmpruntEnRetard();

        return view('admin/emprunts_retard', [
            'user'              => $user,
            'empruntEnRetard'   => $empruntEnRetard,
        ]);
    }

    /**
     * Envoyer des notifications (simulé) aux emprunteurs en retard
     */
    public function envoiRelances()
    {
        $user = session()->get('user');

        if (!$user || $user['role'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès réservé aux administrateurs');
        }

        $empruntEnRetard = $this->empruntModel->getEmpruntEnRetard();

        // Simuler l'envoi d'emails (en production, utiliser phpMailer ou SendGrid)
        $messages = [];
        foreach ($empruntEnRetard as $emprunt) {
            $messages[] = "📧 Relance envoyée à {$emprunt['nom_emprunteur']} pour '{$emprunt['titre']}' (en retard de {$emprunt['jours_retard']} jours)";
        }

        return redirect()->back()->with('success', implode(' | ', $messages));
    }

    /**
     * Voir les réservations en attente
     */
    public function reservationEnAttente()
    {
        $user = session()->get('user');

        if (!$user || $user['role'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès réservé aux administrateurs');
        }

        // Obtenir toutes les réservations groupées par livre
        $db = \Config\Database::connect();
        $reservations = $db
            ->table('reservations')
            ->select('reservations.*, livres.titre, livres.auteur, users.nom, users.email')
            ->join('livres', 'livres.id = reservations.livre_id')
            ->join('users', 'users.id = reservations.user_id')
            ->orderBy('reservations.position_file', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/reservations', [
            'user'          => $user,
            'reservations'  => $reservations,
        ]);
    }
}
