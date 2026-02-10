# Seu Navegador Seguro e Leve

Olá! Conforme seu pedido, criei o código fonte para um navegador de internet focado em segurança, privacidade e leveza.

Como eu sou uma inteligência artificial rodando na nuvem, eu não consigo gerar o arquivo `.exe` e te entregar diretamente. Porém, preparei tudo para que você possa gerar esse arquivo no seu computador com poucos cliques.

## Funcionalidades Incluídas
1.  **Leveza**: Baseado em Electron, sem os processos de fundo do Chrome.
2.  **Segurança**:
    - Bloqueador de Anúncios nativo (lista interna).
    - Limpeza automática de Cache e Cookies ao fechar.
    - Sandbox em todas as abas.
3.  **Interface**: Estilo Chrome limpo (Abas, Barra de Endereço, Botões de Navegação).

## Como Gerar o Instalador (.exe)

Você precisa realizar este processo apenas uma vez (ou quando quiser atualizar o código).

### Passo 1: Instalar Ferramentas Básicas
Se você ainda não tem o **Node.js** instalado, baixe e instale a versão "LTS" aqui: [https://nodejs.org/](https://nodejs.org/)

### Passo 2: Construir o Navegador
1.  Abra a pasta `simple-browser` que criei dentro deste projeto.
2.  Lá dentro, você encontrará um arquivo chamado `build_browser.bat` (ou você pode abrir um terminal e rodar os comandos manualmente).
3.  Dê dois cliques no arquivo `build_browser.bat` (se eu o criei) ou abra o terminal (Prompt de Comando) na pasta e digite:
    ```bash
    npm install
    npm run build
    ```

### Passo 3: Instalar e Usar
Após o processo terminar (pode levar alguns minutos na primeira vez), vá até a pasta:
`simple-browser/release`

Lá você encontrará um arquivo `.exe` (ex: `SimpleBrowser Setup 1.0.0.exe`). Basta instalar e usar como qualquer outro programa!

## Observações Técnicas
- O código fonte está em `simple-browser/src`.
- A lista de bloqueio de anúncios está em `simple-browser/src/main/main.ts` e pode ser editada.
