<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Página inicial
$routes->get('/', 'Home::index');

// Consultar horarios disponibles
$routes->post('home/horarios', 'Home::horarios');

// Guardar turno
$routes->post('turnos/procesar', 'Turnos::procesar');


// --- Login y Admin ---
$routes->get('login', 'Login::index');
$routes->post('auth/login', 'Login::login');
$routes->get('logout', 'Login::logout');
$routes->get('admin/cambiarPassword', 'Login::cambiarPassword');
//Proceso de reserva
$routes->get('proceso-reserva', 'Turnos::resultado');

//Admin
$routes->get('/admin', 'Admin::dashboard');
$routes->post('/admin/peluqueros/agregar', 'Admin::agregarPeluquero');
$routes->post('/admin/servicios/agregar', 'Admin::agregarServicio');
// Cancelar turno
$routes->get('admin/turnos/cancelar/(:num)', 'Admin::cancelarTurno/$1');

// --- CRUD Peluqueros / Barberos (NUEVO) ---
$routes->post('admin/peluqueros/agregar', 'Admin::agregarPeluquero');
$routes->post('admin/peluqueros/editar/(:num)', 'Admin::editarPeluquero/$1');
$routes->get('admin/peluqueros/eliminar/(:num)', 'Admin::eliminarPeluquero/$1');

// --- CRUD Servicios (Placeholder) ---
$routes->post('/admin/servicios/agregar', 'Admin::agregarServicio');