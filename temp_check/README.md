# O Guia Metropolitano - Guia Comercial Regional

## Requisitos
- PHP 8.0 ou superior
- MySQL 5.7 ou superior (ou MariaDB)
- Apache (com mod_rewrite habilitado)
- Hospedagem compartilhada (ex: Hostinger)

## Instalação

1. **Upload de Arquivos:**
   - Faça o upload de todo o conteúdo desta pasta (`ogm/`) para o diretório raiz da sua hospedagem (`public_html` ou uma subpasta).
   - A pasta `public/` contém o `index.php` e os arquivos públicos (imagens, CSS, JS). Configure seu domínio para apontar para a pasta `public/` se possível, ou acesse `seu-dominio.com/public`.

2. **Banco de Dados:**
   - Crie um banco de dados MySQL no painel da sua hospedagem.
   - Importe o arquivo `import_data.sql` para criar as tabelas e inserir os dados iniciais.
   - O arquivo `database.sql` contém apenas a estrutura se preferir começar do zero.

3. **Configuração:**
   - Abra o arquivo `config/config.php`.
   - Configure as credenciais do banco de dados:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'seu_banco_de_dados');
     define('DB_USER', 'seu_usuario');
     define('DB_PASS', 'sua_senha');
     ```
   - Para ativar a IA (Opcional):
     - Obtenha uma chave de API do Google Gemini.
     - Adicione a chave em `config/config.php` na constante `GEMINI_API_KEY`.

4. **Permissões:**
   - Certifique-se de que as pastas `app/`, `config/` e `views/` não estejam acessíveis publicamente (o `.htaccess` na raiz deve cuidar disso se configurado corretamente, mas o ideal é que o DocumentRoot do servidor seja a pasta `public/`).

## Estrutura de Pastas

- `app/`: Contém a lógica do sistema (Models, Controllers, Core).
- `config/`: Arquivos de configuração.
- `public/`: Arquivos acessíveis via web (index.php, imagens, JS, CSS).
- `views/`: Arquivos de template HTML/PHP.
- `tests/`: Scripts de verificação e testes automatizados.

## Funcionalidades Principais

- **Busca:** Por nome, categoria ou bairro.
- **Página da Empresa:** Detalhes completos, mapa interativo, horário, avaliações.
- **IA Integrada:** Widget de chat que responde perguntas sobre as empresas cadastradas usando a API do Google Gemini.
- **Imagem Dinâmica:** Gera imagens de capa com o nome da empresa automaticamente (`public/image.php`).

## Desenvolvimento

- Para testar localmente, você pode usar o servidor embutido do PHP na pasta `public/`:
  ```bash
  cd public
  php -S localhost:8000
  ```
- Os testes automatizados (Playwright) estão na pasta `tests/`.
