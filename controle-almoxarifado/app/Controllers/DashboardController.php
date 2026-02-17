<?php

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Movement.php';

class DashboardController {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }

        $productModel = new Product();
        $movementModel = new Movement();

        $stats = [
            'total_products' => $productModel->count(),
            'total_stock' => $productModel->sumStock(),
            'low_stock_count' => $productModel->countLowStock(),
        ];

        $monthSummary = $movementModel->getSummary();
        $lowStockProducts = $productModel->getLowStockProducts(5);
        $lastMovements = $movementModel->getAll(5);
        $chartData = $movementModel->getChartData(30);

        // Prepare data for Chart.js
        $chartLabels = [];
        $chartInputs = [];
        $chartOutputs = [];

        foreach ($chartData as $row) {
            $chartLabels[] = date('d/m', strtotime($row['data']));
            $chartInputs[] = (float)$row['entrada'];
            $chartOutputs[] = (float)$row['saida'];
        }

        // Pass variables to view
        $data = [
            'stats' => $stats,
            'monthSummary' => $monthSummary,
            'lowStockProducts' => $lowStockProducts,
            'lastMovements' => $lastMovements,
            'chartLabels' => json_encode($chartLabels),
            'chartInputs' => json_encode($chartInputs),
            'chartOutputs' => json_encode($chartOutputs)
        ];

        // Extract to make variables available in view
        extract($data);

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/dashboard/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
}
