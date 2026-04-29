<?php

namespace App\Controllers;

use App\Models\EtuModel;

class EtuController extends BaseController
{
    /**
     * Afficher la liste de tous les étudiants
     */
    public function index()
    {
        // Vérifier la session
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $etuModel = new EtuModel();
        $etudiants = $etuModel->findAllEtudiants();

        return view('etudiant/list', [
            'etudiants' => $etudiants
        ]);
    }
}
