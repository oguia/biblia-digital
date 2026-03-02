<?php

class AuthController extends Controller {

    public function login() {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                header('Location: /admin');
            } else {
                header('Location: /dashboard');
            }
            exit;
        }
        $this->view('auth/login');
    }

    public function loginPost() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            if (Auth::isAdmin()) {
                header('Location: /admin');
            } else {
                header('Location: /dashboard');
            }
            exit;
        }

        $this->view('auth/login', ['error' => 'Email ou senha incorretos.']);
    }

    public function register() {
        if (Auth::check()) {
            header('Location: /dashboard');
            exit;
        }
        $this->view('auth/register');
    }

    public function registerPost() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm) {
            $this->view('auth/register', ['error' => 'As senhas não coincidem.']);
            return;
        }

        if (strlen($password) < 6) {
            $this->view('auth/register', ['error' => 'A senha deve ter pelo menos 6 caracteres.']);
            return;
        }

        $db = Database::getInstance();

        // Check if email exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $this->view('auth/register', ['error' => 'Este email já está cadastrado.']);
            return;
        }

        // Insert
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'owner')");
        if ($stmt->execute([$name, $email, $hash])) {
            Auth::attempt($email, $password);
            header('Location: /dashboard');
            exit;
        }

        $this->view('auth/register', ['error' => 'Erro ao criar conta. Tente novamente.']);
    }

    public function logout() {
        Auth::logout();
        header('Location: /');
        exit;
    }
}
