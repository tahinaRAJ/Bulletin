<?php

namespace App\Controllers;

use App\Models\AuteurModel;
use App\Models\EmpruntModel;
use App\Models\LivreModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class LibraryController extends BaseController
{
    private const ROUTE_LIVRES = '/livres/';
    private const MESSAGE_LIVRE_INTROUVABLE = 'Livre introuvable.';

    private LivreModel $livreModel;
    private EmpruntModel $empruntModel;
    private AuteurModel $auteurModel;

    public function __construct()
    {
        $this->livreModel = new LivreModel();
        $this->empruntModel = new EmpruntModel();
        $this->auteurModel = new AuteurModel();
    }

    public function index()
    {
        $motCle = trim((string) $this->request->getGet('mot_cle'));
        $categorie = trim((string) $this->request->getGet('categorie'));
        $sort = trim((string) $this->request->getGet('sort'));
        $order = trim((string) $this->request->getGet('order'));

        $rawCategories = $this->livreModel
            ->select('categorie')
            ->distinct()
            ->where('categorie IS NOT NULL', null, false)
            ->where('categorie !=', '')
            ->orderBy('categorie', 'ASC')
            ->findAll();

        $categories = array_map(static fn (array $item): string => (string) $item['categorie'], $rawCategories);

        $aRecherche = ($motCle !== '') || ($categorie !== '');

        if ($aRecherche) {
            $livres = $this->livreModel->rechercher($motCle, $categorie, $sort, $order);
            $pager = null;
        } else {
            $livres = $this->livreModel->getLivresPagines(10, $sort, $order);
            $pager = $this->livreModel->pager;
        }

        return view('library/index', [
            'livres' => $livres,
            'pager' => $pager,
            'motCle' => $motCle,
            'categorieSelectionnee' => $categorie,
            'categories' => $categories,
            'sort' => $sort,
            'order' => $order,
        ]);
    }

    public function new()
    {
        $rawCategories = $this->livreModel
            ->select('categorie')
            ->distinct()
            ->where('categorie IS NOT NULL', null, false)
            ->where('categorie !=', '')
            ->orderBy('categorie', 'ASC')
            ->findAll();

        $categories = array_map(static fn (array $item): string => (string) $item['categorie'], $rawCategories);
        $auteurs = $this->auteurModel->orderBy('nom', 'ASC')->findAll();

        return view('library/new', [
            'categories' => $categories,
            'auteurs' => $auteurs,
        ]);
    }

    public function store()
    {
        $anneePublication = (int) $this->request->getPost('annee_publication');
        $categorie = trim((string) $this->request->getPost('categorie'));

        if (! $this->livreModel->anneePublicationValide($anneePublication)) {
            return redirect()->back()->withInput()->with('errors', [
                'annee_publication' => "L'annee de publication ne peut pas etre dans le futur.",
            ]);
        }

        if ($categorie === '') {
            return redirect()->back()->withInput()->with('errors', [
                'categorie' => 'La categorie est obligatoire.',
            ]);
        }

        $fichierCouverture = $this->request->getFile('couverture');
        $nomFichierCouverture = null;

        if ($fichierCouverture !== null && $fichierCouverture->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $fichierCouverture->isValid()) {
                return redirect()->back()->withInput()->with('errors', [
                    'couverture' => 'Le fichier de couverture est invalide.',
                ]);
            }

            $mimeAutorises = ['image/jpeg', 'image/png', 'image/webp'];
            if (! in_array($fichierCouverture->getMimeType(), $mimeAutorises, true)) {
                return redirect()->back()->withInput()->with('errors', [
                    'couverture' => 'La couverture doit etre une image JPEG, PNG ou WEBP.',
                ]);
            }

            if ($fichierCouverture->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('errors', [
                    'couverture' => 'La taille de la couverture ne doit pas depasser 2 Mo.',
                ]);
            }

            $cheminUpload = FCPATH . 'uploads';
            if (! is_dir($cheminUpload)) {
                mkdir($cheminUpload, 0775, true);
            }

            $nomFichierCouverture = $fichierCouverture->getRandomName();
            $fichierCouverture->move($cheminUpload, $nomFichierCouverture);
        }

        $auteursSelectionnes = $this->request->getPost('auteurs');
        $auteursSelectionnes = is_array($auteursSelectionnes) ? $auteursSelectionnes : [];

        $nouveauxAuteurs = trim((string) $this->request->getPost('nouveaux_auteurs'));
        $nouvellesLignes = $nouveauxAuteurs !== '' ? preg_split('/\r\n|\r|\n/', $nouveauxAuteurs) : [];

        $idsAuteurs = [];
        foreach ($auteursSelectionnes as $auteurId) {
            if (is_numeric($auteurId)) {
                $idsAuteurs[] = (int) $auteurId;
            }
        }

        foreach ($nouvellesLignes as $ligne) {
            $ligne = trim((string) $ligne);
            if ($ligne === '') {
                continue;
            }

            $morceaux = preg_split('/\s+/', $ligne, 2);
            $prenom = $morceaux[0] ?? '';
            $nom = $morceaux[1] ?? '';

            if ($nom === '') {
                $nom = $prenom;
                $prenom = null;
            }

            $idsAuteurs[] = $this->auteurModel->findOrCreate($nom, $prenom ?: null, null);
        }

        $idsAuteurs = array_values(array_unique($idsAuteurs));

        $nomAuteurPrincipal = trim((string) $this->request->getPost('auteur'));
        if ($nomAuteurPrincipal !== '' && empty($idsAuteurs)) {
            $idsAuteurs[] = $this->auteurModel->findOrCreate($nomAuteurPrincipal, null, null);
        }

        $auteurAffiche = $nomAuteurPrincipal;
        if (! empty($idsAuteurs)) {
            $listeAuteurs = $this->auteurModel->whereIn('id', $idsAuteurs)->findAll();
            $noms = array_map(static function (array $auteur): string {
                $prenom = trim((string) ($auteur['prenom'] ?? ''));
                $nom = trim((string) ($auteur['nom'] ?? ''));
                return trim($prenom . ' ' . $nom);
            }, $listeAuteurs);
            $auteurAffiche = implode(', ', array_filter($noms));
        }

        $ok = $this->livreModel->insert([
            'isbn' => trim((string) $this->request->getPost('isbn')),
            'titre' => trim((string) $this->request->getPost('titre')),
            'auteur' => $auteurAffiche,
            'annee_publication' => $anneePublication,
            'categorie' => $categorie,
            'resume' => trim((string) $this->request->getPost('resume')),
            'couverture' => $nomFichierCouverture,
            'statut' => 'disponible',
        ]);

        if (! $ok) {
            return redirect()->back()->withInput()->with('errors', $this->livreModel->errors());
        }

        $livreId = (int) $this->livreModel->getInsertID();

        if ($livreId > 0 && ! empty($idsAuteurs)) {
            $builder = \Config\Database::connect()->table('livre_auteur');
            foreach ($idsAuteurs as $auteurId) {
                $builder->insert([
                    'livre_id' => $livreId,
                    'auteur_id' => $auteurId,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to('/livres')->with('success', 'Livre ajoute avec succes.');
    }

    public function show(int $id)
    {
        $livre = $this->livreModel->find($id);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound(self::MESSAGE_LIVRE_INTROUVABLE);
        }

        $dernierEmprunt = $this->empruntModel->getDernierEmpruntPourLivre($id);

        $auteurs = $this->livreModel->getAuteursPourLivre($id);
        $notes = $this->livreModel->getNoeurstrait($id);
        $commentaires = $this->livreModel->getCommentairesRecents($id, 10);

        return view('library/show', [
            'livre' => $livre,
            'dernierEmprunt' => $dernierEmprunt,
            'validation' => service('validation'),
            'auteurs' => $auteurs,
            'notes' => $notes,
            'commentaires' => $commentaires,
        ]);
    }

    public function loan(int $id)
    {
        $livre = $this->livreModel->find($id);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound(self::MESSAGE_LIVRE_INTROUVABLE);
        }

        if (($livre['statut'] ?? 'disponible') !== 'disponible') {
            return redirect()->to(self::ROUTE_LIVRES . $id)->with('error', 'Ce livre est deja prete.');
        }

        $rules = [
            'nom_emprunteur' => 'required|min_length[2]|max_length[255]',
        ];

        $messages = [
            'nom_emprunteur' => [
                'required' => "Le nom de l'emprunteur est requis.",
                'min_length' => "Le nom de l'emprunteur doit contenir au moins 2 caracteres.",
            ],
        ];

        if (! $this->validateData($this->request->getPost(), $rules, $messages)) {
            return redirect()->to(self::ROUTE_LIVRES . $id)->withInput()->with('validation', $this->validator);
        }

        $nomEmprunteur = trim((string) $this->request->getPost('nom_emprunteur'));
        $aujourdhui = date('Y-m-d');

        $this->livreModel->update($id, [
            'statut' => 'prete',
        ]);

        $this->empruntModel->insert([
            'livre_id' => $id,
            'nom_emprunteur' => $nomEmprunteur,
            'date_emprunt' => $aujourdhui,
            'date_retour' => null,
        ]);

        return redirect()->to(self::ROUTE_LIVRES . $id)->with('success', 'Livre prete avec succes.');
    }

    public function returnBook(int $id)
    {
        $livre = $this->livreModel->find($id);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound(self::MESSAGE_LIVRE_INTROUVABLE);
        }

        if (($livre['statut'] ?? 'disponible') === 'disponible') {
            return redirect()->to(self::ROUTE_LIVRES . $id)->with('error', 'Ce livre est deja disponible.');
        }

        $dernierEmpruntOuvert = $this->empruntModel
            ->where('livre_id', $id)
            ->where('date_retour', null)
            ->orderBy('date_emprunt', 'DESC')
            ->first();

        $aujourdhui = date('Y-m-d');

        $this->livreModel->update($id, [
            'statut' => 'disponible',
        ]);

        if ($dernierEmpruntOuvert !== null) {
            $this->empruntModel->update($dernierEmpruntOuvert['id'], [
                'date_retour' => $aujourdhui,
            ]);
        }

        return redirect()->to(self::ROUTE_LIVRES . $id)->with('success', 'Livre retourne avec succes.');
    }

    public function delete(int $id)
    {
        $livre = $this->livreModel->find($id);

        if ($livre === null) {
            throw PageNotFoundException::forPageNotFound(self::MESSAGE_LIVRE_INTROUVABLE);
        }

        if (! empty($livre['couverture'])) {
            $cheminCouverture = FCPATH . 'uploads/' . $livre['couverture'];
            if (is_file($cheminCouverture)) {
                unlink($cheminCouverture);
            }
        }

        $this->empruntModel->where('livre_id', $id)->delete();
        $this->livreModel->delete($id);

        return redirect()->to('/livres')->with('success', 'Livre supprime avec succes.');
    }
}
