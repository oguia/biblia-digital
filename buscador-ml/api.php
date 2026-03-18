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

if (isset($_GET['action']) && $_GET['action'] === 'search') {
    $query = urlencode($_GET['q'] ?? '');
    $sort = $_GET['sort'] ?? 'relevance';

    if (empty($query)) {
        http_response_code(400);
        echo json_encode(['error' => 'Termo de busca vazio.']);
        exit;
    }

    $token_file = 'ml_tokens.php';

    // Verifica se temos o token armazenado
    if (!file_exists($token_file)) {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso negado pelo Mercado Livre (403 Forbidden). Você precisa autorizar seu aplicativo primeiro clicando no botão abaixo.', 'auth_required' => true]);
        exit;
    }

    // Ler o token seguro ignorando a primeira linha (o <?php die...)
    $file_content = file_get_contents($token_file);
    $json_content = preg_replace('/^<\?php.*?\?>\n/s', '', $file_content);
    $tokens = json_decode($json_content, true);
    $access_token = $tokens['access_token'];

    // Renovar Token se estiver expirado
    if (time() >= $tokens['expires_at']) {
        require_once 'config.php';

        $post_data = http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => ML_APP_ID,
            'client_secret' => ML_SECRET_KEY,
            'refresh_token' => $tokens['refresh_token']
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/oauth/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($http_code === 200 && isset($data['access_token'])) {
            $tokens = [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'expires_at' => time() + $data['expires_in'] - 60
            ];
            $secure_content = "<?php die('Acesso negado'); ?>\n" . json_encode($tokens);
            file_put_contents($token_file, $secure_content);
            $access_token = $data['access_token'];
        } else {
            // Refresh falhou, forçar re-autorização manual
            unlink($token_file);
            http_response_code(403);
            echo json_encode(['error' => 'A autorização do Mercado Livre expirou. Clique no botão abaixo para autorizar novamente.', 'auth_required' => true]);
            exit;
        }
    }

    // A API oficial do ML agora permite busca com Autorização sem erro 403.
    // Parâmetros de ordenação suportados pela API: price_asc, price_desc, relevance
    $url = "https://api.mercadolibre.com/sites/MLB/search?q={$query}&limit=20&sort={$sort}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Se o token foi revogado pelo usuário no painel do ML (401)
    if ($http_code === 401 || $http_code === 403) {
        unlink($token_file);
        http_response_code(403);
        echo json_encode([
            'error' => "Erro de Autenticação com o Mercado Livre ($http_code). O token pode ter sido revogado ou as chaves no config.php estão incorretas. Autorize novamente.",
            'auth_required' => true
        ]);
        exit;
    }

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
            'sold_quantity' => $item['sold_quantity'] ?? 0,
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

http_response_code(400);
echo json_encode(['error' => 'Ação não especificada ou inválida.']);
?>