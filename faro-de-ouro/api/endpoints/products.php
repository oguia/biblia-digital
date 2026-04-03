<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$user = require_auth();
$tenant_id = require_tenant($user);
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Basic tenant access check
if ($user['role'] !== 'superadmin' && $user['role'] !== 'owner' && $user['role'] !== 'operator') {
    http_response_code(403); die(json_encode(['error' => 'Forbidden']));
}

if ($method == 'GET') {
    if ($action == 'list') {
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, s.name as supplier_name
                              FROM products p
                              LEFT JOIN categories c ON p.category_id = c.id
                              LEFT JOIN suppliers s ON p.supplier_id = s.id
                              WHERE p.tenant_id = ?");
        $stmt->execute([$tenant_id]);
        echo json_encode($stmt->fetchAll());
    } elseif ($action == 'dashboard') {
         $stmt = $db->prepare("SELECT COUNT(*) as total_products FROM products WHERE tenant_id = ?");
         $stmt->execute([$tenant_id]);
         $total_products = $stmt->fetchColumn();

         $stmt = $db->prepare("SELECT COUNT(*) as low_stock FROM products WHERE tenant_id = ? AND current_stock <= min_stock");
         $stmt->execute([$tenant_id]);
         $low_stock = $stmt->fetchColumn();

         echo json_encode(['total_products' => $total_products, 'low_stock' => $low_stock]);
    } elseif ($action == 'kardex') {
        $product_id = $_GET['product_id'] ?? null;
        if($product_id) {
             $stmt = $db->prepare("SELECT m.*, u.name as user_name FROM movements m
                                   JOIN users u ON m.user_id = u.id
                                   WHERE m.tenant_id = ? AND m.product_id = ? ORDER BY m.created_at DESC");
             $stmt->execute([$tenant_id, $product_id]);
        } else {
             $stmt = $db->prepare("SELECT m.*, p.name as product_name, u.name as user_name FROM movements m
                                   JOIN products p ON m.product_id = p.id
                                   JOIN users u ON m.user_id = u.id
                                   WHERE m.tenant_id = ? ORDER BY m.created_at DESC LIMIT 50");
             $stmt->execute([$tenant_id]);
        }
        echo json_encode($stmt->fetchAll());
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($action == 'create') {
        $stmt = $db->prepare("INSERT INTO products (tenant_id, code, name, category_id, supplier_id, price, min_stock, current_stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $tenant_id,
            $data['code'] ?? null,
            $data['name'],
            $data['category_id'] ?? null,
            $data['supplier_id'] ?? null,
            $data['price'] ?? 0,
            $data['min_stock'] ?? 0,
            $data['current_stock'] ?? 0
        ]);

        $product_id = $db->lastInsertId();

        // Initial movement if stock > 0
        if (($data['current_stock'] ?? 0) > 0) {
             $stmt_mov = $db->prepare("INSERT INTO movements (tenant_id, product_id, user_id, type, quantity, reason) VALUES (?, ?, ?, 'in', ?, 'Faro inicial')");
             $stmt_mov->execute([$tenant_id, $product_id, $user['id'], $data['current_stock']]);
        }

        echo json_encode(['success' => true, 'id' => $product_id]);
    } elseif ($action == 'movement') {
        // type: in, out, adjustment
        $product_id = $data['product_id'];
        $type = $data['type'];
        $qty = (int)$data['quantity'];
        $reason = $data['reason'] ?? '';

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT current_stock FROM products WHERE id = ? AND tenant_id = ?");
            $stmt->execute([$product_id, $tenant_id]);
            $current = $stmt->fetchColumn();

            if ($current === false) throw new Exception("Product not found");

            $new_stock = $current;
            if ($type == 'in') {
                $new_stock += $qty;
            } elseif ($type == 'out') {
                if ($current < $qty) throw new Exception("Insufficient stock");
                $new_stock -= $qty;
            } elseif ($type == 'adjustment') {
                // For adjustment, qty is the new absolute stock
                $qty = $qty - $current; // calculate diff for the movement log
                $type = $qty >= 0 ? 'in' : 'out';
                $qty = abs($qty);
                $new_stock = $current + ($type == 'in' ? $qty : -$qty);
            }

            $stmt = $db->prepare("UPDATE products SET current_stock = ? WHERE id = ?");
            $stmt->execute([$new_stock, $product_id]);

            $stmt = $db->prepare("INSERT INTO movements (tenant_id, product_id, user_id, type, quantity, reason) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$tenant_id, $product_id, $user['id'], $type, $qty, $reason]);

            $db->commit();
            echo json_encode(['success' => true, 'new_stock' => $new_stock]);
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} elseif ($method == 'PUT') {
     $data = json_decode(file_get_contents('php://input'), true);
     if($action == 'update') {
         $id = $data['id'];
         $stmt = $db->prepare("UPDATE products SET code=?, name=?, category_id=?, supplier_id=?, price=?, min_stock=? WHERE id=? AND tenant_id=?");
         $stmt->execute([
            $data['code'] ?? null,
            $data['name'],
            $data['category_id'] ?? null,
            $data['supplier_id'] ?? null,
            $data['price'] ?? 0,
            $data['min_stock'] ?? 0,
            $id,
            $tenant_id
         ]);
         echo json_encode(['success' => true]);
     }
} elseif ($method == 'DELETE') {
     $id = $_GET['id'] ?? null;
     if($id) {
         $stmt = $db->prepare("DELETE FROM products WHERE id=? AND tenant_id=?");
         $stmt->execute([$id, $tenant_id]);
         echo json_encode(['success' => true]);
     }
}
