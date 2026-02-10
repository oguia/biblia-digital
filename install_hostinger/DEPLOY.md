# Guia de Implantação - Mais Deus (Bible Happening Now)

Este projeto foi desenvolvido para funcionar em hospedagem compartilhada (como Hostinger) com PHP e MySQL.

## Estrutura do Projeto
O build gera a seguinte estrutura em `dist/public/`:
- `index.html` e assets (Frontend React)
- `api/` (Backend PHP)

## Passos para Implantação

### 1. Banco de Dados
1. Crie um novo banco de dados MySQL no painel da Hostinger.
2. Importe o arquivo `database.sql` (encontrado na raiz do projeto) para criar a estrutura das tabelas.
3. (Opcional) Importe o arquivo `api/seed_data.sql` para popular com dados de exemplo (Gênesis 1).

### 2. Configuração do Backend
1. Edite o arquivo `api/config.php` antes de enviar (ou no servidor):
   - Atualize `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` com suas credenciais.

### 3. Upload dos Arquivos
1. Faça o upload de todo o conteúdo da pasta `dist/public/` para a pasta `public_html` (ou subdiretório) do seu servidor.
   - Certifique-se de que a pasta `api` esteja acessível em `seu-dominio.com/api/`.

### 4. Configuração do Frontend (Produção)
1. O frontend React espera que a API esteja em `/api/`. Se você colocar em outro lugar, precisará ajustar.
2. Para desativar o modo de teste (Mock Data) e usar o PHP real:
   - Edite o arquivo `src/lib/api.ts` antes de fazer o build final.
   - Mude `const USE_MOCK = true;` para `false`.
   - Reconstrua o projeto com `pnpm build`.

## Arquivos Importantes
- `database.sql`: Schema do banco de dados.
- `api/seed_data.sql`: Dados iniciais para Gênesis 1.
- `api/config.php`: Configuração de conexão com o banco.
