<?php
// guia-metropolitano/api/classes/Gemini.php

class Gemini {
    private $apiKey;
    // Updated to 2.0-flash based on user's available models
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
    private $lastError = null;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getLastError() {
        return $this->lastError;
    }

    public function generate($prompt) {
        if (empty($this->apiKey) || $this->apiKey === 'YOUR_GEMINI_API_KEY_HERE') {
            $this->lastError = "API Key is missing or default.";
            return null;
        }

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($this->apiUrl . '?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // SSL Verification DISABLED for Shared Hosting compatibility
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->lastError = 'Curl error: ' . curl_error($ch);
            curl_close($ch);
            return null;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $this->lastError = "API returned HTTP $httpCode. Response: " . substr($response, 0, 200);
            return null;
        }

        $json = json_decode($response, true);

        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            return $json['candidates'][0]['content']['parts'][0]['text'];
        }

        $this->lastError = "Invalid JSON structure or empty candidates.";
        return null;
    }

    public function interpretSearch($query) {
        $prompt = "Analyze this search query for a local business directory in Curitiba: '{$query}'. " .
                  "Return ONLY a JSON object with these keys: " .
                  "'category_keyword' (string, main service type, e.g., 'pizzaria', 'advogado'), " .
                  "'location_keyword' (string, neighborhood or city mentioned, if any, else null), " .
                  "'intent' (string, e.g., 'emergency', 'general', 'cheap'). " .
                  "Do not include markdown formatting.";

        $result = $this->generate($prompt);
        if (!$result) return null;

        $result = str_replace(['```json', '```'], '', $result);
        return json_decode($result, true);
    }
}
