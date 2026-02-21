<?php

class AdminController extends Controller {

    public function __construct() {
        if (!Auth::isAdmin()) {
            header('Location: /');
            exit;
        }
    }

    public function index() {
        $db = Database::getInstance();

        // Stats
        $stats = [
            'total_companies' => $db->query("SELECT COUNT(*) FROM companies")->fetchColumn(),
            'active_companies' => $db->query("SELECT COUNT(*) FROM companies WHERE status = 'active'")->fetchColumn(),
            'pending_companies' => $db->query("SELECT COUNT(*) FROM companies WHERE status = 'pending'")->fetchColumn(),
            'total_users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_reviews' => $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
        ];

        // Recent Companies
        $recent = $db->query("SELECT * FROM companies ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('admin/index', ['stats' => $stats, 'recent' => $recent]);
    }

    public function companies() {
        $db = Database::getInstance();

        $status = $_GET['status'] ?? 'all';
        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT c.*, cat.name as category_name, n.name as neighborhood_name, u.email as owner_email
                FROM companies c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN neighborhoods n ON c.neighborhood_id = n.id
                LEFT JOIN users u ON c.user_id = u.id";

        $params = [];
        if ($status !== 'all') {
            $sql .= " WHERE c.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.created_at DESC LIMIT $limit OFFSET $offset";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count for pagination
        $countSql = "SELECT COUNT(*) FROM companies";
        if ($status !== 'all') {
            $countSql .= " WHERE status = ?";
        }
        $countStmt = $db->prepare($countSql);
        if ($status !== 'all') {
            $countStmt->execute([$status]);
        } else {
            $countStmt->execute();
        }
        $total = $countStmt->fetchColumn();
        $totalPages = ceil($total / $limit);

        $this->view('admin/companies', [
            'companies' => $companies,
            'page' => $page,
            'totalPages' => $totalPages,
            'status' => $status
        ]);
    }

    public function approveCompany($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE companies SET status = 'active' WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/empresas?status=pending');
        exit;
    }

    public function blockCompany($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE companies SET status = 'blocked' WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/empresas');
        exit;
    }
}
