<?php

namespace App\Controllers;

use App\Models\CommentaireModel;
use App\Models\LivreModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CommentaireController extends BaseController
{
    private CommentaireModel $commentaireModel;
    private LivreModel $livreModel;

    public function __construct()
    {
        $this->commentaireModel = new CommentaireModel();
        $this->livreModel = new LivreModel();
    }

    public function ajouter(int $livreId)
    {
        $user = session()->get('user');

        if (! $user) {
            return redirect()->to('/auth/login');
        }

        $livre = $this->livreModel->find($livreId);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound('Livre introuvable.');
        }

        $texte = trim((string) $this->request->getPost('texte'));

        if ($texte === '') {
            return redirect()->back()->with('error', 'Le commentaire est obligatoire.');
        }

        $ok = $this->commentaireModel->insert([
            'livre_id' => $livreId,
            'user_id' => (int) $user['id'],
            'texte' => $texte,
        ]);

        if (! $ok) {
            return redirect()->back()->with('error', 'Impossible d\'ajouter le commentaire.');
        }

        return redirect()->back()->with('success', 'Commentaire ajoute.');
    }

    public function supprimer(int $commentaireId)
    {
        $user = session()->get('user');

        if (! $user) {
            return redirect()->to('/auth/login');
        }

        if (! $this->commentaireModel->estAuteur($commentaireId, (int) $user['id'])) {
            return redirect()->back()->with('error', 'Acces refuse.');
        }

        $this->commentaireModel->delete($commentaireId);

        return redirect()->back()->with('success', 'Commentaire supprime.');
    }
}
