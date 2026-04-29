<?php

namespace App\Controllers;

use App\Models\EmpruntModel;
use App\Models\LivreModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class MouvementController extends BaseController
{
    private const ROUTE_LIVRES = '/livres/';
    private const MESSAGE_LIVRE_INTROUVABLE = 'Livre introuvable.';

    private LivreModel $livreModel;
    private EmpruntModel $empruntModel;

    public function __construct()
    {
        $this->livreModel = new LivreModel();
        $this->empruntModel = new EmpruntModel();
    }

    public function loan(int $id)
    {
        $livre = $this->livreModel->find($id);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound(self::MESSAGE_LIVRE_INTROUVABLE);
        }

        if (($livre['statut'] ?? 'disponible') !== 'disponible') {
            return redirect()->to(self::ROUTE_LIVRES . $id)->with('error', 'Ce livre n est pas disponible.');
        }

        $rules = [
            'nom_emprunteur' => 'required|min_length[2]|max_length[255]',
        ];

        $messages = [
            'nom_emprunteur' => [
                'required' => "Le nom de l emprunteur est requis.",
                'min_length' => "Le nom de l emprunteur doit contenir au moins 2 caracteres.",
            ],
        ];

        if (! $this->validateData($this->request->getPost(), $rules, $messages)) {
            return redirect()->to(self::ROUTE_LIVRES . $id)->withInput()->with('validation', $this->validator);
        }

        $nomEmprunteur = trim((string) $this->request->getPost('nom_emprunteur'));

        // Créer l'emprunt avec date retour prévue calculée
        $this->empruntModel->creerEmprunt($id, $nomEmprunteur);

        // Marquer le livre comme prêté
        $this->livreModel->update($id, [
            'statut' => 'prete',
        ]);

        return redirect()->to(self::ROUTE_LIVRES . $id)->with('success', 'Pret enregistre avec succes (Retour prévu dans 15 jours).');
    }

    public function returnBook(int $id)
    {
        $livre = $this->livreModel->find($id);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound(self::MESSAGE_LIVRE_INTROUVABLE);
        }

        $empruntActif = $this->empruntModel
            ->where('livre_id', $id)
            ->where('statut', 'actif')
            ->orderBy('date_emprunt', 'DESC')
            ->first();

        if ($empruntActif === null) {
            return redirect()->to(self::ROUTE_LIVRES . $id)->with('error', 'Aucun emprunt actif trouve pour ce livre.');
        }

        // Marquer comme retourné et calculer les retards
        $this->empruntModel->marquerCommeRetourne($empruntActif['id']);

        // Marquer le livre comme disponible
        $this->livreModel->update($id, [
            'statut' => 'disponible',
        ]);

        return redirect()->to(self::ROUTE_LIVRES . $id)->with('success', 'Retour enregistre avec succes.');
    }
}
