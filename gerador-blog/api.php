<?php
// Acesso: /app/gerador-blog/api.php
session_start();
require_once 'config.php';

// Proteção da Sessão Principal
if (!isset($_SESSION['logged_in_blog']) || $_SESSION['logged_in_blog'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Acesso Negado (Login).']);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Função Helper para Chamadas API do WooCommerce
function call_woo_api($endpoint, $method = 'GET', $data = []) {
    $url = rtrim(WP_URL, '/') . '/wp-json/wc/v3/' . ltrim($endpoint, '/');
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_USERPWD, WC_CONSUMER_KEY . ":" . WC_CONSUMER_SECRET);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Loopback na Hostinger exige desligar SSL Verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $headers = ['Accept: application/json'];

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['error' => curl_error($ch), 'code' => 500];
    }

    curl_close($ch);
    return ['data' => json_decode($response, true), 'code' => $http_code];
}

// Função Helper para Chamadas API do WordPress (Posts)
function call_wp_api($endpoint, $method = 'POST', $data = []) {
    $url = rtrim(WP_URL, '/') . '/wp-json/wp/v2/' . ltrim($endpoint, '/');
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_USERPWD, WP_ADMIN_USERNAME . ":" . WP_APP_PASSWORD);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Loopback
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $headers = ['Accept: application/json'];

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['error' => curl_error($ch), 'code' => 500];
    }

    curl_close($ch);
    return ['data' => json_decode($response, true), 'code' => $http_code];
}

// ---------------------------------------------------------
// ROUTER DE AÇÕES
// ---------------------------------------------------------

if ($action === 'get_products') {
    // Busca os 50 produtos mais recentes da loja
    $res = call_woo_api('products?per_page=50&orderby=date&order=desc');

    if ($res['code'] !== 200) {
        http_response_code($res['code'] === 401 ? 403 : $res['code']);
        echo json_encode(['error' => 'Falha ao ler produtos. As chaves do WooCommerce no config.php estão corretas?']);
        exit;
    }

    $products = [];
    foreach ($res['data'] as $p) {
        $img = !empty($p['images']) ? $p['images'][0]['src'] : '';
        $products[] = [
            'id' => $p['id'],
            'title' => $p['name'],
            'price' => $p['price'] ?: '0',
            'permalink' => $p['permalink'],
            'image' => $img,
            'description' => strip_tags($p['description'] ?? '')
        ];
    }

    echo json_encode(['success' => true, 'results' => $products]);
    exit;
}

// ---------------------------------------------------------
// As ações abaixo exigem POST e validação CSRF
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token_blog']) || !hash_equals($_SESSION['csrf_token_blog'], $_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Sessão expirada ou token de segurança inválido. Recarregue a página.']);
        exit;
    }

    if ($action === 'generate_post') {
        $title = $_POST['product_title'] ?? '';
        $price = $_POST['product_price'] ?? '0.00';
        $link = $_POST['product_permalink'] ?? '';

        if (empty($title) || empty(GEMINI_API_KEY) || GEMINI_API_KEY === 'SUA_CHAVE_DO_GOOGLE_AQUI') {
            http_response_code(400);
            echo json_encode(['error' => 'Chave do Google Gemini não configurada no config.php ou Produto Inválido.']);
            exit;
        }

        // Prompt Persuasivo para o Gemini
        $prompt = "Aja como um copywriter profissional e dono de um blog focado em análises de produtos (Reviews/Reviews de Ofertas). ";
        $prompt .= "Crie um artigo atraente, persuasivo e detalhado (cerca de 300 palavras) recomendando o produto '{$title}'. ";
        $prompt .= "O preço atual na loja é em torno de R$ {$price}. ";
        $prompt .= "Instruções rigorosas de formatação:\n";
        $prompt .= "- O título do artigo deve ser criativo e gerar curiosidade (NÃO use tags HTML no título, coloque na primeira linha do texto).\n";
        $prompt .= "- O corpo do texto deve usar estritamente tags HTML para formatação (<h2> para subtítulos, <p> para parágrafos, <ul> e <li> para lista de benefícios, <strong> para negritos).\n";
        $prompt .= "- O artigo deve soar natural, explicando o problema que o produto resolve e por que ele é uma ótima compra.\n";
        $prompt .= "- No final do artigo, crie um parágrafo de CTA (Chamada para Ação) finalizando com um botão ou link forte usando o link oficial: {$link}\n";
        $prompt .= "- NÃO inclua marcações Markdown como ```html ao redor da sua resposta. Apenas retorne o HTML e o Título na primeira linha.";

        $data = [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ]
        ];

        // Sanitize the API key just in case there are trailing spaces or newlines in the config.php
        $gemini_key = trim(GEMINI_API_KEY);
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $gemini_key;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Hostinger curl loopback/SSL issues workaround
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            http_response_code(500);

            // Extract more specific error message from Google if available
            $err_details = json_decode($response, true);
            $msg = $err_details['error']['message'] ?? "Verifique se a sua chave da API é válida.";

            echo json_encode([
                'error' => "O Google Gemini falhou (HTTP {$http_code}). Detalhe: {$msg}",
                'debug_url_start' => substr($url, 0, 80) . '...' // Help diagnose if URL is mangled without leaking full key
            ]);
            exit;
        }

        $gemini_data = json_decode($response, true);
        $generated_text = $gemini_data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if (empty($generated_text)) {
             echo json_encode(['error' => 'A IA não retornou nenhum texto útil.']);
             exit;
        }

        // Separar o Título Gerado do Conteúdo (Assumindo que a IA colocou o título na 1a linha)
        $lines = explode("\n", trim($generated_text));
        $generated_title = trim($lines[0]);
        // Remove markdown headers if present in the first line
        $generated_title = preg_replace('/^#+\s*/', '', $generated_title);
        $generated_title = str_replace(['<h1>', '</h1>', '<h2>', '</h2>'], '', $generated_title);

        array_shift($lines); // Remove a primeira linha
        $generated_content = trim(implode("\n", $lines));

        echo json_encode([
            'success' => true,
            'title' => $generated_title,
            'content' => $generated_content
        ]);
        exit;
    }

    if ($action === 'publish_post') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if (empty($title) || empty($content)) {
            http_response_code(400);
            echo json_encode(['error' => 'Título e Conteúdo são obrigatórios.']);
            exit;
        }

        if (empty(WP_ADMIN_USERNAME) || empty(WP_APP_PASSWORD) || strpos(WP_APP_PASSWORD, 'xxxx') !== false) {
            http_response_code(500);
            echo json_encode(['error' => 'A "Senha de Aplicativo" do WordPress não foi configurada no config.php. É necessária para criar o post.']);
            exit;
        }

        $payload = [
            'title'   => $title,
            'content' => $content,
            'status'  => 'draft', // Cria como rascunho
            'format'  => 'standard'
        ];

        // Idealmente enviaríamos a "featured_media" (ID da Imagem Destacada), mas o REST API do WP
        // requer que a imagem seja feito upload fisicamente na galeria de mídia antes de associar o ID ao post.
        // O Gemini já insere a imagem dentro do conteúdo <img>, o que resolve o visual.

        $res = call_wp_api('posts', 'POST', $payload);

        if ($res['code'] === 201) {
            echo json_encode([
                'success' => true,
                'wp_id' => $res['data']['id'],
                'link' => $res['data']['link'] ?? ''
            ]);
        } else {
            http_response_code($res['code'] === 401 ? 403 : 500);
            echo json_encode([
                'error' => 'O WordPress rejeitou a criação do post (HTTP ' . $res['code'] . '). Verifique seu usuário e Senha de Aplicativo no config.php.',
                'details' => $res['data']
            ]);
        }
        exit;
    }
}

http_response_code(400);
echo json_encode(['error' => 'Ação inválida.']);
?>