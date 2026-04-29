<?php

namespace App\Controllers;

use App\Models\EtuModel;

class AuthController extends BaseController
{
    /**
     * Afficher la page de login
     */
    public function index()
    {
        return view('auth/login');
    }

    /**
     * Vérifier les credentials et créer une session
     */
    public function verifier()
    {
        $nom = $this->request->getPost('nom');
        $mdp = $this->request->getPost('mdp');

        if (!$nom || !$mdp) {
            return redirect()->back()->with('error', 'Nom et mot de passe requis');
        }

        $db = \Config\Database::connect();
        $user = $db->table('user')
            ->where('nom', $nom)
            ->where('mdp', $mdp)
            ->get()
            ->getRowArray();

        if ($user) {
            // Créer la session
            session()->set('user_id', $user['id']);
            session()->set('user_nom', $user['nom']);
            return redirect()->to('/list');
        } else {
            return redirect()->back()
                ->with('error', 'Identifiants invalides')
                ->withInput();
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
