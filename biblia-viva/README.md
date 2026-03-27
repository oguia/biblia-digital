# Bíblia Viva: A Bíblia em Contexto

Este projeto é um braço do portal maisdeus.com, focado em oferecer uma experiência de leitura bíblica enriquecida com contexto geográfico (mapas) e histórico (cronologia).

## Estrutura do Projeto

O projeto foi estruturado para ser leve e performático em hospedagem compartilhada (Hostinger), utilizando PHP puro e MySQL.

- **`index.php`**: Ponto de entrada da aplicação. Gerencia as rotas e renderiza as views.
- **`config.php`**: Configurações de banco de dados e ambiente.
- **`includes/`**: Arquivos PHP reutilizáveis (conexão com banco, funções auxiliares).
- **`views/`**: Componentes visuais (leitor, mapa, timeline).
- **`assets/`**: Recursos estáticos (CSS, JS, imagens).
- **`sql/`**: Scripts SQL para criação das tabelas e inserção de dados.

## Requisitos

- PHP 7.4+
- MySQL 5.7+
- Extensões PHP: PDO, PDO_MySQL

## Instalação

1. Importe o arquivo `sql/schema_safe.sql` (Recomendado para evitar erros de Chave Estrangeira) e `sql/data_genesis_12.sql` no seu banco de dados MySQL.
2. Renomeie o arquivo `config.example.php` para `config.php`.
3. Edite o arquivo `config.php` com as credenciais do seu banco de dados.
4. Faça o upload de todos os arquivos para a pasta `/biblia-viva` no servidor.
