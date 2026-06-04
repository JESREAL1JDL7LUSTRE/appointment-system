<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::doLogin');
$routes->post('/register', 'Home::doRegister');
$routes->get('/logout', 'Home::logout');
$routes->post('/logout', 'Home::logout');


// UI Preview Routes
$routes->group('ui', function ($routes) {
    $routes->get('admin', 'UiPreview::adminDashboard');
    $routes->get('admin/staff', 'UiPreview::adminStaff');
    $routes->get('admin/services', 'UiPreview::adminServices');
    $routes->get('admin/appointments', 'UiPreview::adminAppointments');
    
    $routes->get('staff', 'UiPreview::staffDashboard');
    $routes->get('staff/schedule', 'UiPreview::staffSchedule');
    $routes->get('staff/appointments', 'UiPreview::staffAppointments');
});
$routes->post('chatbot/ask', 'ChatbotController::ask');

// Client Portal Operations
$routes->group('client', function ($routes) {
    $routes->post('switch', 'Home::switchClient');
    $routes->post('register', 'Home::registerClient');
    $routes->get('staff-by-service/(:num)', 'Home::getStaffForService/$1');
    $routes->post('slots', 'Home::getAvailableSlots');
    $routes->post('book', 'Home::bookAppointment');
    $routes->post('cancel/(:num)', 'Home::cancelAppointment/$1');
    $routes->post('profile/update', 'Home::updateProfile');
});

