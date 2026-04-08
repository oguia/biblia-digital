<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$user = require_auth();
$tenant_id = require_tenant($user);
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Aggressive Auto-migrate schema using Table Recreation pattern if column doesn't exist
try {
    // Check if column exists
    $stmt = $db->query("PRAGMA table_info(products)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasUnit = false;
    foreach($columns as $col) {
        if ($col['name'] === 'unit') {
            $hasUnit = true;
            break;
        }
    }

    if (!$hasUnit) {
        $db->beginTransaction();

        // 1. Rename old table
        $db->exec("ALTER TABLE products RENAME TO products_old");

        // 2. Create new table with new schema
        $db->exec("CREATE TABLE products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tenant_id INTEGER NOT NULL,
            code TEXT,
            name TEXT NOT NULL,
            category_id INTEGER,
            supplier_id INTEGER,
            price REAL DEFAULT 0,
            min_stock INTEGER DEFAULT 0,
            current_stock INTEGER DEFAULT 0,
            unit TEXT,
            location TEXT,
            observation TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(tenant_id) REFERENCES tenants(id),
            FOREIGN KEY(category_id) REFERENCES categories(id),
            FOREIGN KEY(supplier_id) REFERENCES suppliers(id)
        )");

        // 3. Copy old data to new table
        $db->exec("INSERT INTO products (id, tenant_id, code, name, category_id, supplier_id, price, min_stock, current_stock, created_at)
                   SELECT id, tenant_id, code, name, category_id, supplier_id, price, min_stock, current_stock, created_at
                   FROM products_old");

        // 4. Drop old table
        $db->exec("DROP TABLE products_old");

        $db->commit();

        // Force refresh
        $db = getDB();
    }
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
}

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
        $stmt = $db->prepare("INSERT INTO products (tenant_id, code, name, category_id, supplier_id, price, min_stock, current_stock, unit, location, observation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $tenant_id,
            $data['code'] ?? null,
            $data['name'],
            $data['category_id'] ?? null,
            $data['supplier_id'] ?? null,
            $data['price'] ?? 0,
            $data['min_stock'] ?? 0,
            $data['current_stock'] ?? 0,
            $data['unit'] ?? null,
            $data['location'] ?? null,
            $data['observation'] ?? null
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
    } elseif ($action == 'import_csv') {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
            http_response_code(400);
            die(json_encode(['error' => 'Nenhum arquivo recebido ou erro no upload.']));
        }

        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExtension != 'csv') {
            http_response_code(400);
            die(json_encode(['error' => 'Por favor, envie apenas arquivos .csv']));
        }

        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            $importedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;

            // Detect separator (try semicolon first, then comma)
            $firstLine = fgets($handle);
            $separator = strpos($firstLine, ';') !== false ? ';' : ',';
            rewind($handle);

            $db->beginTransaction();
            try {
                // Read all lines to detect header offset (skip until "SEQUÊNCIA" or "NOME")
                $headerOffset = 0;
                $colMap = [];
                $isGpcFormat = false;

                while (($data = fgetcsv($handle, 4000, $separator)) !== FALSE) {
                    // Check all columns in this row to detect header
                    foreach($data as $col) {
                        $cleanCol = trim($col);
                        // Clean BOM if exists
                        if (substr($cleanCol, 0, 3) == "\xEF\xBB\xBF") {
                            $cleanCol = substr($cleanCol, 3);
                        }

                        if (stripos($cleanCol, 'SEQU') !== false) {
                            $isGpcFormat = true;
                            // Skip the second header row in GPC format
                            fgetcsv($handle, 4000, $separator);
                            break 2;
                        } elseif (stripos($cleanCol, 'Nome') !== false || stripos($cleanCol, 'Name') !== false) {
                            break 2;
                        }
                    }
                }

                if (!$isGpcFormat) {
                    rewind($handle);
                    fgetcsv($handle, 4000, $separator); // Skip header of standard format
                }


                $catCache = [];

                while (($data = fgetcsv($handle, 4000, $separator)) !== FALSE) {
                    if (empty(trim(implode("", $data)))) continue;

                    $name = '';
                    $code = '';
                    $price = 0;
                    $current_stock = 0;
                    $min_stock = 0;
                    $category_name = '';
                    $unit = '';
                    $location = '';
                    $observation = '';

                    if ($isGpcFormat) {
                        // GPC format mapping
                        // 0: SEQUENCIA, 2: DESCRIÇÃO COMPLETA, 5: CÓDIGO CONTÁBIL (sku), 7: CLASSIFICAÇÃO, 9: UNIDADE MEDIDA, 10: CUSTO UNIT, 11: ESTOQUE MINIMO, 18: ENDEREÇO FINAL, 19: OBSERVAÇÃO
                        $name = trim($data[2] ?? '');
                        $code = trim($data[5] ?? '');
                        if (empty($code) || $code === 'NÃO POSSUI') {
                            $code = trim($data[0] ?? ''); // Fallback to Sequence as SKU
                        }

                        $category_name = trim($data[7] ?? '');
                        $unit = trim($data[9] ?? '');
                        $location = trim($data[18] ?? '');
                        $observation = trim($data[19] ?? '');

                        $priceStr = trim($data[10] ?? '0');
                        // Clean price format "R$ 1.250,00" -> "1250.00"
                        $priceStr = str_replace(['R$', ' ', '.'], '', $priceStr);
                        $priceStr = str_replace(',', '.', $priceStr);
                        $price = floatval($priceStr);

                        $min_stock = (int)($data[11] ?? 0);
                        $current_stock = 0; // Assume 0 as GPC CSV does not seem to have current stock clearly
                    } else {
                        // Standard Faro de Ouro format
                        // 0: Nome, 1: Código, 2: Preço, 3: Estoque Atual, 4: Estoque Mínimo
                        $name = trim($data[0] ?? '');
                        $code = trim($data[1] ?? '');
                        $price = floatval(str_replace(['R$', ' ', ','], ['', '', '.'], $data[2] ?? 0));
                        $current_stock = (int)($data[3] ?? 0);
                        $min_stock = (int)($data[4] ?? 0);
                    }

                    if (empty($name)) {
                        $skippedCount++;
                        continue;
                    }

                    $category_id = null;
                    if (!empty($category_name)) {
                        if (isset($catCache[$category_name])) {
                            $category_id = $catCache[$category_name];
                        } else {
                            $stmtCat = $db->prepare("SELECT id FROM categories WHERE name = ? AND tenant_id = ?");
                            $stmtCat->execute([$category_name, $tenant_id]);
                            $category_id = $stmtCat->fetchColumn();

                            if (!$category_id) {
                                $stmtInsCat = $db->prepare("INSERT INTO categories (tenant_id, name) VALUES (?, ?)");
                                $stmtInsCat->execute([$tenant_id, $category_name]);
                                $category_id = $db->lastInsertId();
                            }
                            $catCache[$category_name] = $category_id;
                        }
                    }

                    $existingId = null;
                    if (!empty($code)) {
                        $stmtCheck = $db->prepare("SELECT id FROM products WHERE code = ? AND tenant_id = ?");
                        $stmtCheck->execute([$code, $tenant_id]);
                        $existingId = $stmtCheck->fetchColumn();
                    }

                    if ($existingId) {
                        // Update existing product
                        if ($category_id !== null) {
                            $stmtUp = $db->prepare("UPDATE products SET name = ?, price = ?, min_stock = ?, category_id = ?, unit = ?, location = ?, observation = ? WHERE id = ?");
                            $stmtUp->execute([$name, $price, $min_stock, $category_id, $unit, $location, $observation, $existingId]);
                        } else {
                            $stmtUp = $db->prepare("UPDATE products SET name = ?, price = ?, min_stock = ?, unit = ?, location = ?, observation = ? WHERE id = ?");
                            $stmtUp->execute([$name, $price, $min_stock, $unit, $location, $observation, $existingId]);
                        }

                        if (!$isGpcFormat && isset($data[3]) && $data[3] !== '') {
                            $stmtStk = $db->prepare("SELECT current_stock FROM products WHERE id = ?");
                            $stmtStk->execute([$existingId]);
                            $oldStk = $stmtStk->fetchColumn();

                            $diff = $current_stock - $oldStk;
                            if ($diff != 0) {
                                $stmtUpStk = $db->prepare("UPDATE products SET current_stock = ? WHERE id = ?");
                                $stmtUpStk->execute([$current_stock, $existingId]);

                                $movType = $diff > 0 ? 'in' : 'out';
                                $qty = abs($diff);
                                $stmtMov = $db->prepare("INSERT INTO movements (tenant_id, product_id, user_id, type, quantity, reason) VALUES (?, ?, ?, ?, ?, 'Importação de Planilha')");
                                $stmtMov->execute([$tenant_id, $existingId, $user['id'], $movType, $qty]);
                            }
                        }
                        $updatedCount++;
                    } else {
                        // Insert new product
                        if ($category_id !== null) {
                            $stmtIn = $db->prepare("INSERT INTO products (tenant_id, code, name, price, min_stock, current_stock, category_id, unit, location, observation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmtIn->execute([$tenant_id, $code, $name, $price, $min_stock, $current_stock, $category_id, $unit, $location, $observation]);
                        } else {
                            $stmtIn = $db->prepare("INSERT INTO products (tenant_id, code, name, price, min_stock, current_stock, unit, location, observation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmtIn->execute([$tenant_id, $code, $name, $price, $min_stock, $current_stock, $unit, $location, $observation]);
                        }
                        $newId = $db->lastInsertId();

                        if ($current_stock > 0) {
                            $stmtMov = $db->prepare("INSERT INTO movements (tenant_id, product_id, user_id, type, quantity, reason) VALUES (?, ?, ?, 'in', ?, 'Faro inicial (Importação)')");
                            $stmtMov->execute([$tenant_id, $newId, $user['id'], $current_stock]);
                        }
                        $importedCount++;
                    }
                }
                $db->commit();
                fclose($handle);
                echo json_encode(['success' => true, 'imported' => $importedCount, 'updated' => $updatedCount, 'skipped' => $skippedCount]);
            } catch (Exception $e) {
                $db->rollBack();
                fclose($handle);
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao importar dados: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Não foi possível ler o arquivo.']);
        }
    }
} elseif ($method == 'PUT') {
     $data = json_decode(file_get_contents('php://input'), true);
     if($action == 'update') {
         $id = $data['id'];
         $stmt = $db->prepare("UPDATE products SET code=?, name=?, category_id=?, supplier_id=?, price=?, min_stock=?, unit=?, location=?, observation=? WHERE id=? AND tenant_id=?");
         $stmt->execute([
            $data['code'] ?? null,
            $data['name'],
            $data['category_id'] ?? null,
            $data['supplier_id'] ?? null,
            $data['price'] ?? 0,
            $data['min_stock'] ?? 0,
            $data['unit'] ?? null,
            $data['location'] ?? null,
            $data['observation'] ?? null,
            $id,
            $tenant_id
         ]);
         echo json_encode(['success' => true]);
     }
} elseif ($method == 'DELETE') {
     $id = $_GET['id'] ?? null;
     $action = $_GET['action'] ?? '';

     if ($action == 'delete_all') {
         // Clear all movements first due to FK constraints or logical cleanup
         $stmt = $db->prepare("DELETE FROM movements WHERE tenant_id=?");
         $stmt->execute([$tenant_id]);

         $stmt = $db->prepare("DELETE FROM products WHERE tenant_id=?");
         $stmt->execute([$tenant_id]);
         echo json_encode(['success' => true]);
     } elseif($id) {
         // Also delete associated movements
         $stmt = $db->prepare("DELETE FROM movements WHERE product_id=? AND tenant_id=?");
         $stmt->execute([$id, $tenant_id]);

         $stmt = $db->prepare("DELETE FROM products WHERE id=? AND tenant_id=?");
         $stmt->execute([$id, $tenant_id]);
         echo json_encode(['success' => true]);
     }
}
