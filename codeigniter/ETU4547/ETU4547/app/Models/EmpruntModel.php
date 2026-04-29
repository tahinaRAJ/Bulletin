<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpruntModel extends Model
{
    protected $table = 'emprunts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'livre_id',
        'nom_emprunteur',
        'date_emprunt',
        'date_retour',
        'date_retour_prevue',
        'statut',
        'jours_retard',
    ];

    // Configuration
    private const NB_JOURS_EMPRUNT = 15;

    /**
     * Obtenir le dernier emprunt pour un livre
     */
    public function getDernierEmpruntPourLivre(int $livreId): ?array
    {
        return $this->where('livre_id', $livreId)
            ->orderBy('date_emprunt', 'DESC')
            ->first();
    }

    /**
     * Créer un emprunt avec date retour prévue
     */
    public function creerEmprunt(int $livreId, string $nomEmprunteur): bool
    {
        $dateRetourPrevue = date('Y-m-d', strtotime('+' . self::NB_JOURS_EMPRUNT . ' days'));

        return $this->insert([
            'livre_id'            => $livreId,
            'nom_emprunteur'      => $nomEmprunteur,
            'date_emprunt'        => date('Y-m-d'),
            'date_retour_prevue'  => $dateRetourPrevue,
            'statut'              => 'actif',
        ]);
    }

    /**
     * Obtenir les emprunts en retard
     */
    public function getEmpruntEnRetard(): array
    {
        $today = date('Y-m-d');

        return $this->select('emprunts.*, livres.titre, livres.auteur')
            ->join('livres', 'livres.id = emprunts.livre_id')
            ->where('statut', 'actif')
            ->where('date_retour_prevue <', $today)
            ->where('date_retour IS NULL', null, false)
            ->orderBy('date_retour_prevue', 'ASC')
            ->findAll();
    }

    /**
     * Historique des emprunts pour un livre
     */
    public function getHistoriqueParLivre(int $livreId): array
    {
        return $this->where('livre_id', $livreId)
            ->orderBy('date_emprunt', 'DESC')
            ->findAll();
    }

    /**
     * Historique des emprunts pour un emprunteur (par nom)
     */
    public function getHistoriqueParEmprunteur(string $nomEmprunteur): array
    {
        return $this->select('emprunts.*, livres.titre, livres.auteur')
            ->join('livres', 'livres.id = emprunts.livre_id')
            ->where('nom_emprunteur', $nomEmprunteur)
            ->orderBy('date_emprunt', 'DESC')
            ->findAll();
    }

    /**
     * Markmarquer un emprunt comme retourné et calculer les retards
     */
    public function marquerCommeRetourne(int $empruntId): bool
    {
        $emprunt = $this->find($empruntId);

        if (!$emprunt) {
            return false;
        }

        $dateRetour = date('Y-m-d');
        $jours_retard = 0;
        $statut = 'retourne';

        // Calculer les jours de retard
        if ($dateRetour > $emprunt['date_retour_prevue']) {
            $datetime1 = new \DateTime($emprunt['date_retour_prevue']);
            $datetime2 = new \DateTime($dateRetour);
            $interval = $datetime1->diff($datetime2);
            $jours_retard = $interval->days;
            $statut = 'retard';
        }

        return $this->update($empruntId, [
            'date_retour'  => $dateRetour,
            'statut'       => $statut,
            'jours_retard' => $jours_retard > 0 ? $jours_retard : null,
        ]);
    }

    /**
     * Obtenir les emprunts actifs d'un utilisateur
     */
    public function getEmpruntActifParUtilisateur(string $nomEmprunteur): array
    {
        return $this->select('emprunts.*, livres.titre, livres.auteur')
            ->join('livres', 'livres.id = emprunts.livre_id')
            ->where('nom_emprunteur', $nomEmprunteur)
            ->where('statut', 'actif')
            ->orderBy('date_retour_prevue', 'ASC')
            ->findAll();
    }

    /**
     * Obtenir les statistiques d'emprunts
     */
    public function getStatistiques(): array
    {
        return [
            'total_emprunts'        => $this->countAllResults(),
            'emprunts_actifs'       => $this->where('statut', 'actif')->countAllResults(),
            'emprunts_retardes'     => $this->where('statut', 'retard')->countAllResults(),
            'emprunts_retournes'    => $this->where('statut', 'retourne')->countAllResults(),
        ];
    }
}
