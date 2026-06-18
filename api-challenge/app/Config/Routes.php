<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('estudiante', function($routes) {
    $routes->get('/', 'Estudiante::index');
    $routes->post('create', 'Estudiante::create');
});