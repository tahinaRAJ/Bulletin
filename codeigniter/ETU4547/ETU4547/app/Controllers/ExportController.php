<?php

namespace App\Controllers;

use App\Models\LivreModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportController extends BaseController
{
    private LivreModel $livreModel;

    public function __construct()
    {
        $this->livreModel = new LivreModel();
    }

    public function exporterEnCSV()
    {
        $livres = $this->livreModel->findAll();
        $rows = [];

        foreach ($livres as $livre) {
            $auteurs = $this->livreModel->getAuteursPourLivre((int) $livre['id']);
            $nomsAuteurs = array_map(static function (array $auteur): string {
                $prenom = trim((string) ($auteur['prenom'] ?? ''));
                $nom = trim((string) ($auteur['nom'] ?? ''));
                return trim($prenom . ' ' . $nom);
            }, $auteurs);

            if (empty($nomsAuteurs)) {
                $nomsAuteurs = [$livre['auteur'] ?? ''];
            }

            $notes = $this->livreModel->getNoeurstrait((int) $livre['id']);

            $rows[] = [
                $livre['titre'] ?? '',
                implode(', ', array_filter($nomsAuteurs)),
                $livre['isbn'] ?? '',
                $livre['categorie'] ?? '',
                $livre['annee_publication'] ?? '',
                $livre['statut'] ?? '',
                number_format((float) ($notes['moyenne'] ?? 0), 1),
                (int) ($notes['total'] ?? 0),
            ];
        }

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Titre', 'Auteurs', 'ISBN', 'Categorie', 'Annee', 'Statut', 'Note moyenne', 'Nb notes']);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="catalogue.csv"')
            ->setBody($csv);
    }

    public function exporterEnPDF()
    {
        if (! class_exists(Dompdf::class)) {
            return redirect()->back()->with('error', 'Dompdf n\'est pas installe. Lancez composer require dompdf/dompdf.');
        }

        $livres = $this->livreModel->findAll();
        $catalogue = [];

        foreach ($livres as $livre) {
            $auteurs = $this->livreModel->getAuteursPourLivre((int) $livre['id']);
            $nomsAuteurs = array_map(static function (array $auteur): string {
                $prenom = trim((string) ($auteur['prenom'] ?? ''));
                $nom = trim((string) ($auteur['nom'] ?? ''));
                return trim($prenom . ' ' . $nom);
            }, $auteurs);

            if (empty($nomsAuteurs)) {
                $nomsAuteurs = [$livre['auteur'] ?? ''];
            }

            $notes = $this->livreModel->getNoeurstrait((int) $livre['id']);

            $catalogue[] = [
                'titre' => $livre['titre'] ?? '',
                'auteurs' => implode(', ', array_filter($nomsAuteurs)),
                'isbn' => $livre['isbn'] ?? '',
                'categorie' => $livre['categorie'] ?? '',
                'annee' => $livre['annee_publication'] ?? '',
                'statut' => $livre['statut'] ?? '',
                'note' => number_format((float) ($notes['moyenne'] ?? 0), 1),
                'nb_notes' => (int) ($notes['total'] ?? 0),
            ];
        }

        $html = view('export/catalog_pdf', [
            'catalogue' => $catalogue,
            'date' => date('d/m/Y'),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="catalogue.pdf"')
            ->setBody($dompdf->output());
    }
}
