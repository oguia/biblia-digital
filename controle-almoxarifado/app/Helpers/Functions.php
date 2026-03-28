<?php

// Funções Auxiliares Gerais

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: " . base_url($url));
        exit;
    }
}

if (!function_exists('dd')) {
    function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            die('CSRF Token inválido. Recarregue a página.');
        }
        return true;
    }
}
