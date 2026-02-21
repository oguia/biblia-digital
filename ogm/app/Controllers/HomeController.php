<?php

class HomeController extends Controller {
    public function index() {
        try {
            $categoryModel = new Category();
            $companyModel = new Company();

            $categories = $categoryModel->getPopular(8);
            $featured = $companyModel->getFeatured(6);

            $this->view('pages/home', [
                'title' => 'O Guia Metropolitano - Curitiba e Região',
                'categories' => $categories,
                'featured' => $featured
            ]);
        } catch (Exception $e) {
            error_log("Error in HomeController::index: " . $e->getMessage());
            echo "Ocorreu um erro ao carregar a página inicial. Por favor, tente novamente mais tarde.";
        }
    }

    public function search() {
        try {
            $companyModel = new Company();
            $query = $_GET['q'] ?? '';
            $category = $_GET['category'] ?? null;
            $neighborhood = $_GET['neighborhood'] ?? null;

            $companies = $companyModel->search($query, $category, $neighborhood);

            $this->view('pages/search', [
                'title' => 'Busca - O Guia Metropolitano',
                'companies' => $companies,
                'query' => $query,
                'filters' => [
                    'category' => $category,
                    'neighborhood' => $neighborhood
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error in HomeController::search: " . $e->getMessage());
            // Show a friendly error page or message
            $this->view('layouts/main', [
                'content' => '<div class="container mx-auto py-10 px-4"><div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Ocorreu um erro na busca. Tente novamente.</div></div>',
                'title' => 'Erro na Busca'
            ]);
        }
    }
}
