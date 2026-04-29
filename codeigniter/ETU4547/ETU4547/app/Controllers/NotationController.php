<?php

namespace App\Controllers;

use App\Models\LivreModel;
use App\Models\NotationModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class NotationController extends BaseController
{
    private NotationModel $notationModel;
    private LivreModel $livreModel;

    public function __construct()
    {
        $this->notationModel = new NotationModel();
        $this->livreModel = new LivreModel();
    }

    public function noter(int $livreId)
    {
        $user = session()->get('user');

        if (! $user) {
            return redirect()->to('/auth/login');
        }

        $livre = $this->livreModel->find($livreId);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound('Livre introuvable.');
        }

        $note = (int) $this->request->getPost('note');

        if ($note < 1 || $note > 5) {
            return redirect()->back()->with('error', 'La note doit etre comprise entre 1 et 5.');
        }

        $ok = $this->notationModel->upsertNote($livreId, (int) $user['id'], $note);

        if (! $ok) {
            return redirect()->back()->with('error', 'Impossible d\'enregistrer la note.');
        }

        return redirect()->back()->with('success', 'Note enregistree.');
    }
}
