<?php

class Auth {
    public static function attempt($email, $password) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            self::login($user);
            return true;
        }

        return false;
    }

    public static function login($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Remove password from session
        unset($user['password']);
        $_SESSION['user'] = $user;
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    public static function user() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }

    public static function id() {
        return self::user()['id'] ?? null;
    }

    public static function isAdmin() {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }

    public static function isOwner() {
        $user = self::user();
        return $user && ($user['role'] === 'owner' || $user['role'] === 'admin');
    }
}
