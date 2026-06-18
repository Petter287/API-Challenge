<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('estudiante', function($routes) {
    $routes->get('/', 'Estudiante::index');
    $routes->post('create', 'Estudiante::create');
    $routes->put('update/(:num)', 'Estudiante::update/$1');
    $routes->delete('delete/(:num)', 'Estudiante::delete/$1');
    $routes->get('list', 'Estudiante::list');
});