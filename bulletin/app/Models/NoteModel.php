<?php

namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $table = 'note';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_etu', 'id_matiere', 'note'];

    /**
     * Insérer une note pour un étudiant
     */
    public function insererNote($id_etu, $id_matiere, $note)
    {
        // Valider la note
        if ($note < 0 || $note > 20 || !is_numeric($note)) {
            return ['success' => false, 'message' => 'Note invalide (0-20)'];
        }

        // Insérer dans la BD
        $data = [
            'id_etu' => $id_etu,
            'id_matiere' => $id_matiere,
            'note' => $note
        ];

        if ($this->insert($data)) {
            return ['success' => true, 'message' => 'Note insérée avec succès'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de l\'insertion'];
        }
    }

    /**
     * Récupérer les notes d'un étudiant par option (S3/S4)
     */
    public function findAllNotesByOption($id_etu)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                nmax.id,
                ? AS id_etu,
                COALESCE(nmax.note, 0) AS note,
                m.id AS id_matiere,
                m.coef,
                m.id_semestre,
                m.nom AS matiere_nom,
                m.numero,
                m.optional,
                CASE 
                    WHEN m.id_semestre = 1 THEN NULL  
                    WHEN m.optional = 0 THEN NULL      
                    WHEN m.numero = 9 THEN 'dev'
                    WHEN m.numero = 10 THEN 'bddres'
                    WHEN m.numero = 11 THEN 'web'
                    ELSE NULL
                END AS option_label
            FROM matiere m
            LEFT JOIN (
                SELECT n1.id, n1.id_etu, n1.id_matiere, n1.note
                FROM note n1
                INNER JOIN (
                    SELECT id_etu, id_matiere, MAX(note) AS max_note
                    FROM note
                    WHERE id_etu = ?
                    GROUP BY id_etu, id_matiere
                ) nmax_note ON nmax_note.id_etu = n1.id_etu
                    AND nmax_note.id_matiere = n1.id_matiere
                    AND nmax_note.max_note = n1.note
            ) nmax ON nmax.id_matiere = m.id AND nmax.id_etu = ?
            WHERE m.id_semestre IN (1, 2)
            ORDER BY m.id_semestre, m.optional, m.numero
        ", [$id_etu, $id_etu, $id_etu]);
        
        return $query->getResultArray();
    }

    /**
     * Récupérer les moyennes par semestre
     */
    public function findAllMoyenneBySemestre($id_etu)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                m.id_semestre,
                CASE m.id_semestre
                    WHEN 1 THEN 'S3'
                    WHEN 2 THEN 'S4'
                END AS semestre,
                ROUND(SUM(COALESCE(nmax.note, 0) * m.coef) / NULLIF(SUM(m.coef), 0), 2) AS moyenne
            FROM matiere m
            LEFT JOIN (
                SELECT n1.id_etu, n1.id_matiere, n1.note
                FROM note n1
                INNER JOIN (
                    SELECT id_etu, id_matiere, MAX(note) AS max_note
                    FROM note
                    WHERE id_etu = ?
                    GROUP BY id_etu, id_matiere
                ) nmax_note ON nmax_note.id_etu = n1.id_etu
                    AND nmax_note.id_matiere = n1.id_matiere
                    AND nmax_note.max_note = n1.note
            ) nmax ON nmax.id_matiere = m.id AND nmax.id_etu = ?
            WHERE m.id_semestre IN (1, 2)
            GROUP BY m.id_semestre
        ", [$id_etu, $id_etu]);

        return $query->getResultArray();
    }

    /**
     * Récupérer la moyenne annuelle (L2)
     */
    public function findAllMoyenneByYear($id_etu)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                ROUND(AVG(moyenne), 2) AS moyenne_annee
            FROM (
                SELECT 
                    m.id_semestre,
                    ROUND(SUM(COALESCE(nmax.note, 0) * m.coef) / NULLIF(SUM(m.coef), 0), 2) AS moyenne
                FROM matiere m
                LEFT JOIN (
                    SELECT n1.id_etu, n1.id_matiere, n1.note
                    FROM note n1
                    INNER JOIN (
                        SELECT id_etu, id_matiere, MAX(note) AS max_note
                        FROM note
                        WHERE id_etu = ?
                        GROUP BY id_etu, id_matiere
                    ) nmax_note ON nmax_note.id_etu = n1.id_etu
                        AND nmax_note.id_matiere = n1.id_matiere
                        AND nmax_note.max_note = n1.note
                ) nmax ON nmax.id_matiere = m.id AND nmax.id_etu = ?
                WHERE m.id_semestre IN (1, 2)
                GROUP BY m.id_semestre
            ) AS moyennes
        ", [$id_etu, $id_etu]);

        $result = $query->getRowArray();
        return $result['moyenne_annee'] ?? 0;
    }

    /**
     * Supprimer une note
     */
    public function supprimerNote($id_note)
    {
        return $this->delete($id_note);
    }

    /**
     * Récupérer toutes les matières
     */
    public function getAllMatieres()
    {
        $db = \Config\Database::connect();
        $query = $db->table('matiere')
            ->select('matiere.id, matiere.nom, matiere.numero, matiere.coef, matiere.id_semestre, matiere.optional, semestre.label as semestre_label')
            ->join('semestre', 'semestre.id = matiere.id_semestre')
            ->get();
        
        return $query->getResultArray();
    }

    /**
     * Récupérer les notes brutes (toutes) d'un étudiant pour une matière
     */
    public function getNotesByMatiereAndEtu($id_etu, $id_matiere)
    {
        return $this->where('id_etu', $id_etu)
                    ->where('id_matiere', $id_matiere)
                    ->orderBy('note', 'DESC')
                    ->findAll();
    }
}
