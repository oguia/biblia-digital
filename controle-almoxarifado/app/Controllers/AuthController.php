<?php

require_once __DIR__ . '/../Models/User.php';

class AuthController {

    public function login() {
        // Se já estiver logado, redireciona para dashboard
        if (isset($_SESSION['user_id'])) {
            redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            require_once __DIR__ . '/../Views/auth/login.php';
        }
    }

    private function processLogin() {
        // Verificar CSRF
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die("Erro de segurança (CSRF).");
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $error = "Preencha todos os campos.";
            require_once __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $userModel = new User();
        $user = $userModel->checkCredentials($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_level'] = $user['nivel'];
            redirect('dashboard');
        } else {
            $error = "E-mail ou senha incorretos.";
            require_once __DIR__ . '/../Views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('auth/login');
    }
}
