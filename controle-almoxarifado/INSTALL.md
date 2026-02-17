# Guia de Instalação na Hostinger (Passo a Passo)

Siga este guia para instalar o Controle de Almoxarifado em sua conta de hospedagem compartilhada.

## 1. Preparação (Local)

1.  Tenha certeza de que todos os arquivos do projeto estão na sua máquina.
2.  Instale o [Composer](https://getcomposer.org/) em sua máquina local se ainda não tiver.
3.  Abra o terminal na pasta do projeto e execute:
    ```bash
    composer install --no-dev --optimize-autoloader
    ```
    Isso criará a pasta `vendor/` com todas as bibliotecas necessárias (PhpSpreadsheet, Dompdf, etc).
4.  Compacte (zip) todo o conteúdo da pasta do projeto (incluindo a pasta `vendor`).

## 2. Upload para Hostinger

1.  Acesse o Painel de Controle (hPanel) da Hostinger.
2.  Vá em **Gerenciador de Arquivos**.
3.  Navegue até `public_html`.
4.  Crie uma pasta chamada `almoxarifado` (ou o nome que preferir).
5.  Faça o upload do arquivo `.zip` que você criou.
6.  Extraia o arquivo `.zip` dentro da pasta `almoxarifado`.
    *   Verifique se os arquivos estão na raiz da pasta `almoxarifado` e não dentro de uma subpasta extra.

## 3. Criar Banco de Dados

1.  No hPanel, vá em **Bancos de Dados MySQL**.
2.  Crie um novo banco de dados e usuário.
    *   Nome do Banco: ex: `u123456_estoque`
    *   Usuário: ex: `u123456_admin`
    *   Senha: Crie uma senha forte e anote.
3.  Clique em **Criar**.
4.  Após criar, clique em **phpMyAdmin** ao lado do banco criado.
5.  No phpMyAdmin, selecione o banco de dados à esquerda.
6.  Vá na aba **Importar**.
7.  Selecione o arquivo `database.sql` que está na pasta do projeto.
8.  Clique em **Executar**.

## 4. Configurar Conexão

1.  No Gerenciador de Arquivos, abra a pasta `almoxarifado/config`.
2.  Edite o arquivo `config.php`.
3.  Altere as linhas com os dados do banco que você criou:
    ```php
    define('DB_HOST', 'localhost'); // Geralmente é localhost na Hostinger
    define('DB_NAME', 'u123456_estoque');
    define('DB_USER', 'u123456_admin');
    define('DB_PASS', 'SuaSenhaForteAqui');
    define('APP_URL', 'https://seusite.com/almoxarifado');
    ```
4.  Salve o arquivo.

## 5. Testar

1.  Acesse `https://seusite.com/almoxarifado` no navegador.
2.  Você deve ver a tela de Login.
3.  Use as credenciais padrão:
    *   **E-mail:** `admin@sistema.com`
    *   **Senha:** `admin123`
4.  Após o primeiro acesso, vá no banco de dados ou crie uma funcionalidade de "Perfil" para alterar sua senha (recomendado).

## Solução de Problemas Comuns

*   **Erro 404 / Página não encontrada:** Verifique se o arquivo `.htaccess` foi enviado corretamente. Ele é oculto em alguns sistemas. No Gerenciador de Arquivos da Hostinger, certifique-se de que ele está lá.
*   **Erro 500:** Verifique o arquivo `config/config.php` se há erros de sintaxe ou credenciais erradas.
*   **Permissão Negada:** A pasta `public/uploads` deve ter permissão 755 (padrão) ou 777 se houver problemas de upload.

---
Se precisar gerar novas dependências, rode `composer install` localmente e re-envie a pasta `vendor`.
