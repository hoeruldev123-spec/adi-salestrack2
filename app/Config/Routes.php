<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes - TANPA FILTER
$routes->get('login', 'Auth\AuthController::index');
$routes->post('login', 'Auth\AuthController::attemptLogin');
$routes->get('logout', 'Auth\AuthController::logout');

// Dashboard Routes - Redirect berdasarkan role
$routes->get('dashboard', function () {
    if (session()->get('isLoggedIn')) {
        $role = session()->get('role');
        return redirect()->to("/{$role}/dashboard");
    }
    return redirect()->to('/login');
}, ['filter' => 'auth']); // Tambahkan filter auth

// Dashboard Routes berdasarkan Role
$routes->group('sales', ['filter' => 'role:sales'], function ($routes) {
    $routes->get('dashboard', 'Dashboard\SalesDashboardController::index');

    // Opportunities Routes
    $routes->get('opportunities', 'Opportunity\OpportunityController::index');
    $routes->get('opportunities/create', 'Opportunity\OpportunityController::create');
    $routes->post('opportunities/store', 'Opportunity\OpportunityController::store');
    $routes->get('opportunities/edit/(:num)', 'Opportunity\OpportunityController::edit/$1');
    $routes->post('opportunities/update/(:num)', 'Opportunity\OpportunityController::update/$1');
    $routes->get('opportunities/delete/(:num)', 'Opportunity\OpportunityController::delete/$1');
    $routes->get('opportunities/(:num)', 'Opportunity\OpportunityController::show/$1'); // View detail
    $routes->post('opportunities/update-status/(:num)', 'Opportunity\OpportunityController::updateStatus/$1');
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
    // Accounts
    $routes->get('accounts', 'Master\AccountController::index');
    $routes->get('accounts/create', 'Master\AccountController::create');
    $routes->post('accounts/store', 'Master\AccountController::store');
    $routes->get('accounts/edit/(:num)', 'Master\AccountController::edit/$1');
    $routes->post('accounts/update/(:num)', 'Master\AccountController::update/$1');
    $routes->get('accounts/delete/(:num)', 'Master\AccountController::delete/$1');
    $routes->get('accounts/toggle/(:num)', 'Master\AccountController::toggle/$1');

    // Principals
    $routes->get('principals', 'Master\PrincipalController::index');
    $routes->get('principals/create', 'Master\PrincipalController::create');
    $routes->post('principals/store', 'Master\PrincipalController::store');
    $routes->get('principals/edit/(:num)', 'Master\PrincipalController::edit/$1');
    $routes->post('principals/update/(:num)', 'Master\PrincipalController::update/$1');
    $routes->get('principals/delete/(:num)', 'Master\PrincipalController::delete/$1');
    $routes->get('principals/toggle/(:num)', 'Master\PrincipalController::toggle/$1');

    // Solution Architects
    $routes->get('solution-architects', 'Master\SolutionArchitectController::index');
    $routes->get('solution-architects/create', 'Master\SolutionArchitectController::create');
    $routes->post('solution-architects/store', 'Master\SolutionArchitectController::store');
    $routes->get('solution-architects/edit/(:num)', 'Master\SolutionArchitectController::edit/$1');
    $routes->post('solution-architects/update/(:num)', 'Master\SolutionArchitectController::update/$1');
    $routes->get('solution-architects/delete/(:num)', 'Master\SolutionArchitectController::delete/$1');
    $routes->get('solution-architects/toggle/(:num)', 'Master\SolutionArchitectController::toggle/$1');

    // License Types
    $routes->get('license-types', 'Master\LicenseTypeController::index');
    $routes->get('license-types/create', 'Master\LicenseTypeController::create');
    $routes->post('license-types/store', 'Master\LicenseTypeController::store');
    $routes->get('license-types/edit/(:num)', 'Master\LicenseTypeController::edit/$1');
    $routes->post('license-types/update/(:num)', 'Master\LicenseTypeController::update/$1');
    $routes->get('license-types/delete/(:num)', 'Master\LicenseTypeController::delete/$1');
    $routes->get('license-types/toggle/(:num)', 'Master\LicenseTypeController::toggle/$1');
});

// Admin Routes (untuk manager dan bod)
$routes->group('admin', ['filter' => 'role:manager,bod'], function ($routes) {
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->get('users/add', 'Admin\UserController::add');

    // Tambahkan route untuk edit dan delete
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->delete('users/delete/(:num)', 'Admin\UserController::delete/$1');
});

// app/Config/Routes.php
$routes->get('check-session', function () {
    $session = session();
    return $this->response->setJSON([
        'user_id' => $session->get('user_id'),
        'role' => $session->get('role'),
        'name' => $session->get('name')
    ]);
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

// Reports Routes
$routes->group('reports', ['filter' => 'auth'], function ($routes) {
    // Sales Reports (untuk sales dan manager)
    $routes->group('sales', function ($routes) {
        $routes->get('activity', 'Report\SalesReportController::activity');
        $routes->get('performance', 'Report\SalesReportController::performance');
        $routes->get('pipeline', 'Report\SalesReportController::pipeline');
        $routes->post('export', 'Report\SalesReportController::export');
    });

    // Revenue Reports (untuk manager dan bod)
    $routes->group('revenue', ['filter' => 'role:manager,bod'], function ($routes) {
        $routes->get('overview', 'Report\RevenueReportController::overview');
        $routes->get('forecast', 'Report\RevenueReportController::forecast');
        $routes->get('comparison', 'Report\RevenueReportController::comparison');
    });

    // Ranking Reports (untuk manager dan bod)
    $routes->group('ranking', ['filter' => 'role:manager,bod'], function ($routes) {
        $routes->get('sales-performance', 'Report\RankingController::salesPerformance');
        $routes->get('principal-performance', 'Report\RankingController::principalPerformance');
        $routes->get('account-performance', 'Report\RankingController::accountPerformance');
    });
});
