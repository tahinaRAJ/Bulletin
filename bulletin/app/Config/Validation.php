<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    /**
     * @var array<string, array<string, string>>
     */
    public array $livreRules = [
        'titre' => 'required|min_length[3]|max_length[150]',
        'isbn' => 'required|max_length[20]|is_unique[livre.isbn]',
        'date_publication' => 'required|valid_date[Y-m-d]',
        'id_auteur' => 'required|is_natural_no_zero',
        'id_categorie' => 'required|is_natural_no_zero',
        'resume' => 'permit_empty|max_length[5000]',
        'couverture' => 'if_exist|is_image[couverture]|max_size[couverture,2048]|mime_in[couverture,image/jpg,image/jpeg,image/png,image/webp]',
    ];

    /**
     * @var array<string, array<string, string>>
     */
    public array $empruntRules = [
        'id_livre' => 'required|is_natural_no_zero',
        'nom_emprunteur' => 'required|min_length[2]|max_length[100]',
    ];

    /**
     * @var array<string, array<string, string>>
     */
    public array $retourRules = [
        'id_livre' => 'required|is_natural_no_zero',
    ];

    /**
     * @var array<string, array<string, string>>
     */
    public array $livreErrors = [
        'titre' => [
            'required' => 'Le titre est requis.',
            'min_length' => 'Le titre doit contenir au moins 3 caracteres.',
        ],
        'isbn' => [
            'required' => 'Le champ ISBN est requis.',
            'is_unique' => 'Cet ISBN existe deja.',
        ],
        'date_publication' => [
            'required' => 'La date de publication est requise.',
            'valid_date' => 'Le format de date est invalide.',
        ],
        'couverture' => [
            'is_image' => 'La couverture doit etre une image.',
            'max_size' => 'La couverture ne doit pas depasser 2 Mo.',
            'mime_in' => 'Le format de couverture doit etre JPG, PNG ou WEBP.',
        ],
    ];

    /**
     * @var array<string, array<string, string>>
     */
    public array $empruntErrors = [
        'nom_emprunteur' => [
            'required' => 'Le nom de l\'emprunteur est requis.',
            'min_length' => 'Le nom de l\'emprunteur est trop court.',
        ],
    ];
}
