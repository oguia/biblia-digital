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
    // Use a mobile UA to get simpler HTML if possible, or desktop
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
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
    // 1. Try DuckDuckGo HTML version (often best for scraping)
    // https://html.duckduckgo.com/html/?q=site:cifraclub.com.br+QUERY

    $searchUrl = "https://html.duckduckgo.com/html/?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
    $html = fetchUrl($searchUrl);

    // 2. Fallback: Try Bing if DDG fails or returns captcha
    if (!$html || strpos($html, 'captcha') !== false) {
        $searchUrl = "https://www.bing.com/search?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
        $html = fetchUrl($searchUrl);
    }

    // 3. Last Resort Fallback: Direct URL Guess (if query looks like "Artist Song")
    // This handles cases where search engines block us entirely.
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

    // Look for all links
    $nodes = $xpath->query("//a");

    foreach ($nodes as $node) {
        $href = $node->getAttribute('href');
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
            $titleText = str_ireplace([' - Cifra Club', ' | Cifra Club', 'Cifra Club - ', '...'], '', $titleText);

            // Basic Formatter from slugs if title seems bad or generic
            $formattedTitle = ucwords(str_replace('-', ' ', $songSlug));
            $formattedArtist = ucwords(str_replace('-', ' ', $artistSlug));

            // Heuristic: If title doesn't look like a song title, use the formatted one
            if (strlen($titleText) < 5 || stripos($titleText, 'http') !== false) {
               $displayTitle = "$formattedTitle - $formattedArtist";
            } else {
               $displayTitle = $titleText;
            }

            // Separate Song and Artist for display
            // Usually "Song - Artist" or "Cifra de Song - Artist"
            $displayTitle = str_ireplace('Cifra de ', '', $displayTitle);

            // Try to split by " - " or " por "
            if (strpos($displayTitle, ' - ') !== false) {
                $parts = explode(' - ', $displayTitle);
                $displaySong = trim($parts[0]);
                $displayArtist = trim($parts[1]);
            } else {
                // Fallback to slugs
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
        // If scraping returned nothing (maybe blocked?), try guessing directly
        return tryDirectGuess($query);
    }

    return ['success' => true, 'results' => $results];
}

function tryDirectGuess($query) {
    // Try to split query into artist/song
    // This is a "shot in the dark" for when search engines fail
    $parts = preg_split('/\s+/', trim($query));
    if (count($parts) < 2) {
        return ['success' => false, 'message' => 'Nenhuma cifra encontrada. Tente ser mais específico.'];
    }

    // We can't know which part is artist or song, so let's try a common pattern
    // Usually people type "Artist Song" or "Song Artist"
    // Let's assume the query IS the song if it's long, or try to find an artist match

    // Actually, let's just try to create a slug from the whole query as a song? No.
    // Let's try to assume the first word is artist? No.

    // Let's just return a generic error asking for more details or try one specific guess if it looks like "Artist - Song"
    if (strpos($query, '-') !== false) {
        list($artist, $song) = explode('-', $query, 2);
        $artistSlug = slugify($artist);
        $songSlug = slugify($song);

        $url = "https://www.cifraclub.com.br/{$artistSlug}/{$songSlug}/";
        $html = fetchUrl($url);
        if ($html && strpos($html, 't1') !== false) { // Basic check for success
             return ['success' => true, 'results' => [[
                'artist_slug' => $artistSlug,
                'song_slug' => $songSlug,
                'display_title' => ucwords($song),
                'display_artist' => ucwords($artist),
                'url' => $url
            ]]];
        }
    }

    return ['success' => false, 'message' => 'Não conseguimos encontrar. Tente digitar "Artista - Música" (com o traço) para ajudar.'];
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
