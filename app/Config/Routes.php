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

    // Authenticated User Endpoints (Any logged-in user)
    $routes->group('user', ['filter' => 'role'], static function ($routes) {
        $routes->post('avatar', 'UserController::uploadAvatar');
    });

    // Client Endpoints (Must be logged in as Client)
    $routes->group('client', ['filter' => 'role:Client'], static function ($routes) {
        $routes->post('appointments', 'AppointmentController::create');
        // Future routes: $routes->get('appointments', 'AppointmentController::index');
    });

    // Staff Endpoints (Must be logged in as Staff)
    $routes->group('staff', ['filter' => 'role:Staff'], static function ($routes) {
        $routes->post('working-hours', 'StaffController::setWorkingHours');
        $routes->post('time-off', 'StaffController::requestTimeOff');
        $routes->get('client-history/(:num)', 'ClientHistoryController::show/$1');
    });

    // Admin Endpoints (Must be logged in as Administrator)
    $routes->group('admin', ['filter' => 'role:Administrator'], static function ($routes) {
        $routes->get('reports/summary', 'AdminController::quickSummary');
        $routes->get('reports/dynamic', 'AdminController::dynamicReport');
        // Admin should also be able to access client history
        $routes->get('client-history/(:num)', 'ClientHistoryController::show/$1');
    });

});

// --------------------------------------------------------------------
// UI Preview Routes
// --------------------------------------------------------------------
$routes->get('/dashboard', 'Client::dashboard', ['filter' => 'role:Client']);
$routes->get('/client/appointments', 'Client::appointments', ['filter' => 'role:Client']);

$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::doLogin');
$routes->post('/register', 'Home::doRegister');
$routes->get('/logout', 'Home::logout');
$routes->post('/logout', 'Home::logout');

$routes->group('ui', function ($routes) {
    $routes->group('admin', ['filter' => 'role:Administrator'], function ($routes) {
        $routes->get('', 'Admin::dashboard');
        $routes->get('staff', 'Admin::staff');
        $routes->post('staff/create', 'Admin::createStaff');
        $routes->post('staff/update/(:num)', 'Admin::updateStaff/$1');
        $routes->post('staff/delete/(:num)', 'Admin::deleteStaff/$1');
        $routes->get('services', 'Admin::services');
        $routes->post('services/create', 'Admin::createService');
        $routes->post('services/update/(:num)', 'Admin::updateService/$1');
        $routes->post('services/delete/(:num)', 'Admin::deleteService/$1');
        $routes->get('appointments', 'Admin::appointments');
    });
    
    $routes->group('staff', ['filter' => 'role:Staff,Administrator'], function ($routes) {
        $routes->get('', 'Staff::dashboard');
        $routes->get('schedule', 'Staff::schedule');
        $routes->get('appointments', 'Staff::appointments');
        $routes->post('appointments/update-status/(:num)', 'Staff::updateAppointmentStatus/$1');
    });
});
$routes->post('chatbot/ask', 'ChatbotController::ask');

// Client Portal Operations
$routes->group('client', ['filter' => 'role:Client'], function ($routes) {
    $routes->post('switch', 'Home::switchClient');
    $routes->post('register', 'Home::registerClient');
    $routes->get('staff-by-service/(:num)', 'Home::getStaffForService/$1');
    $routes->post('slots', 'Home::getAvailableSlots');
    $routes->post('book', 'Home::bookAppointment');
    $routes->post('cancel/(:num)', 'Home::cancelAppointment/$1');
});
$routes->post('quick-login', 'Home::quickLoginUser');
$routes->post('update-profile', 'Home::updateProfile');
