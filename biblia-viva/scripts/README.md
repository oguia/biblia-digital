# Automação de Contexto Bíblico com IA

Este script Python foi projetado para ler automaticamente cada capítulo da sua Bíblia (versão NVI), enviar o texto para o Google Gemini (IA), e salvar os insights gerados (Geografia, Cronologia, Aplicação Prática) diretamente no seu banco de dados MySQL.

## 1. Requisitos

Você precisa ter o Python instalado no seu computador.
Em seguida, instale as bibliotecas necessárias com este comando no terminal:

```bash
pip install mysql-connector-python google-generativeai
```

## 2. Configuração

Abra o arquivo `generate_context.py` em um editor de texto (como Bloco de Notas ou VS Code) e preencha as seguintes variáveis no topo do arquivo:

1.  `GEMINI_API_KEY`: Sua chave de API do Google.
    *   Obtenha gratuitamente aqui: [https://aistudio.google.com/](https://aistudio.google.com/)
2.  `DB_CONFIG`: As credenciais do seu banco de dados MySQL.
    *   **Atenção:** Se o seu banco estiver na Hostinger, você precisará liberar o "Acesso Remoto MySQL" no painel da Hostinger para que seu computador consiga conectar. Ou, alternativamente, rodar este script dentro de um servidor VPS.
    *   Se estiver testando localmente (XAMPP/Laragon), use `localhost`, `root`, etc.

## 3. Como Executar

No terminal, navegue até a pasta onde está o script e rode:

```bash
python generate_context.py
```

## 4. O que ele faz?

1.  Conecta no banco de dados.
2.  Lê a lista de todos os livros (Gênesis a Apocalipse).
3.  Para cada capítulo:
    *   Verifica se já tem contexto (para não repetir).
    *   Lê o texto bíblico (usando `ver_vrs_id = 6` por padrão).
    *   Envia para o Gemini com um prompt especializado.
    *   Recebe um JSON com Locais, Datas e Aplicações.
    *   Salva nas tabelas `contexto_geografico`, `cronologia`, `aplicacao_pratica`.
4.  Mostra o progresso na tela: `[...] Lendo Cap 1... [OK] Cap 1 processado e salvo!`

## 5. Notas Importantes

*   **Rate Limit:** O script tem um `time.sleep(2)` para esperar 2 segundos entre cada capítulo. Isso evita bloquear sua conta gratuita do Gemini.
*   **Custos:** O modelo `gemini-1.5-flash` é muito barato e tem uma cota gratuita generosa, mas fique atento aos limites da API.
*   **Erros:** Se a internet cair ou a API falhar, o script mostra o erro e tenta continuar ou para. Você pode rodar de novo e ele continuará de onde parou (pois verifica o que já existe).
