<?php

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456789_biblia'); // Exemplo Hostinger
define('DB_USER', 'u123456789_user');
define('DB_PASS', 'password');

// Configurações Gerais
define('SITE_URL', 'https://seusite.com/biblia-viva');
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
