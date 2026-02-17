<?php

require_once __DIR__ . '/../Models/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
        $this->categoryModel = new Category();
    }

    public function index() {
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/categories/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function create() {
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/categories/form.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token($_POST['csrf_token'] ?? '');

            $nome = trim($_POST['nome']);

            if (empty($nome)) {
                $error = "Nome é obrigatório.";
                require_once __DIR__ . '/../Views/layouts/header.php';
                require_once __DIR__ . '/../Views/categories/form.php';
                require_once __DIR__ . '/../Views/layouts/footer.php';
                return;
            }

            if ($this->categoryModel->create($nome)) {
                redirect('categories/index');
            } else {
                $error = "Erro ao criar categoria.";
                require_once __DIR__ . '/../Views/layouts/header.php';
                require_once __DIR__ . '/../Views/categories/form.php';
                require_once __DIR__ . '/../Views/layouts/footer.php';
            }
        }
    }

    public function edit($id) {
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            redirect('categories/index');
        }

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/categories/form.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token($_POST['csrf_token'] ?? '');

            $nome = trim($_POST['nome']);

            if (empty($nome)) {
                $category = $this->categoryModel->getById($id);
                $error = "Nome é obrigatório.";
                require_once __DIR__ . '/../Views/layouts/header.php';
                require_once __DIR__ . '/../Views/categories/form.php';
                require_once __DIR__ . '/../Views/layouts/footer.php';
                return;
            }

            if ($this->categoryModel->update($id, $nome)) {
                redirect('categories/index');
            } else {
                $error = "Erro ao atualizar categoria.";
                $category = $this->categoryModel->getById($id); // Refetch to show in form
                require_once __DIR__ . '/../Views/layouts/header.php';
                require_once __DIR__ . '/../Views/categories/form.php';
                require_once __DIR__ . '/../Views/layouts/footer.php';
            }
        }
    }

    public function delete($id) {
        // CSRF protection for delete actions via GET is tricky, ideally use POST
        // But for simplicity in this project context, we check if user is admin or authorized
        // Or we require a confirmation page.
        // Better: Delete should be a POST form.

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             verify_csrf_token($_POST['csrf_token'] ?? '');
             $this->categoryModel->delete($id);
             redirect('categories/index');
        } else {
            // Se for GET, mostra confirmação ou erro.
            // Para simplificar, vou assumir que a view index.php fará um POST via form.
            redirect('categories/index');
        }
    }
}
