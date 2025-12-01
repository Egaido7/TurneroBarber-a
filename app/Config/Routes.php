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

// --- ¡NUEVAS RUTAS PARA REPROGRAMAR! ---

// 1. Muestra la página/formulario para reprogramar
$routes->get('admin/turnos/reprogramar/(:num)', 'Turnos::reprogramar/$1');

// 2. Carga los horarios disponibles (AJAX/Form) para la nueva fecha elegida
$routes->post('admin/turnos/horarios/(:num)', 'Turnos::horariosReprogramar/$1');

// 3. Procesa y guarda la reprogramación
$routes->post('admin/turnos/reprogramar/(:num)', 'Turnos::procesarReprogramacion/$1');


// --- ¡NUEVAS RUTAS PÚBLICAS PARA REPROGRAMACIÓN (USUARIO)! ---
// (:hash) es un placeholder para el token de 64 caracteres
$routes->get('turnos/cambiar/(:hash)', 'Turnos::reprogramarUsuario/$1');
$routes->post('turnos/cambiar/horarios/(:hash)', 'Turnos::horariosUsuario/$1');
$routes->post('turnos/cambiar/guardar/(:hash)', 'Turnos::procesarReprogramacionUsuario/$1');


// --- Login y Admin ---
$routes->get('login', 'Login::index');
$routes->post('auth/login', 'Login::login');
$routes->get('logout', 'Login::logout');
$routes->get('login/cambiarPassword', 'Login::cambiarPassword');
$routes->post('login/procesar-olvido', 'Login::procesarOlvidoPassword');
//Proceso de reserva
$routes->get('proceso-reserva', 'Turnos::resultado');

//Admin
$routes->post('admin/cambiarPassword', 'Login::cambiarPassword'); 
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

// --- CRUD Servicios (Precios) ---
$routes->post('admin/servicios/agregar', 'Admin::agregarServicio'); // Esta ruta ya la tenías
$routes->post('admin/servicios/editar/(:num)', 'Admin::editarServicio/$1');
$routes->get('admin/servicios/eliminar/(:num)', 'Admin::eliminarServicio/$1');

// --- ¡NUEVA RUTA PARA ACTUALIZAR PRECIO! ---
$routes->post('admin/precios/actualizar/(:num)', 'Admin::actualizarPrecioServicio/$1');

// --- Integración Mercado Pago ---
$routes->get('turnos/feedback', 'Turnos::feedbackPago');

// --- CRUD Días Bloqueados (Admin) ---
$routes->post('admin/dias/bloquear', 'Admin::bloquearDia');
$routes->get('admin/dias/desbloquear/(:num)', 'Admin::desbloquearDia/$1');