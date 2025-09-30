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
