# INSTRUÇÕES DE INSTALAÇÃO - BÍBLIA EM CONTEXTO (VERSÃO 5 FINAL)

## 1. Arquivo de Instalação

O arquivo principal para instalação é: **`BIBLIA_EM_CONTEXTO_V5_FINAL.zip`**

Este arquivo contém tudo o que você precisa para instalar o sistema na Hostinger (ou qualquer hospedagem compartilhada com PHP/MySQL).

> **Atenção:** Se você baixou o código fonte completo do GitHub, procure por este arquivo ZIP na raiz do projeto.

## 2. Passo a Passo para Hostinger

1.  **Acesse o Gerenciador de Arquivos** da sua hospedagem.
2.  Vá para a pasta `public_html` (ou a subpasta onde deseja instalar, ex: `public_html/biblia`).
3.  **Faça o Upload** do arquivo `BIBLIA_EM_CONTEXTO_V5_FINAL.zip`.
4.  **Extraia (Unzip)** o arquivo.
    *   O conteúdo será extraído para uma pasta. Mova o conteúdo de dentro dessa pasta para a raiz se desejar.
5.  **Banco de Dados:**
    *   Crie um novo Banco de Dados MySQL na Hostinger.
    *   Abra o phpMyAdmin.
    *   Importe o arquivo `database.sql` (incluso no zip).
    *   **Importante:** Se você tiver o arquivo das 13 versões da Bíblia, importe-o também (certifique-se de remover o comando `CREATE DATABASE` dele antes).
6.  **Configuração:**
    *   Edite o arquivo `api/config.php` com os dados do seu banco de dados (Host, Usuário, Senha, Nome do Banco).

## 3. Verificação

Acesse o endereço da sua instalação (ex: `seu-site.com` ou `seu-site.com/biblia`).
O sistema deverá carregar automaticamente.

---
**Observação:** Esta versão (V5 FINAL) inclui todas as correções de rota, navegação e segurança para hospedagem compartilhada.
