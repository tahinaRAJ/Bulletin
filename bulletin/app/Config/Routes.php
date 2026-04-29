<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * Routes pour la Gestion des Notes
 */

// Redirection par défaut
$routes->get('/', function() {
    return redirect()->to(session('user_id') ? '/list' : '/login');
});

// ============================================================================
// AUTH - Connexion / Déconnexion
// ============================================================================
$routes->get('login', 'AuthController::index');
$routes->post('login', 'AuthController::verifier', ['filter' => 'csrf']);
$routes->get('login/logout', 'AuthController::logout');

// ============================================================================
// ETUDIANT - Liste des étudiants
// ============================================================================
$routes->get('list', 'EtuController::index');

// ============================================================================
// NOTE - Insertion et consultation
// ============================================================================
$routes->get('insert', 'NoteController::index');
$routes->post('insert', 'NoteController::insererNote', ['filter' => 'csrf']);

// Fiche d'un étudiant
$routes->get('etud/(:segment)', 'NoteController::ficheEtu/$1');

// Supprimer une note
$routes->get('note/supprimer/(:segment)/(:segment)', 'NoteController::supprimerNote/$1/$2');
