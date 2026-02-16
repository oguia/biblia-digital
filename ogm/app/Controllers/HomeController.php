<?php

class HomeController extends Controller {
    public function index() {
        $categoryModel = new Category();
        $companyModel = new Company();

        $categories = $categoryModel->getPopular(8);
        $featured = $companyModel->getFeatured(6);

        $this->view('pages/home', [
            'title' => 'O Guia Metropolitano - Curitiba e Região',
            'categories' => $categories,
            'featured' => $featured
        ]);
    }

    public function search() {
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
    }
}
