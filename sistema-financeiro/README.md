# Sistema Financeiro SaaS

Sistema de controle financeiro multi-usuário com arquitetura híbrida (React Frontend + PHP Backend).

## Estrutura

*   `frontend/`: Código fonte da interface (React, Vite, Tailwind).
*   `api/`: Backend PHP (REST API).
*   `database.sql`: Script de criação das tabelas MySQL.
*   `dist/`: (Gerado após build) Arquivos prontos para produção.

## Funcionalidades

*   Cadastro e Login (JWT).
*   Dashboard com Resumo Financeiro.
*   Gráfico de Receitas vs Despesas.
*   Listagem, Adição e Remoção de Transações.
*   Proteção básica contra cópias (Obfuscação e User-Agent Block).

## Como Rodar Localmente

1.  **Backend**:
    *   Tenha PHP e MySQL instalados.
    *   Importe `database.sql`.
    *   Configure `api/config.php` (DB_HOST='localhost', etc).
    *   Inicie o servidor PHP: `php -S localhost:8000 -t .` na raiz do projeto.

2.  **Frontend**:
    *   `cd frontend`
    *   `pnpm install`
    *   `pnpm dev`
    *   Acesse `http://localhost:5173`.

## Deploy

Veja o arquivo [DEPLOY_HOSTINGER.md](./DEPLOY_HOSTINGER.md) para instruções detalhadas.
