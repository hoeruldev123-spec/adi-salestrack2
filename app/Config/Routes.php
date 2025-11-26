<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes - TANPA FILTER
$routes->get('login', 'Auth\AuthController::index');
$routes->post('login', 'Auth\AuthController::attemptLogin');
$routes->get('logout', 'Auth\AuthController::logout');

// Dashboard Routes berdasarkan Role
$routes->group('sales', ['filter' => 'role:sales'], function ($routes) {
    $routes->get('dashboard', 'Dashboard\SalesDashboardController::index');
    $routes->get('opportunities', 'Opportunity\OpportunityController::index');
    $routes->get('opportunities/create', 'Opportunity\OpportunityController::create');
    $routes->post('opportunities/store', 'Opportunity\OpportunityController::store');
});

$routes->group('manager', ['filter' => 'role:manager'], function ($routes) {
    $routes->get('dashboard', 'Dashboard\ManagerDashboardController::index');
    $routes->get('opportunities', 'Opportunity\OpportunityController::index');
    $routes->get('reports/sales', 'Report\SalesReportController::index');
});

$routes->group('bod', ['filter' => 'role:bod'], function ($routes) {
    $routes->get('dashboard', 'Dashboard\BODDashboardController::index');
    $routes->get('reports/revenue', 'Report\RevenueReportController::index');
    $routes->get('reports/ranking', 'Report\RankingController::index');
});

// Master Data Routes (untuk manager dan bod)
$routes->group('master', ['filter' => 'role:manager,bod'], function ($routes) {
    $routes->get('accounts', 'Master\AccountController::index');
    $routes->get('principals', 'Master\PrincipalController::index');
    $routes->get('solution-architects', 'Master\SolutionArchitectController::index');
    $routes->get('license-types', 'Master\LicenseTypeController::index');
});

// Admin Routes (untuk manager dan bod)
$routes->group('admin', ['filter' => 'role:manager,bod'], function ($routes) {
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
});

// Profile Routes (semua role yang login)
$routes->group('profile', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Auth\ProfileController::index');
    $routes->post('/update', 'Auth\ProfileController::update');
});

// Default route - TANPA FILTER
$routes->get('/', function () {
    if (session()->get('isLoggedIn')) {
        $role = session()->get('role');
        return redirect()->to("/{$role}/dashboard");
    }
    return redirect()->to('/login');
});
