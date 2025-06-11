<?php
// TripBazaar - Travel Marketplace
// Main Router and Application Entry Point

session_start();

// Include configuration files
require_once 'config/database.php';
require_once 'config/app.php';
require_once 'includes/functions.php';

// Set timezone
date_default_timezone_set(AppConfig::get('timezone', 'UTC'));

// Error reporting based on debug mode
if (AppConfig::isDebug()) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Check remember me token on every request
require_once 'controllers/AuthController.php';
$authController = new AuthController();
$authController->checkRememberToken();

// Get the current path using the new config system
$path = current_path();

// Remove query string
$path = strtok($path, '?');

// Simple routing system
switch ($path) {
    case '/':
        require_once 'controllers/PackageController.php';
        $controller = new PackageController();
        $controller->home();
        break;
        
    case '/packages':
        require_once 'controllers/PackageController.php';
        $controller = new PackageController();
        $controller->listing();
        break;
    
    // Authentication routes
    case '/login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->showLogin();
        break;
        
    case '/register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->showRegister();
        break;
        
    case '/auth/login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
        
    case '/auth/register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;
        
    case '/logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
        
    // Dashboard routes
    case '/customer/dashboard':
        requireLogin();
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->customerDashboard();
        break;
        
    case '/provider/dashboard':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->dashboard();
        break;
        
    case '/admin/dashboard':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->adminDashboard();
        break;
        
    // Customer routes
    case '/customer/bookings':
        requireLogin();
        requireRole('customer');
        require_once 'controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->bookings();
        break;
        
    case '/customer/profile':
        requireLogin();
        requireRole('customer');
        require_once 'controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->profile();
        break;
        
    case '/customer/reviews':
        requireLogin();
        requireRole('customer');
        require_once 'controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->reviews();
        break;
        
    // Package details route: /package/{slug}
    case '/provider/packages':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->packages();
        break;
        
    case '/provider/packages/create':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->createPackage();
        break;
        
    case '/provider/bookings':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->bookings();
        break;
        
    case '/provider/profile':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->profile();
        break;
        
    case '/provider/earnings':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->earnings();
        break;
        
    case '/provider/request-payout':
        requireLogin();
        requireRole('provider');
        require_once 'controllers/ProviderController.php';
        $controller = new ProviderController();
        $controller->requestPayout();
        break;
        
    // Admin routes
    case '/admin/payouts':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->payouts();
        break;
        
    case '/admin/users':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->users();
        break;
        
    case '/admin/providers':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->providers();
        break;
        
    case '/admin/packages':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->packages();
        break;
        
    case '/admin/packages/details':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->getPackageDetails();
        break;
        
    case '/admin/packages/action':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->packageAction();
        break;
        
    case '/admin/providers/details':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->getProviderDetails();
        break;
        
    case '/admin/providers/action':
        requireLogin();
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->providerAction();
        break;
        
    // Package details route: /package/{slug}
    default:
        if (preg_match('/^\/package\/([a-zA-Z0-9\-_]+)$/', $path, $matches)) {
            require_once 'controllers/PackageController.php';
            $controller = new PackageController();
            $controller->details($matches[1]);
        } elseif (preg_match('/^\/provider\/packages\/edit\/(\d+)$/', $path, $matches)) {
            requireLogin();
            requireRole('provider');
            require_once 'controllers/ProviderController.php';
            $controller = new ProviderController();
            $controller->editPackage($matches[1]);
        } elseif (preg_match('/^\/provider\/packages\/delete\/(\d+)$/', $path, $matches)) {
            requireLogin();
            requireRole('provider');
            require_once 'controllers/ProviderController.php';
            $controller = new ProviderController();
            $controller->deletePackage($matches[1]);
        } elseif (preg_match('/^\/provider\/bookings\/update-status\/(\d+)$/', $path, $matches)) {
            requireLogin();
            requireRole('provider');
            require_once 'controllers/ProviderController.php';
            $controller = new ProviderController();
            $controller->updateBookingStatus($matches[1]);
        } elseif (preg_match('/^\/admin\/payouts\/approve\/(\d+)$/', $path, $matches)) {
            requireLogin();
            requireRole('admin');
            require_once 'controllers/AdminController.php';
            $controller = new AdminController();
            $controller->approvePayout($matches[1]);
        } elseif (preg_match('/^\/admin\/payouts\/reject\/(\d+)$/', $path, $matches)) {
            requireLogin();
            requireRole('admin');
            require_once 'controllers/AdminController.php';
            $controller = new AdminController();
            $controller->rejectPayout($matches[1]);
        } elseif ($path === '/admin/providers/update-commission') {
            requireLogin();
            requireRole('admin');
            require_once 'controllers/AdminController.php';
            $controller = new AdminController();
            $controller->updateProviderCommission();
        } else {
            // 404 - Page not found
            http_response_code(404);
            $pageTitle = '404 - Page Not Found';
            $pageDescription = 'The page you are looking for could not be found.';
            include 'views/404.php';
        }
        break;
}
?> 