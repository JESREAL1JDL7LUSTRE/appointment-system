<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Home::index');

// --------------------------------------------------------------------
// API Routes (Strict Backend)
// --------------------------------------------------------------------
$routes->group('api', ['namespace' => 'App\Controllers\API'], static function ($routes) {

    // Authentication
    $routes->post('login', 'AuthController::login');
    $routes->post('logout', 'AuthController::logout');

    // Public / Shared availability
    $routes->get('schedule/slots', 'ScheduleController::slots');

    // Client Endpoints (Must be logged in as Client)
    $routes->group('client', ['filter' => 'role:Client'], static function ($routes) {
        $routes->post('appointments', 'AppointmentController::create');
        // Future routes: $routes->get('appointments', 'AppointmentController::index');
    });

    // Staff Endpoints (Must be logged in as Staff)
    $routes->group('staff', ['filter' => 'role:Staff'], static function ($routes) {
        // Future routes: $routes->get('schedule', 'ScheduleController::mySchedule');
    });

    // Admin Endpoints (Must be logged in as Administrator)
    $routes->group('admin', ['filter' => 'role:Administrator'], static function ($routes) {
        // Future routes: $routes->post('services', 'ServiceController::create');
    });

});
