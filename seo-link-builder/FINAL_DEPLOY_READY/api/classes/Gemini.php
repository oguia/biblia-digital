<?php
class Gemini {
    private $apiKey;
    private $model = 'gemini-1.5-flash';

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

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
            return "Error: Could not connect to Gemini API.";
        }

        curl_close($ch);

        $json = json_decode($response, true);

        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            return $json['candidates'][0]['content']['parts'][0]['text'];
        }

        if (isset($json['error'])) {
             error_log('Gemini API Error: ' . json_encode($json['error']));
             return "Error: Gemini API responded with error: " . $json['error']['message'];
        }

        return "Error: Unexpected response format from Gemini.";
    }
}
?>