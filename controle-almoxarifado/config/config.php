<?php

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456789_almoxarifado'); // Exemplo Hostinger
define('DB_USER', 'u123456789_admin');
define('DB_PASS', 'SuaSenhaForte123!');

// Configurações da Aplicação
define('APP_URL', 'http://seusite.com/controle-almoxarifado'); // Ajuste conforme necessário
define('APP_NAME', 'Controle de Almoxarifado');

// Fuso Horário
date_default_timezone_set('America/Sao_Paulo');

// Exibição de Erros (Desative em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
