<?php

namespace App\Controllers;

use App\Models\AuteurModel;
use App\Models\CategorieModel;
use App\Models\LivreModel;
use App\Models\MouvementLivreModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class LivresController extends BaseController
{
    private LivreModel $livreModel;
    private CategorieModel $categorieModel;
    private AuteurModel $auteurModel;
    private MouvementLivreModel $mouvementModel;

    public function __construct()
    {
        $this->livreModel = new LivreModel();
        $this->categorieModel = new CategorieModel();
        $this->auteurModel = new AuteurModel();
        $this->mouvementModel = new MouvementLivreModel();
    }

    public function index(): string
    {
        $recherche = trim((string) $this->request->getGet('q'));
        $categorieId = $this->request->getGet('categorie_id');
        $categorieId = ctype_digit((string) $categorieId) ? (int) $categorieId : null;

        $livres = $this->livreModel->getPaginatedWithRelations($recherche, $categorieId, 10);

        return view('livres/index', [
            'livres' => $livres,
            'categories' => $this->categorieModel->orderBy('nom', 'ASC')->findAll(),
            'recherche' => $recherche,
            'categorieId' => $categorieId,
            'pager' => $this->livreModel->pager,
        ]);
    }

    public function create(): string
    {
        return view('livres/create', [
            'auteurs' => $this->auteurModel->orderBy('nom', 'ASC')->findAll(),
            'categories' => $this->categorieModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function store(): ResponseInterface|RedirectResponse
    {
        $data = [
            'titre' => trim((string) $this->request->getPost('titre')),
            'isbn' => trim((string) $this->request->getPost('isbn')),
            'date_publication' => (string) $this->request->getPost('date_publication'),
            'id_auteur' => (int) $this->request->getPost('id_auteur'),
            'id_categorie' => (int) $this->request->getPost('id_categorie'),
            'resume' => trim((string) $this->request->getPost('resume')),
        ];

        if (! $this->validateData($data, config('Validation')->livreRules, config('Validation')->livreErrors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (strtotime($data['date_publication']) > strtotime(date('Y-m-d'))) {
            return redirect()->back()->withInput()->with('errors', [
                'date_publication' => 'La date de publication ne peut pas etre dans le futur.',
            ]);
        }

        $cover = $this->request->getFile('couverture');
        if ($cover !== null && $cover->isValid() && ! $cover->hasMoved()) {
            $coverName = $cover->getRandomName();
            $cover->move(WRITEPATH . 'uploads/couvertures', $coverName);
            $data['couverture'] = $coverName;
        }

        $this->livreModel->insert($data);

        return redirect()->to('/livres')->with('success', 'Livre ajoute avec succes.');
    }

    public function show(int $id): string
    {
        $livre = $this->livreModel->getWithRelations($id);

        if ($livre === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('livres/show', [
            'livre' => $livre,
            'etat' => $this->mouvementModel->getEtatCourantLivre($id),
            'historique' => $this->mouvementModel->getHistoriqueLivre($id),
            'dernierEmprunteur' => $this->mouvementModel->getDernierEmprunteur($id),
        ]);
    }
}
