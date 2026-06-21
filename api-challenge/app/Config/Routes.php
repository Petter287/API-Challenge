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

$routes->group('espacio-curricular', function($routes) {
    $routes->get('/', 'EspacioCurricular::index');
    $routes->post('create', 'EspacioCurricular::create');
    $routes->put('update/(:num)', 'EspacioCurricular::update/$1');
    $routes->delete('delete/(:num)', 'EspacioCurricular::delete/$1');
    $routes->get('list', 'EspacioCurricular::list');
    $routes->get('periodos', 'EspacioCurricular::periodos');
});

$routes->group('materia-espacio-curricular', function($routes) {
    $routes->get('/', 'MateriaEspacioCurricular::index');
    $routes->post('create', 'MateriaEspacioCurricular::create');
    $routes->put('update/(:num)/(:num)', 'MateriaEspacioCurricular::update/$1/$2');
    $routes->delete('delete/(:num)/(:num)', 'MateriaEspacioCurricular::delete/$1/$2');
    $routes->get('list', 'MateriaEspacioCurricular::list');
    $routes->get('options', 'MateriaEspacioCurricular::options');
});

$routes->group('estudiante-espacio-curricular', function($routes) {
    $routes->get('/', 'EstudianteEspacioCurricular::index');
    $routes->post('create', 'EstudianteEspacioCurricular::create');
    $routes->put('update/(:num)/(:num)', 'EstudianteEspacioCurricular::update/$1/$2');
    $routes->delete('delete/(:num)/(:num)', 'EstudianteEspacioCurricular::delete/$1/$2');
    $routes->get('list', 'EstudianteEspacioCurricular::list');
    $routes->get('options', 'EstudianteEspacioCurricular::options');
});
