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

    // Cookie to look like a real user
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
    // Strategy 1: Direct Artist Lookup
    // If the user types "Aline Barros", we check cifraclub.com.br/aline-barros/
    // This is the most reliable way to get a list of songs without being blocked by Google/Bing.

    $artistSlug = slugify($query);
    $results = tryScrapeArtistPage($artistSlug);

    if (!empty($results)) {
        return ['success' => true, 'results' => $results];
    }

    // Strategy 2: Scrape Bing Search Results (Fallback)
    // If direct artist lookup fails (e.g. user typed a song name or misspelled artist), try search engine.
    $searchUrl = "https://www.bing.com/search?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
    $html = fetchUrl($searchUrl);

    if (!$html || strpos($html, 'captcha') !== false) {
        $searchUrl = "https://lite.duckduckgo.com/lite/?q=site:cifraclub.com.br+" . urlencode($query . " cifra");
        $html = fetchUrl($searchUrl);
    }

    if ($html) {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query("//a");
        $unique = [];

        foreach ($nodes as $node) {
            $href = $node->getAttribute('href');
            $href = urldecode($href);
            $href = str_replace('m.cifraclub.com.br', 'www.cifraclub.com.br', $href);

            if (preg_match('#cifraclub\.com\.br/([^/]+)/([^/]+)/?#', $href, $matches)) {
                $aSlug = $matches[1];
                $sSlug = $matches[2];

                if (in_array($aSlug, ['admin', 'app', 'letra', 'top', 'estilos', 'listas', 'blog', 'backend'])) continue;

                $titleText = trim($node->textContent);
                $titleText = preg_replace('/ - Cifra Club.*/i', '', $titleText);
                $titleText = str_ireplace(['Cifra Club - ', '...'], '', $titleText);

                $formattedTitle = ucwords(str_replace('-', ' ', $sSlug));
                $formattedArtist = ucwords(str_replace('-', ' ', $aSlug));

                if (strlen($titleText) < 5 || stripos($titleText, 'http') !== false) {
                   $displayTitle = "$formattedTitle - $formattedArtist";
                } else {
                   $displayTitle = $titleText;
                }

                $displayTitle = str_ireplace(['Cifra de ', 'Cifras de '], '', $displayTitle);

                if (strpos($displayTitle, ' - ') !== false) {
                    $parts = explode(' - ', $displayTitle);
                    $displaySong = trim($parts[0]);
                    $displayArtist = trim($parts[1]);
                } else {
                    $displaySong = $formattedTitle;
                    $displayArtist = $formattedArtist;
                }

                $key = $aSlug . '|' . $sSlug;
                if (!isset($unique[$key])) {
                    $results[] = [
                        'artist_slug' => $aSlug,
                        'song_slug' => $sSlug,
                        'display_title' => $displaySong,
                        'display_artist' => $displayArtist,
                        'url' => $href
                    ];
                    $unique[$key] = true;
                }
                if (count($results) >= 15) break;
            }
        }
    }

    if (!empty($results)) {
        return ['success' => true, 'results' => $results];
    }

    return ['success' => false, 'message' => 'Nenhuma cifra encontrada. Tente digitar o nome do Artista.'];
}

function tryScrapeArtistPage($artistSlug) {
    $url = "https://www.cifraclub.com.br/{$artistSlug}/";
    $html = fetchUrl($url);

    if (!$html) return [];

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Look for song links in the "Top Músicas" or "Todas as Músicas" list
    // Usually <ul class="list-songs"> or <ol id="top-songs">
    // Structure: <a href="/artist/song/" class="song-link"> <span class="song-name">Title</span> </a>

    $results = [];
    $nodes = $xpath->query("//a[contains(@href, '/{$artistSlug}/')]");

    foreach ($nodes as $node) {
        $href = $node->getAttribute('href');

        // Extract song slug from /artist/song/
        if (preg_match("#/{$artistSlug}/([^/]+)/#", $href, $matches)) {
            $songSlug = $matches[1];

            // Skip utility links
            if (in_array($songSlug, ['letra', 'discografia', 'fotos', 'video', 'biografia'])) continue;

            // Get Song Title
            // Often inside a span, or just text content
            $title = trim($node->textContent);

            // Clean up title (sometimes has numbering like "1. Song")
            $title = preg_replace('/^\d+\.\s*/', '', $title);

            if (empty($title)) $title = ucwords(str_replace('-', ' ', $songSlug));

            $results[] = [
                'artist_slug' => $artistSlug,
                'song_slug' => $songSlug,
                'display_title' => $title,
                'display_artist' => ucwords(str_replace('-', ' ', $artistSlug)),
                'url' => "https://www.cifraclub.com.br{$href}"
            ];

            if (count($results) >= 20) break;
        }
    }

    return $results;
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
