<?php

class Controller {
    protected function view($view, $data = []) {
        extract($data);

        // Check if view exists
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View '$view' not found.");
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
