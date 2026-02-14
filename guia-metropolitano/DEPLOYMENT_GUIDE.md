# Guia de Negócios e Serviços - Guia de Implantação

## 1. Banco de Dados
1. Acesse o painel da Hostinger (phpMyAdmin).
2. Crie um novo banco de dados (ex: `u123456789_guia`).
3. Importe o arquivo `api/schema.sql`.

## 2. Configuração do Backend (API)
1. Edite o arquivo `api/config.php` com as credenciais do banco de dados que você criou:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'u123456789_guia');
   define('DB_USER', 'u123456789_usuario');
   define('DB_PASS', 'SuaSenhaForte');
   ```
2. Adicione sua chave da Google Gemini API no `config.php`:
   ```php
   define('GEMINI_API_KEY', 'SUA_CHAVE_AQUI');
   ```
3. Configure as credenciais do Mercado Pago no `config.php` se for usar pagamentos.

## 3. Upload dos Arquivos
1. No Gerenciador de Arquivos da Hostinger, vá para a pasta `public_html`.
2. Crie uma pasta chamada `guia` (ou use a raiz se for um domínio exclusivo).
3. Faça upload do conteúdo da pasta `client/dist` para dentro dessa pasta. (Estes são os arquivos do site: index.html, assets/, etc).
4. Crie uma pasta chamada `api` dentro da pasta `guia`.
5. Faça upload de todos os arquivos da pasta `api` (do seu computador) para a pasta `api` no servidor.

A estrutura final deve ser:
```
/public_html/guia/
  ├── assets/
  ├── index.html
  ├── vite.svg
  └── api/
      ├── config.php
      ├── search.php
      ├── classes/
      └── ...
```

## 4. Popular o Banco de Dados (Seed)
Para começar com dados, você pode rodar o script de seed via SSH ou acessando pelo navegador (não recomendado para produção, mas útil para teste inicial).
- SSH: `php api/seeder.php "Pizzaria" "Curitiba"`
- Se não tiver SSH, você pode tentar acessar `https://seu-site.com/guia/api/seeder.php?cat=Pizzaria` (precisará adaptar o script para receber GET parameters se quiser rodar via browser, atualmente ele espera CLI args).

**Recomendação:** Use o terminal SSH da Hostinger para rodar o `seed_data.sh`.

## 5. Teste
Acesse `https://seu-site.com/guia` e tente fazer uma busca.

## Suporte
Se precisar de ajuda, verifique os logs de erro na pasta `api` ou no painel da Hostinger.
