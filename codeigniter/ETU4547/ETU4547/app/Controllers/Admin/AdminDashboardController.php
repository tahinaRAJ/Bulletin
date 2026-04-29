<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmpruntModel;
use App\Models\LivreModel;
use App\Models\ReservationModel;

class AdminDashboardController extends BaseController
{
    private EmpruntModel $empruntModel;
    private ReservationModel $reservationModel;
    private LivreModel $livreModel;

    public function __construct()
    {
        $this->empruntModel = new EmpruntModel();
        $this->reservationModel = new ReservationModel();
        $this->livreModel = new LivreModel();
    }

    public function dashboard()
    {
        $user = session()->get('user');

        if (! $user || $user['role'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Acces reserve aux administrateurs');
        }

        $stats = $this->empruntModel->getStatistiques();
        $empruntEnRetard = $this->empruntModel->getEmpruntEnRetard();

        $livresPlusEmpruntes = $this->livreModel
            ->select('livres.*, COUNT(emprunts.id) as nb_emprunts')
            ->join('emprunts', 'emprunts.livre_id = livres.id', 'left')
            ->groupBy('livres.id')
            ->orderBy('nb_emprunts', 'DESC')
            ->limit(10)
            ->findAll();

        $emprunteurs = $this->empruntModel
            ->select('nom_emprunteur, COUNT(*) as nb_emprunts')
            ->groupBy('nom_emprunteur')
            ->orderBy('nb_emprunts', 'DESC')
            ->limit(10)
            ->findAll();

        $debutMois = date('Y-m-01');
        $finMois = date('Y-m-t');
        $empruntsMois = $this->empruntModel->builder()
            ->where('date_emprunt >=', $debutMois)
            ->where('date_emprunt <=', $finMois)
            ->countAllResults();

        $tauxRetard = 0.0;
        if (! empty($stats['total_emprunts'])) {
            $tauxRetard = ($stats['emprunts_retardes'] / $stats['total_emprunts']) * 100;
        }

        $reservationsEnAttente = $this->reservationModel->countAllResults();

        $chartLivresLabels = array_map(static fn (array $livre): string => (string) $livre['titre'], $livresPlusEmpruntes);
        $chartLivresValues = array_map(static fn (array $livre): int => (int) ($livre['nb_emprunts'] ?? 0), $livresPlusEmpruntes);

        $chartEmprunteursLabels = array_map(static fn (array $item): string => (string) $item['nom_emprunteur'], $emprunteurs);
        $chartEmprunteursValues = array_map(static fn (array $item): int => (int) $item['nb_emprunts'], $emprunteurs);

        return view('admin/dashboard', [
            'user' => $user,
            'stats' => $stats,
            'empruntEnRetard' => $empruntEnRetard,
            'livresPlusEmpruntes' => $livresPlusEmpruntes,
            'emprunteurs' => $emprunteurs,
            'empruntsMois' => $empruntsMois,
            'tauxRetard' => $tauxRetard,
            'reservationsEnAttente' => $reservationsEnAttente,
            'chartLivresLabels' => $chartLivresLabels,
            'chartLivresValues' => $chartLivresValues,
            'chartEmprunteursLabels' => $chartEmprunteursLabels,
            'chartEmprunteursValues' => $chartEmprunteursValues,
        ]);
    }
}
