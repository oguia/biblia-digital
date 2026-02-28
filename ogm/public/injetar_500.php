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
    $stmtCat = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    $stmtNeigh = $db->prepare("INSERT INTO neighborhoods (name, slug, city) VALUES (?, ?, ?)");

    // Prepared statement for Company
    $stmtComp = $db->prepare("
        INSERT INTO companies (
            category_id, neighborhood_id, name, slug, description, address, number,
            zip_code, phone, whatsapp, latitude, longitude, image_url, status,
            rating, total_reviews
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?)
    ");

    $count = 0;

    foreach ($empresas as $empresa) {
        // --- 1. Handle Category ---
        $catName = trim($empresa['categoria']);
        if (!isset($catMap[$catName])) {
            $slug = slugify($catName);
            $stmtCat->execute([$catName, $slug]);
            $catMap[$catName] = $db->lastInsertId();
        }
        $catId = $catMap[$catName];

        // --- 2. Handle Neighborhood ---
        $neighName = trim($empresa['endereco']['bairro']);
        $cityName = trim($empresa['endereco']['cidade']);

        $neighKey = $neighName . '_' . $cityName;

        if (!isset($neighMap[$neighKey])) {
            $slug = slugify($neighName . '-' . $cityName);
            $stmtNeigh->execute([$neighName, $slug, $cityName]);
            $neighMap[$neighKey] = $db->lastInsertId();
        }
        $neighId = $neighMap[$neighKey];

        // --- 3. Handle Company Data ---
        $realName = trim($empresa['nome']);
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

        $rating = rand(35, 50) / 10;
        $totalReviews = rand(5, 120);

        // --- Execute Insert ---
        $stmtComp->execute([
            $catId, $neighId, $realName, $slug, $desc, $street, $number,
            $zip, $phone, $whatsapp, $lat, $lng, $imageUrl,
            $rating, $totalReviews
        ]);

        $count++;
    }

    echo "<h1>Sucesso!</h1>";
    echo "<p>Foi feita a injeção de <strong>$count</strong> empresas usando os dados exatos do seu arquivo JSON.</p>";
    echo "<p>Todas as empresas receberam a imagem de exemplo: <code>img_exemplo.png</code>.</p>";
    echo "<br><a href='/'>Voltar para a Home</a>";

    // Opcional: remover o arquivo para evitar que outra pessoa acesse e sobrescreva os dados
    // unlink(__FILE__);
    // unlink(__DIR__ . '/raw_geo.json');

} catch (Exception $e) {
    echo "<h1>Erro</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
