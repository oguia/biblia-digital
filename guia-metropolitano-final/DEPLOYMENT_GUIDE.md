# Guia de Negócios e Serviços - Guia de Implantação Rápida

Este pacote contém todos os arquivos necessários para colocar o Guia Metropolitano no ar.

## 🚀 Instalação Rápida (SQLite - Padrão)

O sistema já vem configurado para usar um banco de dados SQLite (arquivo local), o que significa que **não é necessário criar um banco de dados MySQL** no painel da Hostinger inicialmente.

1. **Upload:**
   - Faça o upload do arquivo `guia-metropolitano-final.zip` para a pasta `public_html` (ou subdomínio) na sua hospedagem Hostinger.
   - Extraia o arquivo ZIP.
   - Mova o conteúdo da pasta extraída (`guia-metropolitano-final/`) para a raiz do seu site, se necessário.

2. **Permissões:**
   - Certifique-se de que a pasta `api/` tenha permissões de escrita (755 ou 777 dependendo da hospedagem) para que o arquivo do banco de dados `guia.sqlite` possa ser criado/atualizado.

3. **Configuração (Opcional):**
   - Edite o arquivo `api/config.php` se desejar alterar a chave da API do Google Gemini ou mudar para MySQL no futuro.
   - Por padrão, ele usará a chave `YOUR_GEMINI_API_KEY_HERE`. **Você deve alterar isso para funcionar a IA.**

4. **Popular Dados:**
   - Acesse no navegador: `https://seu-site.com/api/seeder_ai_only.php`
   - Isso irá criar as tabelas e popular o banco com dados de exemplo de Curitiba usando IA.

5. **Pronto!**
   - Acesse `https://seu-site.com` e comece a usar.

---

## ⚙️ Instalação Produção (MySQL - Recomendado)

Para maior performance, recomendamos usar MySQL.

1. **Banco de Dados:**
   - Crie um banco de dados MySQL no painel da Hostinger.
   - Importe o arquivo `api/schema.sql` via phpMyAdmin.

2. **Configuração:**
   - Edite `api/config.php` e preencha as credenciais do banco:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'u123456789_guia');
     define('DB_USER', 'u123456789_usuario');
     define('DB_PASS', 'SuaSenhaForte');
     ```

## 📂 Estrutura de Arquivos

```
/public_html/
  ├── assets/          (Arquivos do site: JS, CSS)
  ├── api/             (Backend PHP)
  │   ├── config.php
  │   ├── guia.sqlite  (Banco de dados se usar SQLite)
  │   └── ...
  ├── index.html       (Página principal)
  ├── .htaccess        (Configuração de rotas)
  └── ...
```

## 🔑 Acesso Admin

- Painel: `https://seu-site.com/admin`
- Senha Padrão: `guia-admin-secret-123` (Altere no arquivo `api/admin.php`)
