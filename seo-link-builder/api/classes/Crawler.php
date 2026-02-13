<?php
class Crawler {
    public function search($query, $maxResults = 10) {
        $url = "https://html.duckduckgo.com/html/?q=" . urlencode($query);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Rotate User Agents if possible, but stick to a standard one for now
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $html = curl_exec($ch);

        if (curl_errno($ch)) {
             error_log('Crawler Curl Error: ' . curl_error($ch));
             curl_close($ch);
             return [];
        }
        curl_close($ch);

        if (!$html) return [];

        $dom = new DOMDocument();
        // Suppress warnings for invalid HTML
        libxml_use_internal_errors(true);
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Selector for DuckDuckGo HTML results
        $nodes = $xpath->query("//a[contains(@class, 'result__a')]");

        $results = [];
        foreach ($nodes as $node) {
            $href = $node->getAttribute('href');
            $title = trim($node->textContent);

            // Handle DDG Redirects
            if (strpos($href, '/l/?uddg=') !== false) {
                $parts = parse_url($href);
                parse_str($parts['query'] ?? '', $query_params);
                if (isset($query_params['uddg'])) {
                    $href = $query_params['uddg'];
                }
            }

            // Filter out internal DDG links or ads if possible
            if (filter_var($href, FILTER_VALIDATE_URL) && count($results) < $maxResults) {
                // Basic duplication check in current result set
                $isDuplicate = false;
                foreach ($results as $res) {
                    if ($res['url'] === $href) {
                        $isDuplicate = true;
                        break;
                    }
                }

                if (!$isDuplicate) {
                    $results[] = [
                        'url' => $href,
                        'title' => $title
                    ];
                }
            }
        }

        return $results;
    }
}
?>