<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('students', 'Estudiante::index');
$routes->post('students', 'Estudiante::create');
$routes->get('students/list', 'Estudiante::list');
$routes->put('students/(:num)', 'Estudiante::update/$1');
$routes->delete('students/(:num)', 'Estudiante::delete/$1');
$routes->get('students/subjects-status', 'Estudiante::subjectStatusesPage');
$routes->get('students/(:num)/subjects-status', 'Estudiante::subjectStatuses/$1');

$routes->get('subjects', 'Materia::index');
$routes->post('subjects', 'Materia::create');
$routes->get('subjects/list', 'Materia::list');
$routes->put('subjects/(:num)', 'Materia::update/$1');
$routes->delete('subjects/(:num)', 'Materia::delete/$1');

$routes->get('periods', 'Periodo::index');
$routes->post('periods', 'Periodo::create');
$routes->get('periods/list', 'Periodo::list');
$routes->put('periods/(:num)', 'Periodo::update/$1');
$routes->delete('periods/(:num)', 'Periodo::delete/$1');

$routes->get('curricular-space-statuses', 'EstadoEspacioCurricular::index');
$routes->post('curricular-space-statuses', 'EstadoEspacioCurricular::create');
$routes->get('curricular-space-statuses/list', 'EstadoEspacioCurricular::list');
$routes->put('curricular-space-statuses/(:num)', 'EstadoEspacioCurricular::update/$1');
$routes->delete('curricular-space-statuses/(:num)', 'EstadoEspacioCurricular::delete/$1');

$routes->get('curricular-spaces', 'EspacioCurricular::index');
$routes->post('curricular-spaces', 'EspacioCurricular::createFromChallenge');
$routes->post('curricular-spaces/create', 'EspacioCurricular::create');
$routes->get('curricular-spaces/list', 'EspacioCurricular::list');
$routes->get('curricular-spaces/periods', 'EspacioCurricular::periodos');
$routes->put('curricular-spaces/(:num)', 'EspacioCurricular::update/$1');
$routes->delete('curricular-spaces/(:num)', 'EspacioCurricular::delete/$1');

$routes->post(
    'subjects/(:num)/curricular-spaces/(:num)',
    'MateriaEspacioCurricular::createFromChallenge/$1/$2'
);

$routes->group('subject-curricular-spaces', function($routes) {
    $routes->get('/', 'MateriaEspacioCurricular::index');
    $routes->post('create', 'MateriaEspacioCurricular::create');
    $routes->put('update/(:num)/(:num)', 'MateriaEspacioCurricular::update/$1/$2');
    $routes->delete('delete/(:num)/(:num)', 'MateriaEspacioCurricular::delete/$1/$2');
    $routes->get('list', 'MateriaEspacioCurricular::list');
    $routes->get('options', 'MateriaEspacioCurricular::options');
});

$routes->post(
    'students/(:num)/curricular-spaces/(:num)/status',
    'EstudianteEspacioCurricular::setStatus/$1/$2'
);

$routes->group('student-curricular-spaces', function($routes) {
    $routes->get('/', 'EstudianteEspacioCurricular::index');
    $routes->post('create', 'EstudianteEspacioCurricular::create');
    $routes->put('update/(:num)/(:num)', 'EstudianteEspacioCurricular::update/$1/$2');
    $routes->delete('delete/(:num)/(:num)', 'EstudianteEspacioCurricular::delete/$1/$2');
    $routes->get('list', 'EstudianteEspacioCurricular::list');
    $routes->get('options', 'EstudianteEspacioCurricular::options');
});
