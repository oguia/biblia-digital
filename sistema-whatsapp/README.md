# Sistema WhatsApp Multi-Instância (Node.js + PHP)

Este projeto permite gerenciar múltiplos números de WhatsApp utilizando a tecnologia de leitura de QR Code.

## Arquitetura
- **Server (Backend):** Node.js + Baileys. Conecta ao WhatsApp e salva mensagens.
- **Public (Frontend):** PHP. Painel de gerenciamento.

## Segurança
- O sistema usa um Token de API (`minha_senha_secreta_api`) para proteger a comunicação entre PHP e Node.js.
- **MUITO IMPORTANTE:** Altere esse token em `server/server.js` e `public/config.php` antes de usar em produção.

## Instalação no cPanel (Hospedagem Compartilhada)

### 1. Banco de Dados
1. Crie um banco de dados MySQL.
2. Importe o arquivo `database.sql`.
3. Edite `server/server.js` e `public/config.php` com os dados de conexão.

### 2. Node.js
Você tem duas opções para rodar o Node.js:

**Opção A: "Setup Node.js App" (Recomendado se disponível)**
Esta opção mantém o sistema rodando 24h automaticamente.
1. No cPanel, vá em **Setup Node.js App**.
2. Crie app apontando para `server/server.js`.
3. Instale as dependências (`npm install`) via terminal.
4. Clique em **Start App**.
5. *Nota:* Se usar essa opção, ignore os arquivos `start_server.php` e `stop_server.php`.

**Opção B: Cronjobs (Gambiarra para Economia)**
Use esta opção se o seu host derruba processos ou se você quer que o sistema funcione apenas em horário comercial.
1. Configure um Cronjob para rodar `start_server.php` de manhã (ex: 08:00).
   `php /caminho/para/sistema-whatsapp/start_server.php`
2. Configure um Cronjob para rodar `stop_server.php` à noite (ex: 18:00).
   `php /caminho/para/sistema-whatsapp/stop_server.php`

### 3. Lembretes de Atraso
Para receber emails quando um cliente fica sem resposta, configure um Cronjob a cada 10 minutos:
`*/10 * * * * php /caminho/para/sistema-whatsapp/cron_reminders.php`

## Uso
1. Acesse `seusite.com/sistema-whatsapp/public/`.
2. Login: `admin@admin.com` / `123456`.
3. Crie uma Instância e escaneie o QR Code.
