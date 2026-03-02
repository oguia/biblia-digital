<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();

    // 1. Truncate tables securely
    $db->exec("SET FOREIGN_KEY_CHECKS=0");
    $db->exec("TRUNCATE TABLE companies");
    $db->exec("TRUNCATE TABLE categories");
    $db->exec("TRUNCATE TABLE neighborhoods");
    $db->exec("TRUNCATE TABLE reviews");
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        return $text ?: 'n-a';
    }

    // Embed the JSON content directly to bypass HTTP 404
    $json_data = file_get_contents(__DIR__ . '/raw_geo.json');
    if (!$json_data) {
        throw new Exception("Não foi possível carregar o arquivo raw_geo.json.");
    }

    $empresas = json_decode($json_data, true);
    if (!$empresas) {
        throw new Exception("Erro ao decodificar o JSON das empresas.");
    }

    // Prepare dictionaries for Category and Neighborhood caching
    $catMap = [];
    $neighMap = [];

    // Prepared statements for Category and Neighborhoods
    $stmtCat = $db->prepare("INSERT IGNORE INTO categories (name, slug) VALUES (?, ?)");
    $stmtNeigh = $db->prepare("INSERT IGNORE INTO neighborhoods (name, slug, city) VALUES (?, ?, ?)");

    $stmtGetCat = $db->prepare("SELECT id FROM categories WHERE name = ? LIMIT 1");
    $stmtGetNeigh = $db->prepare("SELECT id FROM neighborhoods WHERE name = ? LIMIT 1");

    // Prepared statement for Company
    $stmtComp = $db->prepare("
        INSERT INTO companies (
            category_id, neighborhood_id, name, slug, description, address, number,
            zip_code, phone, whatsapp, latitude, longitude, image_url, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
    ");

    $count = 0;

    foreach ($empresas as $empresa) {
        // --- 1. Handle Category ---
        $catName = trim($empresa['categoria']);
        if (empty($catName)) $catName = 'Geral';

        if (!isset($catMap[$catName])) {
            $catSlug = slugify($catName);
            $stmtCat->execute([$catName, $catSlug]);

            $stmtGetCat->execute([$catName]);
            $catId = $stmtGetCat->fetchColumn();
            $catMap[$catName] = $catId;
        } else {
            $catId = $catMap[$catName];
        }

        // --- 2. Handle Neighborhood ---
        // Since neighborhoods.name is UNIQUE in the DB schema,
        // we map strictly by neighborhood name, ignoring the city difference
        // if two neighborhoods have the same name (e.g. Centro).
        $neighName = trim($empresa['endereco']['bairro']);
        $cityName = trim($empresa['endereco']['cidade']);
        if (empty($neighName)) $neighName = 'Centro';
        if (empty($cityName)) $cityName = 'Curitiba';

        if (!isset($neighMap[$neighName])) {
            $neighSlug = slugify($neighName); // generate slug based only on name to avoid unique conflicts
            $stmtNeigh->execute([$neighName, $neighSlug, $cityName]);

            $stmtGetNeigh->execute([$neighName]);
            $neighId = $stmtGetNeigh->fetchColumn();
            $neighMap[$neighName] = $neighId;
        } else {
            $neighId = $neighMap[$neighName];
        }

        // --- 3. Handle Company Data ---
        $realName = trim($empresa['nome']);
        // Append unique ID to slug to avoid duplicates on company names
        $slug = slugify($realName . '-' . $empresa['id']);

        $street = trim($empresa['endereco']['rua']);
        $number = trim($empresa['endereco']['numero']);
        $zip = trim($empresa['endereco']['cep']);

        $phone = trim($empresa['telefone']);
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $whatsapp = "55" . $cleanPhone;

        $lat = (float) $empresa['latitude'];
        $lng = (float) $empresa['longitude'];

        $desc = "A {$realName} é uma excelente opção em {$catName} localizada na região de {$neighName}, {$cityName}.";

        // --- IMAGE HANDLING ---
        $imageUrl = '/img_exemplo.png';

        // --- Execute Insert ---
        // Ensure valid IDs to prevent Foreign Key 1452 error
        if (!$catId) {
            $catId = null;
        }
        if (!$neighId) {
            // Hard fallback: insert without neighborhood if it completely failed
            $neighId = null;
        }

        $stmtComp->execute([
            $catId, $neighId, $realName, $slug, $desc, $street, $number,
            $zip, $phone, $whatsapp, $lat, $lng, $imageUrl
        ]);

        // Fake Reviews
        $companyId = $db->lastInsertId();
        $totalReviews = rand(1, 15);
        $stmtReview = $db->prepare("INSERT INTO reviews (company_id, name, rating, comment) VALUES (?, 'Cliente', ?, 'Ótimo lugar, recomendo!')");

        for ($i=0; $i < $totalReviews; $i++) {
            $rating = rand(3, 5);
            $stmtReview->execute([$companyId, $rating]);
        }

        $count++;
    }

    echo "<h1>Sucesso Absoluto!</h1>";
    echo "<p>Foi feita a injeção de <strong>$count</strong> empresas usando os dados exatos do seu arquivo JSON.</p>";
    echo "<p>Todas as empresas receberam a imagem de exemplo: <code>img_exemplo.png</code> e avaliações simuladas.</p>";
    echo "<br><a href='/'>Voltar para a Home</a>";

} catch (Exception $e) {
    echo "<h1>Erro</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
