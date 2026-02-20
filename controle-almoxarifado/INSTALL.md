# Guia de Instalação na Hostinger (v2)

Siga este guia atualizado para resolver problemas comuns de envio e configuração.

## Problemas Comuns (Diagnóstico Rápido)

### ERRO 404 (Not Found)
Se você vê esse erro, geralmente é porque o arquivo `.htaccess` (que é oculto) **não foi enviado** ou não está funcionando corretamente.
1.  Verifique no Gerenciador de Arquivos da Hostinger se existe um arquivo chamado `.htaccess` (começa com ponto) na raiz da pasta `almoxarifado`.
2.  Se não existir, crie-o manualmente com o conteúdo abaixo:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    # Se der erro 500, tente descomentar a linha abaixo:
    # RewriteBase /almoxarifado/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

### ERRO 500 (Internal Server Error)
Geralmente erro de configuração do PHP ou Banco de Dados.
1.  Verifique o arquivo `config/config.php` se as credenciais do banco estão corretas.
2.  Se estiver tudo certo, tente descomentar a linha `RewriteBase` no `.htaccess` (remova o `#` da frente).

---

## Instalação Limpa (Passo a Passo)

1.  **Baixe o ZIP Completo:** Use o arquivo `controle-almoxarifado-v2.zip` gerado.
2.  **Upload:** Envie para a pasta `public_html/almoxarifado` na Hostinger.
3.  **Extrair:** Clique com o botão direito e extraia (`Extract`).
    *   **Importante:** Verifique se os arquivos estão na raiz da pasta `almoxarifado`. Se estiverem dentro de uma subpasta (ex: `almoxarifado/controle-almoxarifado/`), mova-os para `almoxarifado/`.
4.  **Banco de Dados:**
    *   Crie o banco e usuário no painel.
    *   Importe o `database.sql` via phpMyAdmin.
5.  **Configuração:**
    *   Edite `config/config.php` com os dados do banco.
    *   Edite `APP_URL` para o endereço correto (ex: `https://seusite.com/almoxarifado`).

## Ferramenta de Teste
Se ainda estiver com problemas, acesse o arquivo de teste que incluí:
`https://seusite.com/almoxarifado/test_db.php`

Ele vai mostrar se o PHP, Banco e Rotas estão funcionando.
Se der erro 404 nele também, o problema é 100% no `.htaccess` ou na pasta errada.

---
Após resolver, delete o arquivo `test_db.php` por segurança.
