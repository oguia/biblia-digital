<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();

    // 1. Truncate
    $db->exec("SET FOREIGN_KEY_CHECKS=0");
    $db->exec("TRUNCATE TABLE companies");
    $db->exec("TRUNCATE TABLE categories");
    $db->exec("TRUNCATE TABLE neighborhoods");
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    // 2. Load the SQL I generated (with real names)
    // I will embed the exact SQL logic here so it doesn't rely on files

    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        return $text ?: 'n-a';
    }

    $categories = [
        'Restaurante' => ['Madalosso', 'Barolo Trattoria', 'Restaurante Família Fadanelli', 'Terra Madre', 'Poco Tapas', 'Churrascaria Batel Grill', 'Jardins Grill', 'Restaurante Caliceti', 'Cantina do Délio', 'Restaurante Petit Château'],
        'Pizzaria' => ['Pizzaria Baggio', 'Abaré Pizzas', 'Mercearia Bresser', 'Pizzaria Carolla', 'Domino\'s Pizza', 'Pizza Hut', 'Avenida Paulista Pizza Bar', 'Délio', 'Quintana', 'Pizzaria Curitiba'],
        'Farmácia' => ['Farmácias Nissei', 'Raia Drogasil', 'Panvel Farmácias', 'Farmácia Morifarma', 'Droga Raia', 'Farmácia Preço Popular', 'CallFarma', 'Farmácias Vale Verde'],
        'Supermercado' => ['Supermercado Condor', 'Supermercado Angeloni', 'Festval', 'Supermercado Muffato', 'Mercadorama', 'Carrefour', 'Assaí Atacadista', 'Casa Fiesta'],
        'Academia' => ['Smart Fit', 'Bluefit', 'Academia Gustavo Borges', 'Companhia Athletica', 'Bodytech', 'Academia Hype', 'Academia Corpus', 'Crossfit Barigui'],
        'Oficina Mecânica' => ['Auto Center Curitiba', 'Mecânica do Beto', 'Oficina High Torque', 'Centro Automotivo Porto Seguro', 'Bosch Car Service', 'Mecânica Motor Tech'],
        'Escola' => ['Colégio Positivo', 'Colégio Marista Paranaense', 'Colégio Bom Jesus', 'Escola Everest', 'Colégio Medianeira', 'Colégio Sion', 'Escola Internacional'],
        'Hotel' => ['Hotel Bourbon', 'Radisson Hotel', 'Grand Mercure Rayon', 'Pestana Curitiba', 'Ibis Curitiba', 'Hotel NH The Five', 'Nomaa Hotel'],
        'Clínica Médica' => ['Hospital Nossa Senhora das Graças', 'Hospital Marcelino Champagnat', 'Clínica Sugisawa', 'Hospital Vita', 'IPO - Instituto Paranaense de Otorrinolaringologia'],
        'Salão de Beleza' => ['Torriton Beauty & Hair', 'Lady&Lord', 'Expert Beauty Center', 'Salão Marly', 'Vimax Art Hair Beauty', 'Keune Concept'],
        'Loja de Roupas' => ['Lojas Renner', 'Riachuelo', 'C&A', 'Zara', 'Amaro', 'Lojas Americanas', 'Havan'],
        'Construtora' => ['Construtora Plaenge', 'A.Yoshii Engenharia', 'Construtora Laguna', 'Vanguard Home', 'Thá Engenharia'],
        'Escritório de Advocacia' => ['Escritório Professor René Dotti', 'Pereira Gionédis Advogados', 'Hapner Kroetz Advogados'],
        'Padaria' => ['Padaria América', 'Requinte', 'Saint Germain', 'Padaria Spazio di Pane', 'Padaria Guarani']
    ];

    $neighborhoods = [
        'Batel' => ['Av. do Batel', 'Rua Bispo Dom José', 'Al. Dom Pedro II'],
        'Água Verde' => ['Av. Iguaçu', 'Av. Presidente Getúlio Vargas', 'Rua Republica Argentina'],
        'Centro' => ['Rua XV de Novembro', 'Av. Marechal Deodoro', 'Rua Marechal Floriano Peixoto'],
        'Santa Felicidade' => ['Av. Manoel Ribas', 'Rua Via Veneto'],
        'Bigorrilho' => ['Rua Padre Anchieta', 'Al. Julia da Costa'],
        'Cabral' => ['Av. Paraná', 'Rua Munhoz da Rocha'],
        'Juvevê' => ['Av. João Gualberto', 'Rua Rocha Pombo'],
        'Portão' => ['Av. República Argentina', 'Rua João Bettega'],
        'Rebouças' => ['Av. Marechal Floriano Peixoto', 'Rua Engenheiros Rebouças'],
        'Mercês' => ['Av. Manoel Ribas', 'Rua Desembargador Motta']
    ];

    // Insert Categories
    $stmtCat = $db->prepare("INSERT INTO categories (id, name, slug) VALUES (?, ?, ?)");
    $catId = 1;
    $catMap = [];
    foreach ($categories as $catName => $examples) {
        $slug = slugify($catName);
        $stmtCat->execute([$catId, $catName, $slug]);
        $catMap[$catName] = $catId;
        $catId++;
    }

    // Insert Neighborhoods
    $stmtNeigh = $db->prepare("INSERT INTO neighborhoods (id, name, slug, city) VALUES (?, ?, ?, 'Curitiba')");
    $neighId = 1;
    $neighMap = [];
    foreach ($neighborhoods as $neighName => $streets) {
        $slug = slugify($neighName);
        $stmtNeigh->execute([$neighId, $neighName, $slug]);
        $neighMap[$neighName] = ['id' => $neighId, 'streets' => $streets];
        $neighId++;
    }

    // Insert Companies
    $stmtComp = $db->prepare("INSERT INTO companies (category_id, neighborhood_id, name, slug, description, address, number, zip_code, phone, whatsapp, latitude, longitude, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '/img_exemplo.png', 'active')");

    $count = 0;
    foreach ($categories as $catName => $names) {
        foreach ($names as $realName) {
            $catId = $catMap[$catName];

            $neighName = array_rand($neighborhoods);
            $neighInfo = $neighMap[$neighName];
            $neighId = $neighInfo['id'];

            $street = $neighInfo['streets'][array_rand($neighInfo['streets'])];
            $number = rand(10, 3000);
            $zip = '80' . rand(100, 999) . '-' . rand(100, 999);

            $slug = slugify($realName . '-' . $neighName);
            $desc = "Uma excelente opção de $catName localizada no coração de $neighName. Oferecemos o melhor serviço da região com qualidade e confiança.";

            $lat = -25.42 + (rand(-50, 50) / 1000);
            $lng = -49.27 + (rand(-50, 50) / 1000);

            $phone = '(41) 3' . rand(100, 999) . '-' . rand(1000, 9999);
            $whatsapp = '55419' . rand(10000000, 99999999);

            $stmtComp->execute([$catId, $neighId, $realName, $slug, $desc, $street, $number, $zip, $phone, $whatsapp, $lat, $lng]);
            $count++;
        }
    }

    echo "<h1>Sucesso!</h1>";
    echo "<p>$count empresas reais (Madalosso, etc) foram inseridas no banco de dados.</p>";
    echo "<p>Pode acessar a home e testar a busca!</p>";

    // Self destruct for security
    unlink(__FILE__);

} catch (Exception $e) {
    echo "<h1>Erro</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
