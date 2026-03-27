INSTRUÇÕES DE INSTALAÇÃO - AFINADOR MAIS DEUS (HOSTINGER)

Este projeto foi desenvolvido para rodar perfeitamente na hospedagem compartilhada da Hostinger. Siga os passos abaixo para colocar o site no ar.

1. PREPARAÇÃO DOS ARQUIVOS
   - Você receberá uma pasta chamada "afinador".
   - Dentro dela estão todos os arquivos necessários (index.php, css, js, includes, etc).

2. BANCO DE DADOS
   - Acesse o painel da Hostinger (hPanel).
   - Vá em "Banco de Dados" -> "Gerenciamento de MySQL".
   - Crie um novo banco de dados e anote o NOME DO BANCO, USUÁRIO e SENHA.
   - Entre no phpMyAdmin do banco recém-criado.
   - Clique na aba "Importar" e selecione o arquivo "afinador/database.sql". Execute para criar as tabelas.

3. CONFIGURAÇÃO DO SITE
   - Abra o arquivo "afinador/includes/db.php" em um editor de texto (Bloco de Notas ou VS Code).
   - Altere as seguintes linhas com os dados do seu banco de dados:
     $host = 'localhost';
     $dbname = 'u123456789_nomedobanco'; // Coloque o nome do banco aqui
     $username = 'u123456789_usuario'; // Coloque o usuário aqui
     $password = 'SuaSenhaForte'; // Coloque a senha aqui

   - Abra o arquivo "afinador/includes/config.php".
   - Altere a URL do site para o endereço final onde você vai instalar (ex: https://masideus.com/afinador).
     define('APP_URL', 'https://seu-dominio.com/afinador');

   - Configure o Mercado Pago:
     - Obtenha seu ACCESS TOKEN de produção no painel do Mercado Pago Developers.
     - Cole em: define('MP_ACCESS_TOKEN', 'SEU_ACCESS_TOKEN_AQUI');
     - Cole sua Public Key em: define('MP_PUBLIC_KEY', 'SUA_PUBLIC_KEY_AQUI');

4. UPLOAD PARA A HOSTINGER
   - Acesse o "Gerenciador de Arquivos" da Hostinger.
   - Navegue até a pasta "public_html".
   - Crie uma pasta chamada "afinador" (ou outro nome que preferir).
   - Faça o upload de todos os arquivos e pastas de dentro da pasta "afinador" do seu computador para dentro desta pasta na hospedagem.

5. TESTES FINAIS
   - Acesse https://seu-dominio.com/afinador
   - Crie uma conta de teste.
   - Teste o afinador (permita o uso do microfone).
   - Tente acessar a busca de cifras (deve pedir upgrade).
   - Simule um pagamento (se estiver em modo de teste do Mercado Pago) ou use um valor baixo real.

OBSERVAÇÕES IMPORTANTES:
- O afinador precisa de HTTPS (cadeado verde) para funcionar o microfone no navegador. A Hostinger oferece SSL gratuito, certifique-se de ativá-lo.
- A busca de cifras depende da estrutura do site Cifra Club. Se eles mudarem o site drasticamente, a busca pode precisar de ajustes no arquivo "includes/scraper.php".
