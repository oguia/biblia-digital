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
    // Use a standard browser UA to avoid blocks
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
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
    // 1. Try DuckDuckGo Lite (HTML only) - often easier to scrape than Google
    // Format: https://lite.duckduckgo.com/lite/?q=site:cifraclub.com.br+QUERY

    $searchUrl = "https://lite.duckduckgo.com/lite/?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
    $html = fetchUrl($searchUrl);

    if (!$html) {
        // Fallback to Google if DDG fails
        $searchUrl = "https://www.google.com/search?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
        $html = fetchUrl($searchUrl);
    }

    if (!$html) {
        return ['success' => false, 'message' => 'Erro ao realizar a busca. Tente novamente mais tarde.'];
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    $results = [];
    $unique = [];

    // Look for all links
    $nodes = $xpath->query("//a");

    foreach ($nodes as $node) {
        $href = $node->getAttribute('href');

        // Handle redirect URL formats
        if (strpos($href, '/url?q=') !== false) {
            $parts = parse_url($href);
            parse_str($parts['query'], $queryParts);
            $href = $queryParts['q'] ?? '';
        } elseif (strpos($href, '//duckduckgo.com/l/?uddg=') !== false) {
             // Handle DDG redirect
             $parts = parse_url($href);
             parse_str($parts['query'], $queryParts);
             $href = $queryParts['uddg'] ?? '';
        }

        // Decode URL
        $href = urldecode($href);

        // Filter for valid Cifra Club song URLs
        // Pattern: https://www.cifraclub.com.br/ARTIST/SONG/
        if (preg_match('#cifraclub\.com\.br/([^/]+)/([^/]+)/$#', $href, $matches)) {
            $artistSlug = $matches[1];
            $songSlug = $matches[2];

            // Skip non-song pages
            if (in_array($artistSlug, ['admin', 'app', 'letra', 'top', 'estilos', 'listas', 'blog'])) continue;

            // Extract title from the link text
            $titleText = trim($node->textContent);

            // Clean up title (remove site branding)
            $titleText = str_ireplace([' - Cifra Club', ' | Cifra Club', 'Cifra Club - '], '', $titleText);

            // If the title is too short or generic (like "Translate this page"), construct it from slugs
            // Often search engines show "Cifra de Ressuscita-me - Aline Barros"

            // Basic Formatter from slugs if title seems bad
            $formattedTitle = ucwords(str_replace('-', ' ', $songSlug));
            $formattedArtist = ucwords(str_replace('-', ' ', $artistSlug));

            // Heuristic: If title doesn't contain the song name, use the formatted one
            if (stripos($titleText, str_replace('-', ' ', $songSlug)) === false) {
               $displayTitle = "$formattedTitle - $formattedArtist";
            } else {
               $displayTitle = $titleText;
            }

            // Separate Song and Artist for display if possible
            // Usually "Song - Artist"
            $parts = explode(' - ', $displayTitle);
            if (count($parts) >= 2) {
                // Heuristic: usually Song comes first in title, but let's trust the slug
                $displaySong = $formattedTitle;
                $displayArtist = $formattedArtist;
            } else {
                $displaySong = $formattedTitle;
                $displayArtist = $formattedArtist;
            }

            // Avoid duplicates
            $key = $artistSlug . '|' . $songSlug;
            if (!isset($unique[$key])) {
                $results[] = [
                    'artist_slug' => $artistSlug,
                    'song_slug' => $songSlug,
                    'display_title' => $displaySong,
                    'display_artist' => $displayArtist,
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
        // Try alternate selector for tabs/lyrics if pre is missing
        $content = "Conteúdo protegido ou formato não suportado.";
        return ['success' => false, 'message' => 'Conteúdo da cifra não encontrado. Pode ser uma página protegida.'];
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
