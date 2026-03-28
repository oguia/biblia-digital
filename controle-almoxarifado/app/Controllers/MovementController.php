<?php

require_once __DIR__ . '/../Models/Movement.php';
require_once __DIR__ . '/../Models/Product.php';

class MovementController {
    private $movementModel;
    private $productModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
        $this->movementModel = new Movement();
        $this->productModel = new Product();
    }

    public function index() {
        $movements = $this->movementModel->getAll();
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/movements/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function create() {
        $products = $this->productModel->getAll();
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/movements/create.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token($_POST['csrf_token'] ?? '');

            $produto_id = $_POST['produto_id'];
            $tipo = $_POST['tipo'];
            $quantidade = (float)$_POST['quantidade'];
            $observacao = $_POST['observacao'];

            if (!$produto_id || !$tipo || $quantidade <= 0) {
                $error = "Preencha os campos corretamente. Quantidade deve ser positiva.";
                $products = $this->productModel->getAll();
                require_once __DIR__ . '/../Views/layouts/header.php';
                require_once __DIR__ . '/../Views/movements/create.php';
                require_once __DIR__ . '/../Views/layouts/footer.php';
                return;
            }

            $product = $this->productModel->getById($produto_id);
            if (!$product) {
                redirect('movements/create');
            }

            $currentStock = (float)$product['quantidade'];
            $newStock = $currentStock;

            if ($tipo === 'entrada') {
                $newStock += $quantidade;
            } elseif ($tipo === 'saida') {
                if ($quantidade > $currentStock) {
                    $error = "Estoque insuficiente. Disponível: " . $currentStock;
                    $products = $this->productModel->getAll();
                    require_once __DIR__ . '/../Views/layouts/header.php';
                    require_once __DIR__ . '/../Views/movements/create.php';
                    require_once __DIR__ . '/../Views/layouts/footer.php';
                    return;
                }
                $newStock -= $quantidade;
            } else {
                redirect('movements/create');
            }

            // Transactional integrity is ideal, but for now simple steps:
            // 1. Insert Movement
            // 2. Update Product

            $movementData = [
                'produto_id' => $produto_id,
                'usuario_id' => $_SESSION['user_id'],
                'tipo' => $tipo,
                'quantidade' => $quantidade,
                'observacao' => $observacao
            ];

            try {
                // Should use transaction here properly with Database::getConnection()->beginTransaction()
                $pdo = Database::getConnection();
                $pdo->beginTransaction();

                $this->movementModel->create($movementData);
                $this->productModel->updateStock($produto_id, $newStock);

                $pdo->commit();

                // Redireciona com mensagem de sucesso (pode implementar flash messages depois)
                redirect('movements/index');
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Erro ao registrar movimentação: " . $e->getMessage();
                $products = $this->productModel->getAll();
                require_once __DIR__ . '/../Views/layouts/header.php';
                require_once __DIR__ . '/../Views/movements/create.php';
                require_once __DIR__ . '/../Views/layouts/footer.php';
            }
        }
    }

    public function export() {
        $movements = $this->movementModel->getAll(10000); // Export large limit

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalho
        $headers = ['Data', 'Código Produto', 'Produto', 'Tipo', 'Quantidade', 'Responsável', 'Observação'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Dados
        $rowNum = 2;
        foreach ($movements as $mov) {
            $sheet->setCellValue('A' . $rowNum, date('d/m/Y H:i', strtotime($mov['data_movimentacao'])));
            $sheet->setCellValue('B' . $rowNum, $mov['produto_codigo']);
            $sheet->setCellValue('C' . $rowNum, $mov['produto_nome']);
            $sheet->setCellValue('D' . $rowNum, $mov['tipo']);
            $sheet->setCellValue('E' . $rowNum, $mov['quantidade']);
            $sheet->setCellValue('F' . $rowNum, $mov['usuario_nome'] ?? 'Sistema');
            $sheet->setCellValue('G' . $rowNum, $mov['observacao']);
            $rowNum++;
        }

        // Output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="movimentacoes_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
