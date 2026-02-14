<?php
// guia-metropolitano/api/classes/Gemini.php

class Gemini {
    private $apiKey;
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generate($prompt) {
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

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return null; // Handle error appropriately in production
        }

        curl_close($ch);

        $json = json_decode($response, true);

        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            return $json['candidates'][0]['content']['parts'][0]['text'];
        }

        return null;
    }

    /**
     * Parses a natural language search query into structured data.
     */
    public function interpretSearch($query) {
        $prompt = "Analyze this search query for a local business directory in Curitiba: '{$query}'. " .
                  "Return ONLY a JSON object with these keys: " .
                  "'category_keyword' (string, main service type, e.g., 'pizzaria', 'advogado'), " .
                  "'location_keyword' (string, neighborhood or city mentioned, if any, else null), " .
                  "'intent' (string, e.g., 'emergency', 'general', 'cheap'). " .
                  "Do not include markdown formatting.";

        $result = $this->generate($prompt);
        // Clean markdown code blocks if present
        $result = str_replace(['```json', '```'], '', $result);
        return json_decode($result, true);
    }
}
