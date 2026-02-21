<?php

class CompanyController extends Controller {
    public function show($slug) {
        $companyModel = new Company();
        $company = $companyModel->getBySlug($slug);

        if (!$company) {
            http_response_code(404);
            die("Empresa não encontrada.");
        }

        // Log view (simple implementation, ideally async or separate table)
        // $companyModel->incrementView($company['id']);

        $this->view('pages/company', [
            'title' => $company['name'] . ' - O Guia Metropolitano',
            'company' => $company
        ]);
    }
}
