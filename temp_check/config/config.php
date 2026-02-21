<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'u123456789_ogm',
        'user' => getenv('DB_USER') ?: 'u123456789_user',
        'pass' => getenv('DB_PASS') ?: 'password',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'name' => 'O Guia Metropolitano',
        'url' => getenv('APP_URL') ?: 'https://oguiametropolitano.com.br',
        'env' => getenv('APP_ENV') ?: 'production',
        'gemini_api_key' => getenv('GEMINI_API_KEY') ?: 'YOUR_GEMINI_API_KEY_HERE'
    ]
];
