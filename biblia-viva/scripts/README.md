# Automação de Contexto Bíblico

Este script Python serve como um template para automatizar a geração de conteúdo (Geografia, Cronologia, Aplicação Prática) usando Inteligência Artificial (Gemini, OpenAI, etc).

## Requisitos

- Python 3.8+
- Bibliotecas Python:
  - `mysql-connector-python` (para conectar ao banco)
  - `google-generativeai` (se usar Gemini)

## Instalação

```bash
pip install mysql-connector-python google-generativeai
```

## Como Usar

1.  Abra o arquivo `scripts/generate_context.py`.
2.  Configure suas credenciais de banco de dados (`db_config`).
3.  Obtenha uma API Key do Google Gemini (ou outra IA).
4.  Descomente a parte do código que faz a chamada real à API (`genai.generate_content`).
5.  Execute o script:

```bash
python scripts/generate_context.py
```

O script irá iterar sobre os capítulos, ler o texto bíblico do seu banco, enviar para a IA e salvar as respostas estruturadas nas tabelas de contexto automaticamente.
