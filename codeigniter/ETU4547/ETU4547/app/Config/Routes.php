<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes publiques — aucun filtre
$routes->get('/', 'LibraryController::index');
$routes->get('/auth/login', 'AuthController::loginForm');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/auth/register', 'AuthController::registerForm');
$routes->post('/auth/register', 'AuthController::register');
$routes->get('/auth/logout', 'AuthController::logout');
$routes->get('/livres', 'LibraryController::index');

// Routes protégées — utilisateur connecté uniquement
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/livres/new', 'LibraryController::new');
    $routes->post('/livres', 'LibraryController::store');
    $routes->get('/livres/(:num)', 'LibraryController::show/$1');
    $routes->get('/auteurs/(:num)', 'AuteurController::listeLivresParAuteur/$1');
    $routes->post('/livres/(:num)/loan', 'MouvementController::loan/$1');
    $routes->post('/livres/(:num)/return', 'MouvementController::returnBook/$1');
    $routes->post('/livres/(:num)/note', 'NotationController::noter/$1');
    $routes->post('/livres/(:num)/commentaires', 'CommentaireController::ajouter/$1');
    $routes->post('/commentaires/(:num)/delete', 'CommentaireController::supprimer/$1');
    $routes->get('/export/csv', 'ExportController::exporterEnCSV');
    $routes->get('/export/pdf', 'ExportController::exporterEnPDF');
    $routes->get('/profile', 'UserController::profile');
    
    // Réservations
    $routes->post('/livres/(:num)/reserver', 'ReservationController::creerReservation/$1');
    $routes->get('/mes-reservations', 'ReservationController::mesReservations');
    $routes->post('/reservations/(:num)/annuler', 'ReservationController::annulerReservation/$1');
    
    // Historique d'emprunts
    $routes->get('/mes-emprunts', 'EmpruntController::historiquePourUtilisateur');
    $routes->get('/emprunts/(:num)', 'EmpruntController::detailsEmprunt/$1');
});

// Routes réservées à l'admin ET bibliothécaire
$routes->group('', ['filter' => 'role:admin,bibliothecaire'], function($routes) {
    $routes->post('/livres/(:num)/delete', 'LibraryController::delete/$1');
});

// Routes réservées à l'admin
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    $routes->get('dashboard', 'Admin\AdminDashboardController::dashboard');
    $routes->get('emprunts-retard', 'Admin\AdminController::listeEmpruntEnRetard');
    $routes->get('relances', 'Admin\AdminController::envoiRelances');
    $routes->get('reservations', 'Admin\AdminController::reservationEnAttente');
});
