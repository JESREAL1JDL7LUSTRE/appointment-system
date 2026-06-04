<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

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
