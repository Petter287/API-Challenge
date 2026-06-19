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

$routes->group('periodo', function($routes){
    $routes->get('/', 'Periodo::index');
    $routes->post('create', 'Periodo::create');
    $routes->put('update/(:num)', 'Periodo::update/$1');
    $routes->delete('delete/(:num)', 'Periodo::delete/$1');
    $routes->get('list', 'Periodo::list');
});

$routes->group('estado-espacio-curricular', function($routes) {
    $routes->get('/', 'EstadoEspacioCurricular::index');
    $routes->post('create', 'EstadoEspacioCurricular::create');
    $routes->put('update/(:num)', 'EstadoEspacioCurricular::update/$1');
    $routes->delete('delete/(:num)', 'EstadoEspacioCurricular::delete/$1');
    $routes->get('list', 'EstadoEspacioCurricular::list');
});

$routes->group('materia', function($routes) {
    $routes->get('/', 'Materia::index');
    $routes->post('create', 'Materia::create');
    $routes->put('update/(:num)', 'Materia::update/$1');
    $routes->delete('delete/(:num)', 'Materia::delete/$1');
    $routes->get('list', 'Materia::list');
});
