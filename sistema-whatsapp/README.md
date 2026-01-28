# Sistema de Atendimento WhatsApp (PHP + MySQL)

Este é um sistema simples de atendimento via WhatsApp utilizando a API Oficial da Meta (Cloud API).
Ele foi projetado para rodar em hospedagens compartilhadas (cPanel, Hostgator, Hostinger, etc).

## Requisitos da Hospedagem
- PHP 7.4 ou superior.
- Banco de Dados MySQL.
- Suporte a HTTPS (SSL) - Obrigatório para o Webhook do WhatsApp.

## Configuração do WhatsApp (Meta)
1. Crie uma conta em [developers.facebook.com](https://developers.facebook.com/).
2. Crie um aplicativo do tipo "Empresa" (Business).
3. Adicione o produto "WhatsApp".
4. Configure um número de telefone (pode usar um de teste inicialmente).
5. Obtenha o **Token de Acesso Permanente** (ou temporário para testes) e o **ID do Número de Telefone**.

## Instalação
1. **Banco de Dados:**
   - Crie um banco de dados na sua hospedagem.
   - Importe o arquivo `database.sql` (via PHPMyAdmin).

2. **Arquivos:**
   - Faça upload de todos os arquivos desta pasta para sua hospedagem (ex: pasta `public_html/whatsapp`).

3. **Configuração:**
   - Edite o arquivo `config.php`:
     - Coloque os dados do banco de dados (Host, User, Pass, DB Name).
     - Coloque o Token do WhatsApp e o ID do Telefone.
     - Defina uma senha para o painel administrativo.
     - Defina um `WEBHOOK_VERIFY_TOKEN` (uma senha que você inventar, ex: `minha_senha_segura`).

4. **Conectar Webhook:**
   - No painel da Meta (Facebook Developers), vá em WhatsApp > Configuração.
   - Em "URL de retorno de chamada" (Webhook), coloque o link do seu site:
     `https://seusite.com/whatsapp/webhook.php`
   - Em "Token de verificação", coloque a senha que você definiu no `config.php`.
   - Clique em verificar.
   - Em "Campos do Webhook", inscreva-se em: `messages`.

## Como Usar
- Acesse `https://seusite.com/whatsapp/` para entrar no painel.
- Faça login com o usuário e senha definidos no banco de dados (tabela `admins`).
- Quando um cliente enviar mensagem, aparecerá na lista.

## Cronjob (Lembretes)
Para que o sistema verifique conversas paradas, configure um Cronjob no seu cPanel:
`*/5 * * * * php /home/seu_usuario/public_html/whatsapp/cron.php`
