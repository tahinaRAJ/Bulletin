<?php

namespace App\Controllers;

use App\Models\EmpruntModel;
use App\Models\LivreModel;

class UserController extends BaseController
{
    protected $empruntModel;
    protected $livreModel;

    public function __construct()
    {
        $this->empruntModel = new EmpruntModel();
        $this->livreModel = new LivreModel();
    }

    /**
     * Afficher le profil de l'utilisateur avec historique d'emprunts
     */
    public function profile()
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        // Récupérer l'historique des emprunts de l'utilisateur
        $emprunts = $this->empruntModel
            ->where('nom_emprunteur', $user['nom'])
            ->orderBy('date_emprunt', 'DESC')
            ->findAll();

        // Enrichir avec les infos du livre
        foreach ($emprunts as &$emprunt) {
            $livre = $this->livreModel->find($emprunt['livre_id']);
            $emprunt['livre'] = $livre;
        }

        return view('user/profile', [
            'user'     => $user,
            'emprunts' => $emprunts,
        ]);
    }
}
