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

function getChord($artist, $song) {
    $artistSlug = slugify($artist);
    $songSlug = slugify($song);

    $url = "https://www.cifraclub.com.br/{$artistSlug}/{$songSlug}/";

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for simplicity on shared hosts

    // Execute cURL request
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    // Close cURL session
    curl_close($ch);

    if ($html === FALSE || $httpCode != 200) {
        return ['success' => false, 'message' => 'Cifra não encontrada ou erro ao acessar o site. (Código: ' . $httpCode . ')'];
    }

    $dom = new DOMDocument();
    // Suppress warnings for malformed HTML
    libxml_use_internal_errors(true);
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Extract Title (h1.t1)
    $titleNode = $xpath->query("//h1[@class='t1']");
    $title = $titleNode->length > 0 ? $titleNode->item(0)->textContent : ucwords($song);

    // Extract Artist (h2.t3)
    $artistNode = $xpath->query("//h2[@class='t3']");
    $artistName = $artistNode->length > 0 ? $artistNode->item(0)->textContent : ucwords($artist);

    // Extract Tone (span#cifra_tom a) - catch the link inside span
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
        // Save HTML of children to preserve <b> tags
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
        'content' => $content, // This contains HTML (b tags)
        'url' => $url
    ];
}
?>
