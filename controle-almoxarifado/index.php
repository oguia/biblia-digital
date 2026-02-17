<?php
session_start();

// Carrega o autoloader do Composer se existir
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    // Fallback simples se não rodou composer install ainda
    // (Apenas para exibir erro amigável, pois precisa das libs)
    die("<h1>Erro: Dependências não instaladas via Composer.</h1><p>Por favor, execute 'composer install' na raiz do projeto.</p>");
}

// Carrega configurações e banco de dados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/Functions.php';

// Roteamento Simples (Front Controller)
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'dashboard';
$urlParts = explode('/', $url);

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';
$params = array_slice($urlParts, 2);

// Mapeamento de rotas padrão
if ($urlParts[0] === '' || $urlParts[0] === 'index.php') {
    $controllerName = 'DashboardController';
    $methodName = 'index';
}

// Verifica autenticação (exceto login)
if (!isset($_SESSION['user_id']) && $controllerName !== 'AuthController') {
    $controllerName = 'AuthController';
    $methodName = 'login';
}

// Verifica se o arquivo do controller existe
$controllerFile = __DIR__ . '/app/Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        // Método não encontrado - 404
        http_response_code(404);
        require_once __DIR__ . '/app/Views/404.php';
    }
} else {
    // Controller não encontrado - 404
    http_response_code(404);
    // Tenta criar view 404 se não existir
    if (!file_exists(__DIR__ . '/app/Views/404.php')) {
        echo "<h1>404 - Página não encontrada</h1>";
    } else {
        require_once __DIR__ . '/app/Views/404.php';
    }
}
