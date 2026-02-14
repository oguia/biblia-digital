<?php
class Gemini {
    private $apiKey;
    // Fallback to gemini-1.5-flash-001 (specific version) if gemini-pro is unavailable
    private $model = 'gemini-1.5-flash-001';

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generate($prompt) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $data = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return "Error: Connection failed ($error)";
        }

        curl_close($ch);

        $json = json_decode($response, true);

        if ($httpCode !== 200) {
             $msg = $json['error']['message'] ?? 'Unknown API Error';
             return "Error ($httpCode): $msg";
        }

        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            return $json['candidates'][0]['content']['parts'][0]['text'];
        }

        return "Error: No candidates returned (Safety settings blocked response?)";
    }
}
?>