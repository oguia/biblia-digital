# Guia de Instalação - Hostinger (Hpanel)

Este guia explica como instalar o **Sistema Financeiro** em uma hospedagem compartilhada da Hostinger.

## Método Fácil (Arquivo Zip)

Preparei um arquivo pronto para instalação: `install-hostinger.zip`.

1.  **Baixe o arquivo** `install-hostinger.zip` que está na pasta `sistema-financeiro` deste repositório.
2.  **Configuração do Banco de Dados (Na Hostinger):**
    *   Acesse o Hpanel.
    *   Vá em **Bancos de Dados MySQL**.
    *   Crie um banco de dados e usuário (Anote a senha!).
    *   Entre no **phpMyAdmin**, clique em **Importar** e envie o arquivo `database.sql` (você pode extrair o zip no seu PC para pegar esse arquivo, ou pegar ele da pasta `sistema-financeiro/database.sql`).
3.  **Upload dos Arquivos:**
    *   No **Gerenciador de Arquivos** da Hostinger, vá para a pasta onde quer instalar (ex: `public_html/financeiro`).
    *   Faça upload do `install-hostinger.zip`.
    *   Clique com o botão direito no zip e escolha **Extract** (Extrair).
    *   (Opcional) Pode apagar o zip depois.
4.  **Conexão:**
    *   Edite o arquivo `api/config.php` que foi extraído.
    *   Coloque os dados do banco que você criou (Nome, Usuário, Senha).
5.  **Pronto!** Acesse o site.

---

## Método Manual (Compilando do Zero)

Se preferir compilar você mesmo:

### 1. Preparação (No seu computador)

Antes de enviar os arquivos, você precisa "compilar" a parte visual (Frontend) do sistema.

1.  Certifique-se de ter o **Node.js** instalado.
2.  Abra o terminal na pasta `sistema-financeiro/frontend`.
3.  Instale as dependências:
    ```bash
    npm install
    # ou
    pnpm install
    ```
4.  Gere a versão de produção:
    ```bash
    npm run build
    # ou
    pnpm build
    ```
    *Isso criará uma pasta chamada `dist` dentro de `sistema-financeiro`.*

### 2. Configuração do Banco de Dados (Na Hostinger)

1.  Acesse o **Hpanel** da Hostinger.
2.  Vá em **Bancos de Dados** -> **Gerenciamento de Bancos de Dados**.
3.  Crie um novo banco de dados MySQL:
    *   **Nome do Banco:** Ex: `u123456_financeiro`
    *   **Usuário:** Ex: `u123456_admin`
    *   **Senha:** Crie uma senha forte e ANOTE.
4.  Após criar, clique em **Entrar no phpMyAdmin**.
5.  Selecione o banco criado na esquerda.
6.  Clique na aba **Importar**.
7.  Selecione o arquivo `sistema-financeiro/database.sql` do seu computador e execute.
    *   **Nota:** Se você já tinha o sistema instalado, precisará atualizar o banco de dados. O jeito mais fácil é apagar as tabelas `transactions` e `users` e importar o novo `database.sql` (cuidado, isso apaga os dados!), ou rodar manualmente este comando SQL na aba SQL:
        ```sql
        ALTER TABLE transactions ADD COLUMN status ENUM('paid', 'pending') DEFAULT 'paid';
        ```
    *   **Método Automático (Recomendado):** Após enviar os novos arquivos, acesse `https://seusite.com/financeiro/api/migration.php`. Isso vai tentar criar a coluna automaticamente para você.

### 3. Configuração do Backend (API PHP)

1.  Abra o arquivo `sistema-financeiro/api/config.php` no seu computador.
2.  Edite as seguintes linhas com os dados que você criou na Hostinger:
    ```php
    define('DB_NAME', 'u123456_financeiro'); // Nome do Banco criado
    define('DB_USER', 'u123456_admin');      // Usuário criado
    define('DB_PASS', 'SuaSenhaAqui');       // Senha criada
    ```
3.  (Opcional) No mesmo arquivo (`api/config.php`), mude a `JWT_SECRET` para algo aleatório e seguro.

### 4. Upload dos Arquivos

Agora vamos enviar tudo para o servidor.

1.  No Hpanel, vá em **Gerenciador de Arquivos**.
2.  Entre na pasta `public_html`.
3.  (Recomendado) Crie uma pasta para o sistema, ex: `financeiro`, ou coloque na raiz se for o único site.
4.  **Upload da API:**
    *   Crie uma pasta chamada `api` dentro de `public_html/financeiro`.
    *   Envie todo o conteúdo da pasta `sistema-financeiro/api` (auth, transactions, config.php, etc) para dentro de `public_html/financeiro/api`.
5.  **Upload do Frontend:**
    *   Vá até a pasta `sistema-financeiro/dist` no seu computador (gerada no passo 1).
    *   Envie **todos os arquivos e pastas** de dentro de `dist` para `public_html/financeiro`.
    *   Você verá arquivos como `index.html`, `vite.svg` e uma pasta `assets`.

### 5. Testando

1.  Acesse seu site: `https://seusite.com/financeiro`.
2.  Você deve ver a tela de Login.
3.  Clique em "Cadastre-se" e crie uma conta.
4.  Se conseguir entrar, o sistema está funcionando!

### Resolução de Problemas

*   **Erro ao conectar no banco:** Verifique o `api/config.php`.
*   **Erro 404 na API:** Verifique se a pasta `api` foi enviada corretamente.
*   **Tela Branca:** Abra o Console do Navegador (F12) e veja se há erros vermelhos. Geralmente é caminho errado no upload.
