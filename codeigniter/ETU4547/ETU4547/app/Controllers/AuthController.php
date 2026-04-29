<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function loginForm()
    {
        return view('auth/login');
    }

    /**
     * Traiter la connexion
     */
    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyCredentials($email, $password);

        if (!$user) {
            return view('auth/login', [
                'erreur' => 'Email ou mot de passe incorrect'
            ]);
        }

        // Stocker uniquement les données non sensibles en session
        session()->set('user', [
            'id'    => $user['id'],
            'nom'   => $user['nom'],
            'email' => $user['email'],
            'role'  => $user['role'], // 'admin' | 'bibliothecaire' | 'utilisateur'
        ]);

        return redirect()->to('/livres')->with('success', 'Connexion réussie');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function registerForm()
    {
        return view('auth/register');
    }

    /**
     * Traiter l'inscription
     */
    public function register()
    {
        $data = [
            'nom'      => $this->request->getPost('nom'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => 'utilisateur', // Role par défaut
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/auth/login')->with('success', 'Inscription réussie. Veuillez vous connecter.');
        }

        return view('auth/register', [
            'erreurs' => $this->userModel->errors()
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Déconnexion réussie');
    }
}
