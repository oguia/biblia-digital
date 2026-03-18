<?php
// Acesso: /app/buscador-ml/api.php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado.']);
    exit;
}

header('Content-Type: application/json');

// Ação: Buscar no Mercado Livre
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    $query = urlencode($_GET['q'] ?? '');
    $sort = $_GET['sort'] ?? 'relevance';

    // Parâmetros de ordenação suportados pela API: price_asc, price_desc, relevance

    if (empty($query)) {
        http_response_code(400);
        echo json_encode(['error' => 'Termo de busca vazio.']);
        exit;
    }

    // Endpoint público da API do Mercado Livre para busca (Brasil = MLB)
    // Limitando a 20 resultados por página para performance
    $url = "https://api.mercadolibre.com/sites/MLB/search?q={$query}&limit=20&sort={$sort}";

    // Inicializa cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Hostinger as vezes exige isso
    // Add header if using ML keys later, but public search works without it
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . ML_ACCESS_TOKEN]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        http_response_code(500);
        echo json_encode(['error' => "Erro na requisição cURL: $error_msg"]);
        exit;
    }

    curl_close($ch);

    if ($http_code !== 200) {
        http_response_code($http_code);
        echo json_encode(['error' => "Erro na API do Mercado Livre (HTTP $http_code).", 'details' => json_decode($response)]);
        exit;
    }

    $data = json_decode($response, true);
    $results = $data['results'] ?? [];

    // Processamento e limpeza dos dados
    $formatted_results = [];
    foreach ($results as $item) {
        // Obter uma imagem de melhor qualidade se possível (a default é thumbnail pequena)
        // Thumbnail: http://http2.mlstatic.com/D_878347-MLB72791783060_112023-I.jpg
        // Imagem Maior: http://http2.mlstatic.com/D_878347-MLB72791783060_112023-O.jpg
        $image_url = $item['thumbnail'];
        $image_high_res = str_replace('-I.jpg', '-O.jpg', $image_url);

        // Verifica se é um produto de catálogo (melhor qualidade, nota alta garantida)
        $is_catalog = $item['catalog_product_id'] ? true : false;

        $formatted_results[] = [
            'id' => $item['id'],
            'title' => $item['title'],
            'price' => $item['price'],
            'currency' => $item['currency_id'],
            'permalink' => $item['permalink'],
            'image' => str_replace('http://', 'https://', $image_high_res), // Forçar HTTPS
            'is_catalog' => $is_catalog,
            'condition' => $item['condition'],
            'sold_quantity' => $item['sold_quantity'] ?? 0, // Alguns itens mais novos não trazem isso solto
            'seller_id' => $item['seller']['id'] ?? '',
            'free_shipping' => $item['shipping']['free_shipping'] ?? false
        ];
    }

    echo json_encode([
        'success' => true,
        'total' => $data['paging']['total'] ?? 0,
        'results' => $formatted_results
    ]);
    exit;
}

// Ação inválida
http_response_code(400);
echo json_encode(['error' => 'Ação não especificada ou inválida.']);
?>