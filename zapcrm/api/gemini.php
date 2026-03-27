<?php
// zapcrm/api/gemini.php

class GeminiAPI {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = trim($apiKey);
    }

    public function generateResponse($userMessage, $context) {
        if (empty($this->apiKey)) {
            return "Erro: Chave API do Gemini não configurada.";
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $this->apiKey;

        // Construct the prompt with context
        $prompt = "Você é um assistente virtual prestativo de uma empresa conversando com um cliente via WhatsApp.\n";
        $prompt .= "Use o seguinte CONHECIMENTO DA EMPRESA para basear suas respostas. Se não souber a resposta com base no conhecimento, seja honesto e diga que não sabe ou vai verificar.\n\n";
        $prompt .= "--- CONHECIMENTO DA EMPRESA ---\n";
        $prompt .= $context . "\n";
        $prompt .= "-------------------------------\n\n";
        $prompt .= "Seja curto, direto e amigável (formato mensagem de WhatsApp, sem formatação complexa).\n";
        $prompt .= "Se o cliente expressar forte desejo de falar com um HUMANO, ATENDENTE, PESSOA, etc., responda EXATAMENTE E APENAS COM O TEXTO: [TRANSFERIR_PARA_HUMANO]\n\n";
        $prompt .= "Cliente diz: " . $userMessage;

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return "Erro de conexão com o Gemini: " . $error;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            return "Erro da API Gemini: " . $result['error']['message'];
        }

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($result['candidates'][0]['content']['parts'][0]['text']);
        }

        return "Desculpe, não consegui processar a resposta.";
    }
}
?>
