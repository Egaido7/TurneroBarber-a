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

$routes->get('login', 'Login::index');
$routes->post('auth/login', 'Login::login');
$routes->get('logout', 'Login::logout');
$routes->get('admin/principal', 'Login::admin');
$routes->get('admin/cambiarPassword', 'Login::cambiarPassword');