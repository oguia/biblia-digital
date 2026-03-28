# Controle de Almoxarifado

Sistema web completo para controle de estoque, movimentações e geração de etiquetas, desenvolvido em PHP 8.

## Funcionalidades

*   **Autenticação Segura:** Login com proteção de sessão e hash de senha.
*   **Gestão de Produtos:** Cadastro completo com categorização e controle de estoque mínimo.
*   **Movimentações:** Registro de Entradas e Saídas com atualização automática de estoque.
*   **Dashboard:** Visão geral com estatísticas e gráfico de movimentações (Entradas x Saídas).
*   **Importação/Exportação:** Importação via Excel (.xlsx, .csv) e Exportação de relatórios.
*   **Etiquetas:** Geração de etiquetas com código de barras (Code 128) para impressoras térmicas (10x5cm) ou PDF A4.
*   **Segurança:** Proteção contra SQL Injection, CSRF e acesso não autorizado.

## Requisitos

*   PHP 8.0 ou superior
*   MySQL / MariaDB
*   Composer (para gerenciamento de dependências)

## Estrutura de Pastas

*   `app/`: Código fonte da aplicação (Controllers, Models, Views).
*   `config/`: Arquivos de configuração.
*   `public/`: Arquivos públicos (CSS, JS, Uploads).
*   `vendor/`: Dependências (gerado pelo Composer).
*   `index.php`: Ponto de entrada.

## Instalação (Hostinger / Hospedagem Compartilhada)

### 1. Upload dos Arquivos
Faça o upload de todos os arquivos para a pasta desejada (ex: `public_html/almoxarifado`).

### 2. Banco de Dados
1.  Crie um banco de dados MySQL no painel da hospedagem.
2.  Importe o arquivo `database.sql` via phpMyAdmin.

### 3. Configuração
1.  Abra o arquivo `config/config.php`.
2.  Edite as constantes de conexão com o banco de dados:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'seu_banco');
    define('DB_USER', 'seu_usuario');
    define('DB_PASS', 'sua_senha');
    ```
3.  Ajuste a `APP_URL` para o endereço do seu sistema.

### 4. Instalar Dependências
No terminal (SSH) ou localmente antes de subir:
```bash
composer install
```
Se não tiver acesso SSH, instale o composer localmente na sua máquina, gere a pasta `vendor` e faça o upload dela completa.

### 5. Permissões
Garanta que a pasta `public/uploads` tenha permissão de escrita (755 ou 777 se necessário).

## Usuário Padrão

*   **Email:** admin@sistema.com
*   **Senha:** admin123

---
Desenvolvido com PHP Puro (MVC), Bootstrap 5 e MySQL.
