<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('livres', 'LivresController::index');
$routes->get('livres/nouveau', 'LivresController::create');
$routes->post('livres', 'LivresController::store', ['filter' => 'csrf']);
$routes->get('livres/(:num)', 'LivresController::show/$1');

$routes->post('mouvements/(:num)/emprunter', 'MouvementsController::emprunter/$1', ['filter' => 'csrf']);
$routes->post('mouvements/(:num)/retour', 'MouvementsController::retour/$1', ['filter' => 'csrf']);
