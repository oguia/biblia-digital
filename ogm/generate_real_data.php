<?php

// Realistic Data Generator for Curitiba
// Maps categories to realistic names and real neighborhoods

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

$sql = "SET FOREIGN_KEY_CHECKS=0;\n";
$sql .= "TRUNCATE TABLE companies;\n";
$sql .= "TRUNCATE TABLE categories;\n";
$sql .= "TRUNCATE TABLE neighborhoods;\n";
$sql .= "SET FOREIGN_KEY_CHECKS=1;\n\n";

// Generate Categories SQL
$catId = 1;
$catMap = [];
$sql .= "-- Categories\n";
foreach ($categories as $catName => $examples) {
    $slug = slugify($catName);
    $sql .= "INSERT INTO categories (id, name, slug) VALUES ($catId, '$catName', '$slug');\n";
    $catMap[$catName] = $catId;
    $catId++;
}
$sql .= "\n";

// Generate Neighborhoods SQL
$neighId = 1;
$neighMap = [];
$sql .= "-- Neighborhoods\n";
foreach ($neighborhoods as $neighName => $streets) {
    $slug = slugify($neighName);
    $sql .= "INSERT INTO neighborhoods (id, name, slug, city) VALUES ($neighId, '$neighName', '$slug', 'Curitiba');\n";
    $neighMap[$neighName] = [
        'id' => $neighId,
        'streets' => $streets
    ];
    $neighId++;
}
$sql .= "\n";

// Generate Companies SQL
$sql .= "-- Companies\n";
$companyCount = 0;

// Helper for random phone
function randomPhone() {
    return '(41) 3' . rand(100, 999) . '-' . rand(1000, 9999);
}

// Generate ~100 high quality realistic entries
foreach ($categories as $catName => $names) {
    foreach ($names as $realName) {
        $catId = $catMap[$catName];

        // Pick random neighborhood
        $neighName = array_rand($neighborhoods);
        $neighInfo = $neighMap[$neighName];
        $neighId = $neighInfo['id'];

        // Pick street
        $street = $neighInfo['streets'][array_rand($neighInfo['streets'])];
        $number = rand(10, 3000);
        $zip = '80' . rand(100, 999) . '-' . rand(100, 999);

        $slug = slugify($realName . '-' . $neighName); // Unique slug
        $desc = "Uma excelente opção de $catName localizada no coração de $neighName. Oferecemos o melhor serviço da região com qualidade e confiança.";

        // Fake coordinates roughly in Curitiba center
        $lat = -25.42 + (rand(-50, 50) / 1000);
        $lng = -49.27 + (rand(-50, 50) / 1000);

        $phone = randomPhone();
        $whatsapp = '55419' . rand(10000000, 99999999);

        $sql .= "INSERT INTO companies (category_id, neighborhood_id, name, slug, description, address, number, zip_code, phone, whatsapp, latitude, longitude, image_url, status) VALUES ";
        $sql .= "($catId, $neighId, '$realName', '$slug', '$desc', '$street', '$number', '$zip', '$phone', '$whatsapp', $lat, $lng, '/img_exemplo.png', 'active');\n";

        $companyCount++;
    }
}

// Fill up to 150 with variations if needed, but ~80 solid real names is better than 500 fakes
// We have about 14 cats * ~8 names = ~112 companies. That's a good start.

file_put_contents(__DIR__ . '/import_data.sql', $sql);
echo "Generated $companyCount companies in ogm/import_data.sql\n";

?>
