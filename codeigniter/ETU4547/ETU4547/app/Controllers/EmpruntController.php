<?php

namespace App\Controllers;

use App\Models\EmpruntModel;
use App\Models\LivreModel;

class EmpruntController extends BaseController
{
    protected $empruntModel;
    protected $livreModel;

    public function __construct()
    {
        $this->empruntModel = new EmpruntModel();
        $this->livreModel = new LivreModel();
    }

    /**
     * Afficher l'historique des emprunts de l'utilisateur
     */
    public function historiquePourUtilisateur()
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        // Récupérer l'historique complet
        $historique = $this->empruntModel->getHistoriqueParEmprunteur($user['nom']);

        // Séparer les emprunts actifs et passés
        $empruntActifs = array_filter($historique, fn($e) => $e['statut'] === 'actif');
        $empruntPasses = array_filter($historique, fn($e) => $e['statut'] !== 'actif');

        return view('emprunt/historique', [
            'user'            => $user,
            'empruntActifs'   => $empruntActifs,
            'empruntPasses'   => $empruntPasses,
        ]);
    }

    /**
     * Afficher les détails d'un emprunt
     */
    public function detailsEmprunt(int $empruntId)
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        $emprunt = $this->empruntModel->find($empruntId);

        if (!$emprunt) {
            return redirect()->back()->with('error', 'Emprunt introuvable');
        }

        // Vérifier que l'emprunt appartient à l'utilisateur
        if ($emprunt['nom_emprunteur'] !== $user['nom']) {
            return redirect()->back()->with('error', 'Accès refusé');
        }

        $livre = $this->livreModel->find($emprunt['livre_id']);

        return view('emprunt/details', [
            'emprunt' => $emprunt,
            'livre'   => $livre,
        ]);
    }
}
