<?php

namespace App\Controllers;

use App\Models\AuteurModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AuteurController extends BaseController
{
    private AuteurModel $auteurModel;

    public function __construct()
    {
        $this->auteurModel = new AuteurModel();
    }

    public function listeLivresParAuteur(int $auteurId)
    {
        $auteur = $this->auteurModel->find($auteurId);

        if ($auteur === null) {
            throw PageNotFoundException::forPageNotFound('Auteur introuvable.');
        }

        $livres = $this->auteurModel->getLivresParAuteur($auteurId);

        return view('auteur/show', [
            'auteur' => $auteur,
            'livres' => $livres,
        ]);
    }
}
