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

    // Important for Bing to not block us immediately
    curl_setopt($ch, CURLOPT_COOKIE, "SRCHHPGUSR=SRCHLANG=pt");

    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($html === FALSE || $httpCode != 200) {
        return false;
    }
    return $html;
}

function searchSongs($query) {
    // Strategy: Scrape Bing Search Results
    $searchUrl = "https://www.bing.com/search?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
    $html = fetchUrl($searchUrl);

    // If Bing fails, fallback to DuckDuckGo Lite
    if (!$html || strpos($html, 'captcha') !== false) {
        $searchUrl = "https://lite.duckduckgo.com/lite/?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
        $html = fetchUrl($searchUrl);
    }

    if (!$html) {
        return tryDirectGuess($query);
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    $results = [];
    $unique = [];

    $nodes = $xpath->query("//a");

    foreach ($nodes as $node) {
        $href = $node->getAttribute('href');
        $href = urldecode($href);

        // Normalize URL (handle m.cifraclub.com.br)
        $href = str_replace('m.cifraclub.com.br', 'www.cifraclub.com.br', $href);

        // Filter for valid Cifra Club song URLs
        // Pattern: https://www.cifraclub.com.br/ARTIST/SONG/
        if (preg_match('#cifraclub\.com\.br/([^/]+)/([^/]+)/?#', $href, $matches)) {
            $artistSlug = $matches[1];
            $songSlug = $matches[2];

            // Skip non-song pages
            if (in_array($artistSlug, ['admin', 'app', 'letra', 'top', 'estilos', 'listas', 'blog', 'backend'])) continue;

            // Extract title from the link text
            $titleText = trim($node->textContent);

            // Clean up title
            $titleText = preg_replace('/ - Cifra Club.*/i', '', $titleText);
            $titleText = preg_replace('/ \| Cifra Club.*/i', '', $titleText);
            $titleText = str_ireplace(['Cifra Club - ', '...'], '', $titleText);

            $formattedTitle = ucwords(str_replace('-', ' ', $songSlug));
            $formattedArtist = ucwords(str_replace('-', ' ', $artistSlug));

            // Heuristic to clean up title
            if (strlen($titleText) < 5 || stripos($titleText, 'http') !== false) {
               $displayTitle = "$formattedTitle - $formattedArtist";
            } else {
               $displayTitle = $titleText;
            }

            $displayTitle = str_ireplace('Cifra de ', '', $displayTitle);
            $displayTitle = str_ireplace('Cifras de ', '', $displayTitle);

            // Try to split by " - "
            if (strpos($displayTitle, ' - ') !== false) {
                $parts = explode(' - ', $displayTitle);
                $displaySong = trim($parts[0]);
                $displayArtist = trim($parts[1]);
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

    // If no results via scraping, AND the query looks simple, return empty message
    // If query contains hyphen, try direct guess
    if (empty($results)) {
        if (strpos($query, '-') !== false) {
            return tryDirectGuess($query);
        }
        return ['success' => false, 'message' => 'Nenhuma cifra encontrada para "' . htmlspecialchars($query) . '". Tente adicionar o nome do artista.'];
    }

    return ['success' => true, 'results' => $results];
}

function tryDirectGuess($query) {
    if (strpos($query, '-') !== false) {
        list($artist, $song) = explode('-', $query, 2);
        $artistSlug = slugify($artist);
        $songSlug = slugify($song);

        $url = "https://www.cifraclub.com.br/{$artistSlug}/{$songSlug}/";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code == 200) {
             return ['success' => true, 'results' => [[
                'artist_slug' => $artistSlug,
                'song_slug' => $songSlug,
                'display_title' => ucwords($song),
                'display_artist' => ucwords($artist),
                'url' => $url
            ]]];
        }
    }

    return ['success' => false, 'message' => 'Não encontramos resultados exatos.'];
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

    // Extract Title
    $titleNode = $xpath->query("//h1[@class='t1']");
    $title = $titleNode->length > 0 ? $titleNode->item(0)->textContent : ucwords(str_replace('-', ' ', $songSlug));

    // Extract Artist
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
