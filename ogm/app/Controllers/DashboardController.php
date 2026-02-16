<?php

class DashboardController extends Controller {

    public function __construct() {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $db = Database::getInstance();
        $userId = Auth::id();

        // Get Company
        $stmt = $db->prepare("SELECT * FROM companies WHERE user_id = ?");
        $stmt->execute([$userId]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$company) {
            $this->view('dashboard/index', ['company' => null]);
            return;
        }

        // Get Stats (Mocking monthly reports as simpler stats for now)
        // In a real app, we'd have a clicks/views log table with dates.
        // For now, we use the columns in companies table.

        $this->view('dashboard/index', ['company' => $company]);
    }

    public function editCompany() {
        $db = Database::getInstance();
        $userId = Auth::id();

        $stmt = $db->prepare("SELECT * FROM companies WHERE user_id = ?");
        $stmt->execute([$userId]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get Categories and Neighborhoods for dropdowns
        $categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        $neighborhoods = $db->query("SELECT * FROM neighborhoods ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('dashboard/edit_company', [
            'company' => $company,
            'categories' => $categories,
            'neighborhoods' => $neighborhoods
        ]);
    }

    public function updateCompany() {
        $db = Database::getInstance();
        $userId = Auth::id();

        // Validation (Basic)
        $name = $_POST['name'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $categoryId = $_POST['category_id'] ?? null;
        $neighborhoodId = $_POST['neighborhood_id'] ?? null;
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $whatsapp = $_POST['whatsapp'] ?? '';

        // Handle Image Upload (Optional)
        // For now, we rely on the dynamic image or existing image.
        // If file upload is needed, we'd process $_FILES['image'].

        // Check if company exists
        $stmt = $db->prepare("SELECT id FROM companies WHERE user_id = ?");
        $stmt->execute([$userId]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($company) {
            // Update
            $sql = "UPDATE companies SET
                name = ?, category_id = ?, neighborhood_id = ?, description = ?,
                address = ?, phone = ?, whatsapp = ?
                WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $categoryId, $neighborhoodId, $description, $address, $phone, $whatsapp, $company['id']]);
        } else {
            // Create
            // Ensure slug is unique
            $check = $db->prepare("SELECT id FROM companies WHERE slug = ?");
            $check->execute([$slug]);
            if ($check->fetch()) {
                $slug .= '-' . time();
            }

            $sql = "INSERT INTO companies (user_id, name, slug, category_id, neighborhood_id, description, address, phone, whatsapp, status, is_featured)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 0)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId, $name, $slug, $categoryId, $neighborhoodId, $description, $address, $phone, $whatsapp]);
        }

        header('Location: /dashboard');
        exit;
    }
}
