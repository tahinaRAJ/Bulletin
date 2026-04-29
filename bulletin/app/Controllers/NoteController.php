<?php

namespace App\Controllers;

use App\Models\EtuModel;
use App\Models\NoteModel;

class NoteController extends BaseController
{
    /**
     * Afficher le formulaire d'insertion de notes
     */
    public function index()
    {
        // Vérifier la session
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $etuModel = new EtuModel();
        $noteModel = new NoteModel();

        $etudiants = $etuModel->findAllEtudiants();
        $matieres = $noteModel->getAllMatieres();

        return view('note/insert', [
            'etudiants' => $etudiants,
            'matieres' => $matieres,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ]);
    }

    /**
     * Insérer une note
     */
    public function insererNote()
    {
        // Vérifier la session
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $id_etu = $this->request->getPost('id_etu');
        $id_matiere = $this->request->getPost('id_matiere');
        $note = $this->request->getPost('note');

        // Valider les entrées
        if (!$id_etu || !$id_matiere || !$note) {
            return redirect()->back()
                ->with('error', 'Tous les champs sont requis')
                ->withInput();
        }

        $noteModel = new NoteModel();
        $result = $noteModel->insererNote($id_etu, $id_matiere, $note);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message'])
                ->withInput();
        }
    }

    /**
     * Afficher la fiche d'un étudiant avec ses notes
     */
    public function ficheEtu($id)
    {
        // Vérifier la session
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $etuModel = new EtuModel();
        $noteModel = new NoteModel();

        $etudiant = $etuModel->getEtudiant($id);

        if (!$etudiant) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $notes = $noteModel->findAllNotesByOption($id);
        $moyennesSemestre = $noteModel->findAllMoyenneBySemestre($id);
        $moyenneAnnee = $noteModel->findAllMoyenneByYear($id);

        return view('note/fiche_etu', [
            'etudiant' => $etudiant,
            'notes' => $notes,
            'moyennesSemestre' => $moyennesSemestre,
            'moyenneAnnee' => $moyenneAnnee
        ]);
    }

    /**
     * Supprimer une note
     */
    public function supprimerNote($id_note, $id_etu)
    {
        // Vérifier la session
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        if (empty($id_note) || !ctype_digit((string) $id_note)) {
            return redirect()->to("/etud/{$id_etu}")->with('error', 'Aucune note à supprimer');
        }

        $noteModel = new NoteModel();
        $noteModel->supprimerNote($id_note);

        return redirect()->to("/etud/{$id_etu}")->with('success', 'Note supprimée');
    }
}
