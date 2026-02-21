<?php

// Front Controller

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/Core/',
        __DIR__ . '/../app/Controllers/',
        __DIR__ . '/../app/Models/',
        __DIR__ . '/../app/Helpers/',
        __DIR__ . '/../app/Services/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load Config (Global constant or registry if needed, but config.php is fine)
// We use require inside classes usually.

session_start();

// Router Setup
$router = new Router();

// Auth Routes
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'loginPost');
$router->get('/register', 'AuthController', 'register');
$router->post('/register', 'AuthController', 'registerPost');
$router->get('/logout', 'AuthController', 'logout');

// Dashboard Routes
$router->get('/dashboard', 'DashboardController', 'index');
$router->get('/dashboard/empresa', 'DashboardController', 'editCompany');
$router->post('/dashboard/empresa', 'DashboardController', 'updateCompany');

// Admin Routes
$router->get('/admin', 'AdminController', 'index');
$router->get('/admin/empresas', 'AdminController', 'companies');
$router->get('/admin/empresa/{id}/aprovar', 'AdminController', 'approveCompany');
$router->get('/admin/empresa/{id}/bloquear', 'AdminController', 'blockCompany');

// Public Routes
$router->get('/', 'HomeController', 'index');
$router->get('/busca', 'HomeController', 'search');
$router->get('/empresa/{slug}', 'CompanyController', 'show');

// AI API
$router->post('/api/chat', 'ApiController', 'chat');

// Dispatch
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Handle base path if in subdirectory (Hostinger specific)
// If the app is at /folder/index.php, we need to strip /folder
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName !== '/') {
    $uri = str_replace($scriptName, '', $uri);
}

// Fallback for empty uri
if ($uri === '') $uri = '/';

$router->dispatch($uri, $method);
