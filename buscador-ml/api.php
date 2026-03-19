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

    // A API oficial do Mercado Livre bloqueia novas contas de desenvolvedor com Erro 403.
    // Para manter a ferramenta funcional e gratuita sem aprovação de negócios,
    // faremos um Web Scraping da página Mobile do Mercado Livre (que é mais limpa e difícil de bloquear).

    // Mapeamento de Ordenação
    $sortSuffix = '';
    if ($sort === 'price_asc') {
        $sortSuffix = '_OrderId_PRICE_ASC';
    } elseif ($sort === 'price_desc') {
        $sortSuffix = '_OrderId_PRICE_DESC';
    }

    $url = "https://lista.mercadolivre.com.br/{$query}{$sortSuffix}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // Simulando ser um iPhone para forçar a versão mobile leve do site
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cache-Control: max-age=0'
    ]);

    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200 || empty($html)) {
        http_response_code($http_code ?: 500);
        echo json_encode(['error' => "Falha ao se conectar ao Mercado Livre (HTTP {$http_code}). Pode ser um bloqueio temporário do servidor."]);
        exit;
    }

    // Suprimir warnings do HTML mal formatado
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new DOMXPath($dom);

    $results = [];

    // O Mercado Livre Mobile usa a classe 'ui-search-result__wrapper'
    $items = $xpath->query("//div[contains(@class, 'ui-search-result__wrapper')]");

    foreach ($items as $item) {
        if (count($results) >= 20) break; // Limitar a 20 resultados

        // 1. Título
        $titleNode = $xpath->query(".//*[contains(@class, 'ui-search-item__title')]", $item);
        $title = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : '';

        // 2. Link
        $linkNode = $xpath->query(".//a[contains(@class, 'ui-search-link')]", $item);
        $permalink = $linkNode->length > 0 ? $linkNode->item(0)->getAttribute('href') : '';

        // Limpar o link (remover tracking ID do ML e hashtags)
        $permalink = explode('#', $permalink)[0];
        $permalink = explode('?', $permalink)[0];

        // 3. Preço
        $priceFractionNode = $xpath->query(".//span[contains(@class, 'andes-money-amount__fraction')]", $item);
        $priceStr = $priceFractionNode->length > 0 ? $priceFractionNode->item(0)->textContent : '0';
        $price = (float) str_replace(['.', ','], ['', '.'], $priceStr);

        // 4. Imagem
        $imgNodes = $xpath->query(".//img", $item);
        $image = '';
        foreach($imgNodes as $img) {
            $src = $img->getAttribute('data-src'); // Lazy loading
            if (empty($src) || strpos($src, 'data:image') !== false) {
                 $src = $img->getAttribute('src');
            }
            if (!empty($src) && strpos($src, 'data:image') === false) {
                // Tentar pegar a versão original em vez do thumbnail
                $image = str_replace('-I.jpg', '-O.jpg', $src);
                break;
            }
        }

        // 5. Frete Grátis (Badge)
        $shippingNode = $xpath->query(".//*[contains(text(), 'Frete grátis')]", $item);
        $free_shipping = $shippingNode->length > 0;

        // 6. ID do Produto (Extraído do Link)
        $id = '';
        if (preg_match('/MLB-?(\d+)/', $permalink, $matches)) {
            $id = 'MLB' . $matches[1];
        } else {
            $id = uniqid('mlb_');
        }

        if (!empty($title) && !empty($price) && !empty($permalink)) {
            $results[] = [
                'id' => $id,
                'title' => $title,
                'price' => $price,
                'currency' => 'BRL',
                'permalink' => $permalink,
                'image' => $image,
                'is_catalog' => false,
                'condition' => 'new', // Mobile scraper fallback
                'sold_quantity' => 0,
                'free_shipping' => $free_shipping
            ];
        }
    }

    libxml_clear_errors();

    if (empty($results)) {
         echo json_encode([
            'success' => true,
            'total' => 0,
            'results' => [],
            'debug' => "Nenhum resultado processado via scraping Mobile DOM. O layout do Mercado Livre pode ter mudado."
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