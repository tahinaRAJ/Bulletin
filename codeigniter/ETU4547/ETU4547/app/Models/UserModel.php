<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nom', 'email', 'password', 'role'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nom'      => 'required|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]',
        'role'     => 'required|in_list[utilisateur,bibliothecaire,admin]',
    ];

    protected $validationMessages = [
        'nom'      => [
            'required'   => 'Le nom est obligatoire',
            'min_length' => 'Le nom doit contenir au moins 3 caractères',
            'max_length' => 'Le nom ne peut pas dépasser 255 caractères',
        ],
        'email'    => [
            'required'    => 'L\'email est obligatoire',
            'valid_email' => 'L\'email doit être valide',
            'is_unique'   => 'Cet email est déjà utilisé',
        ],
        'password' => [
            'required'   => 'Le mot de passe est obligatoire',
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères',
        ],
        'role'     => [
            'required' => 'Le rôle est obligatoire',
            'in_list'  => 'Le rôle doit être: utilisateur, bibliothecaire ou admin',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Vérifier les credentials
     */
    public function verifyCredentials(string $email, string $password) : ?array
    {
        $user = $this->where('email', $email)->first();

        if ($user && $user['password'] === $password) {
            return $user;
        }

        return null;
    }

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail(string $email) : ?array
    {
        return $this->where('email', $email)->first();
    }
}
