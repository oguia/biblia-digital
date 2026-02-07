<?php

function slugify($text) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, '-');
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

// Helper function to execute curl requests
function fetchUrl($url) {
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($html === FALSE || $httpCode != 200) {
        return false;
    }
    return $html;
}

function searchSongs($query) {
    // Use Google Search restricted to cifraclub.com.br
    $searchUrl = "https://www.google.com/search?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
    $html = fetchUrl($searchUrl);

    if (!$html) {
        return ['success' => false, 'message' => 'Erro ao realizar a busca. Tente novamente mais tarde.'];
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Google results are often in <a> tags.
    // The structure changes often, but the href attribute is constant.

    $results = [];
    $nodes = $xpath->query("//a");

    foreach ($nodes as $node) {
        $href = $node->getAttribute('href');

        // Handle Google redirect URL format (/url?q=...)
        if (strpos($href, '/url?q=') !== false) {
            $parts = parse_url($href);
            parse_str($parts['query'], $queryParts);
            $href = $queryParts['q'] ?? '';
        }

        // Filter for valid Cifra Club song URLs
        // Pattern: https://www.cifraclub.com.br/ARTIST/SONG/
        if (preg_match('#cifraclub\.com\.br/([^/]+)/([^/]+)/$#', $href, $matches)) {
            $artistSlug = $matches[1];
            $songSlug = $matches[2];

            // Skip non-song pages
            if (in_array($artistSlug, ['admin', 'app', 'letra', 'top', 'estilos', 'listas'])) continue;

            // Extract title from the link text or construct it
            // Google usually puts the title in <h3> inside the <a>, or just the text
            $titleText = $node->textContent;

            // Clean up title
            $titleText = str_ireplace([' - Cifra Club', 'Cifra Club - ', ' | Cifra Club'], '', $titleText);

            // If text is empty or junk, format slugs
            if (strlen(trim($titleText)) < 3) {
                $titleText = ucwords(str_replace('-', ' ', $artistSlug)) . ' - ' . ucwords(str_replace('-', ' ', $songSlug));
            }

            // Avoid duplicates
            $key = $artistSlug . '|' . $songSlug;
            if (!isset($unique[$key])) {
                $results[] = [
                    'artist_slug' => $artistSlug,
                    'song_slug' => $songSlug,
                    'display_title' => trim($titleText),
                    'url' => $href
                ];
                $unique[$key] = true;
            }

            if (count($results) >= 15) break;
        }
    }

    if (empty($results)) {
        return ['success' => false, 'message' => 'Nenhuma cifra encontrada. Tente ser mais específico.'];
    }

    return ['success' => true, 'results' => $results];
}

function getChord($artistSlug, $songSlug) {
    $url = "https://www.cifraclub.com.br/{$artistSlug}/{$songSlug}/";

    $html = fetchUrl($url);

    if (!$html) {
        return ['success' => false, 'message' => 'Cifra não encontrada ou erro ao acessar o site.'];
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Extract Title (h1.t1)
    $titleNode = $xpath->query("//h1[@class='t1']");
    $title = $titleNode->length > 0 ? $titleNode->item(0)->textContent : ucwords(str_replace('-', ' ', $songSlug));

    // Extract Artist (h2.t3)
    $artistNode = $xpath->query("//h2[@class='t3']");
    $artistName = $artistNode->length > 0 ? $artistNode->item(0)->textContent : ucwords(str_replace('-', ' ', $artistSlug));

    // Extract Tone
    $toneNode = $xpath->query("//span[@id='cifra_tom']/a");
    if ($toneNode->length === 0) {
         $toneNode = $xpath->query("//span[@id='cifra_tom']");
    }
    $tone = $toneNode->length > 0 ? $toneNode->item(0)->textContent : '';

    // Extract Pre content (The chords)
    $preNode = $xpath->query("//pre");
    $content = '';

    if ($preNode->length > 0) {
        $pre = $preNode->item(0);
        foreach ($pre->childNodes as $child) {
            $content .= $dom->saveHTML($child);
        }
    } else {
        return ['success' => false, 'message' => 'Conteúdo da cifra não encontrado nesta página.'];
    }

    return [
        'success' => true,
        'title' => trim($title),
        'artist' => trim($artistName),
        'tone' => trim($tone),
        'content' => $content,
        'url' => $url
    ];
}
?>
