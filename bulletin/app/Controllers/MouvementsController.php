<?php

namespace App\Controllers;

use App\Models\LivreModel;
use App\Models\MouvementLivreModel;
use CodeIgniter\HTTP\RedirectResponse;

class MouvementsController extends BaseController
{
    private LivreModel $livreModel;
    private MouvementLivreModel $mouvementModel;

    public function __construct()
    {
        $this->livreModel = new LivreModel();
        $this->mouvementModel = new MouvementLivreModel();
    }

    public function emprunter(int $idLivre): RedirectResponse
    {
        if ($this->livreModel->find($idLivre) === null) {
            return redirect()->back()->with('error', 'Livre introuvable.');
        }

        $data = [
            'id_livre' => $idLivre,
            'nom_emprunteur' => trim((string) $this->request->getPost('nom_emprunteur')),
        ];

        if (! $this->validateData($data, config('Validation')->empruntRules, config('Validation')->empruntErrors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->mouvementModel->getEtatCourantLivre($idLivre) !== 'DISPONIBLE') {
            return redirect()->back()->with('error', 'Ce livre est deja emprunte.');
        }

        $this->mouvementModel->insert([
            'id_livre' => $idLivre,
            'type_mouvement' => 'EMPRUNT',
            'nom_emprunteur' => $data['nom_emprunteur'],
            'date_mouvement' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Emprunt enregistre avec succes.');
    }

    public function retour(int $idLivre): RedirectResponse
    {
        if ($this->livreModel->find($idLivre) === null) {
            return redirect()->back()->with('error', 'Livre introuvable.');
        }

        if (! $this->validateData(['id_livre' => $idLivre], config('Validation')->retourRules)) {
            return redirect()->back()->with('error', 'Demande de retour invalide.');
        }

        $dernier = $this->mouvementModel->getDernierMouvementLivre($idLivre);
        if ($dernier === null || $dernier['type_mouvement'] !== 'EMPRUNT') {
            return redirect()->back()->with('error', 'Retour impossible: le livre est deja disponible.');
        }

        $this->mouvementModel->insert([
            'id_livre' => $idLivre,
            'type_mouvement' => 'RETOUR',
            'nom_emprunteur' => null,
            'date_mouvement' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Retour enregistre avec succes.');
    }
}
