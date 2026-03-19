<?php
// Acesso: /app/buscador-ml/api.php
session_start();
require_once 'config.php';

// Verifica se o usuário está logado e o token CSRF confere para buscas (opcional, mas bom)
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

    if (empty(SCRAPER_API_KEY) || SCRAPER_API_KEY === 'SUA_CHAVE_AQUI') {
        http_response_code(500);
        echo json_encode(['error' => 'Você precisa configurar o SCRAPER_API_KEY no arquivo config.php. Crie uma conta gratuita em ScraperAPI.com para obter sua chave. Isso impede que o Mercado Livre bloqueie seu servidor.']);
        exit;
    }

    // Mapeamento de Ordenação
    $sortSuffix = '';
    if ($sort === 'price_asc') {
        $sortSuffix = '_OrderId_PRICE_ASC';
    } elseif ($sort === 'price_desc') {
        $sortSuffix = '_OrderId_PRICE_DESC';
    }

    $ml_url = "https://lista.mercadolivre.com.br/{$query}{$sortSuffix}";

    // ScraperAPI Render: Renderiza o Javascript (React) antes de retornar o HTML
    $api_url = "http://api.scraperapi.com?api_key=" . SCRAPER_API_KEY . "&render=true&url=" . urlencode($ml_url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // Timeout longo porque o ScraperAPI tenta várias vezes em IPs diferentes + Render JS
    curl_setopt($ch, CURLOPT_TIMEOUT, 90);

    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        http_response_code(500);
        echo json_encode(['error' => "Falha de conexão com o Proxy de Scraping: $error_msg"]);
        exit;
    }

    curl_close($ch);

    if ($http_code !== 200 || empty($html)) {
        // Se a chave for inválida (401/403 do ScraperAPI)
        if ($http_code === 401 || $http_code === 403) {
            http_response_code($http_code);
            echo json_encode(['error' => "Chave do ScraperAPI inválida ou cota mensal esgotada."]);
            exit;
        }

        http_response_code(500);
        echo json_encode(['error' => "O ScraperAPI não conseguiu acessar o Mercado Livre (HTTP {$http_code})."]);
        exit;
    }

    // Suprimir warnings do HTML mal formatado
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new DOMXPath($dom);

    $results = [];

    // O Mercado Livre usa a classe 'ui-search-result__wrapper' para cada card de produto
    // 'ui-search-layout__item' ou 'andes-card' também aparecem dependendo de A/B tests do React deles
    $items = $xpath->query("//div[contains(@class, 'ui-search-result__wrapper')] | //li[contains(@class, 'ui-search-layout__item')] | //div[contains(@class, 'andes-card')]");

    foreach ($items as $item) {
        if (count($results) >= 20) break; // Limitar a 20 resultados

        // 1. Título
        $titleNode = $xpath->query(".//*[contains(@class, 'ui-search-item__title')]", $item);
        $title = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : '';
        if (empty($title)) {
            $titleNode = $xpath->query(".//a[contains(@class, 'ui-search-item__group__element')]", $item);
            $title = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : '';
        }
        if (empty($title)) {
             $titleNode = $xpath->query(".//h2", $item);
             $title = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : '';
        }

        // 2. Link
        $linkNode = $xpath->query(".//a[contains(@class, 'ui-search-link')]", $item);
        $permalink = $linkNode->length > 0 ? $linkNode->item(0)->getAttribute('href') : '';
        if (empty($permalink)) {
             $linkNode = $xpath->query(".//a", $item);
             $permalink = $linkNode->length > 0 ? $linkNode->item(0)->getAttribute('href') : '';
        }

        // Limpar o link
        $permalink = explode('#', $permalink)[0];
        $permalink = explode('?', $permalink)[0];

        // 3. Preço
        $priceFractionNode = $xpath->query(".//*[contains(@class, 'andes-money-amount__fraction')]", $item);
        $priceStr = $priceFractionNode->length > 0 ? $priceFractionNode->item(0)->textContent : '0';
        $price = (float) str_replace(['.', ','], ['', '.'], $priceStr);

        // 4. Imagem
        $imgNodes = $xpath->query(".//img", $item);
        $image = '';
        foreach($imgNodes as $img) {
            $src = $img->getAttribute('data-src') ?: $img->getAttribute('src');
            $class = $img->getAttribute('class');

            // Pega a primeira imagem de produto real (frequentemente .ui-search-result-image__element)
            if (!empty($src) && strpos($src, 'data:image') === false && strpos($class, 'logo') === false) {
                // Tentar pegar a versão O (Original) em vez de I (Thumbnail) se for da mlstatic
                $image = str_replace('-I.jpg', '-O.jpg', $src);
                break;
            }
        }

        // 5. Frete Grátis (Badge)
        $shippingNode = $xpath->query(".//*[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛÃÕÄËÏÖÜÇÑ', 'abcdefghijklmnopqrstuvwxyzáéíóúàèìòùâêîôûãõäëïöüçñ'), 'frete grátis')]", $item);
        $free_shipping = $shippingNode->length > 0;

        // 6. ID do Produto (Extraído do Link)
        $id = '';
        if (preg_match('/MLB-?(\d+)/', $permalink, $matches)) {
            $id = 'MLB' . $matches[1];
        } else {
            $id = uniqid('mlb_');
        }

        if (!empty($title) && !empty($price) && !empty($permalink) && strpos($permalink, 'mercadolivre.com.br') !== false) {
            $results[] = [
                'id' => $id,
                'title' => $title,
                'price' => $price,
                'currency' => 'BRL',
                'permalink' => $permalink,
                'image' => $image,
                'is_catalog' => false,
                'condition' => 'new',
                'sold_quantity' => 0,
                'free_shipping' => $free_shipping
            ];
        }
    }

    libxml_clear_errors();

    if (empty($results)) {
         $title_tag = $dom->getElementsByTagName('title');
         $page_title = $title_tag->length > 0 ? trim($title_tag->item(0)->textContent) : 'Sem Título no HTML';

         $debug_msg = "O Scraper retornou a página, mas não encontrou as classes dos produtos. O Mercado Livre pode ter entregue um Captcha ou um A/B test com novo layout. Título da página retornada pelo ScraperAPI: <b>{$page_title}</b>";

         echo json_encode([
            'success' => true,
            'total' => 0,
            'results' => [],
            'debug' => $debug_msg
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'total' => count($results),
        'results' => $results
    ]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Ação não especificada ou inválida.']);
?>