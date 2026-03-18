# 🕵️‍♂️ Curador Oculto - Faro de Ouro (Versão Autenticada)

O **Curador Oculto** (Buscador ML) é uma ferramenta secreta que permite pesquisar produtos no Mercado Livre rapidamente e criar "Produtos Externos/Afiliados" automaticamente no seu site WordPress (Faro de Ouro).

Esta nova versão integra-se oficialmente com a **API do Mercado Livre**, o que resolve definitivamente qualquer problema de bloqueio de IP ("Erro 403 Forbidden") por parte da sua hospedagem.

---

## 🛠️ Instalação na Hostinger (Passo a Passo)

### 1. Subir os Arquivos
1. Acesse o painel da sua hospedagem na Hostinger (hPanel).
2. Vá no **Gerenciador de Arquivos**.
3. Crie a pasta `buscador` dentro do `public_html` do seu site principal.
4. Extraia o conteúdo deste arquivo `buscador-ml.zip` dentro dessa pasta.

### 2. Gerar as Chaves do WooCommerce
1. Acesse o painel de administrador do seu site WordPress (Faro de Ouro).
2. Vá em **WooCommerce > Configurações > Avançado > API REST**.
3. Clique em **Adicionar chave** (Nome: "Buscador Oculto", Permissões: **Ler/Escrever**).
4. Clique em **Gerar chave de API** e deixe essa tela aberta para copiar as chaves no próximo passo.

### 3. Criar Aplicativo no Mercado Livre
Para que o buscador não seja bloqueado, ele precisa de uma credencial oficial. É grátis e leva apenas 1 minuto:
1. Acesse o portal de desenvolvedores do Mercado Livre: [https://developers.mercadolivre.com.br/devcenter/](https://developers.mercadolivre.com.br/devcenter/)
2. Faça login com a sua conta do Mercado Livre.
3. Clique em **Criar Novo Aplicativo**.
4. Preencha os dados:
   - **Nome:** Curador Faro de Ouro
   - **URI de redirecionamento:** Coloque a URL exata do arquivo `auth.php`. *(Ex: `https://farodeouro.com.br/buscador/auth.php`)*
   - **Escopos:** Selecione "Ler" (Read) e "Acesso Offline" (Offline_Access).
5. Salve o aplicativo. Na tela seguinte, você verá o seu **APP_ID** e **SECRET_KEY**.

### 4. Configurar o Buscador
1. Volte para o Gerenciador de Arquivos da Hostinger.
2. Edite o arquivo `config.php` que está dentro da pasta do buscador.
3. Altere `APP_PASSWORD` para a senha secreta que você quer usar para acessar a ferramenta.
4. Preencha as chaves do WooCommerce (`WC_CONSUMER_KEY` e `WC_CONSUMER_SECRET`).
5. Preencha as chaves do Mercado Livre (`ML_APP_ID`, `ML_SECRET_KEY` e o `ML_REDIRECT_URI` exatamente igual ao que você cadastrou no passo anterior).
6. Salve o arquivo.

---

## 🔐 Como Autorizar e Usar

1. Acesse `https://farodeouro.com.br/buscador` no seu navegador.
2. Digite a senha secreta do sistema.
3. Como é a sua primeira vez, você não conseguirá pesquisar nada ainda. O sistema pedirá para você **Autorizar**.
4. Clique no botão de Autorizar. Você será levado ao Mercado Livre, onde deve clicar em "Permitir".
5. O Mercado Livre devolverá você para a ferramenta e mostrará uma mensagem de "Sucesso!".
6. A partir de agora, as chaves estão salvas. Volte para a pesquisa, digite o que quiser (Ex: "iPhone") e comece a importar produtos com 1 clique!

### ⚠️ Sobre a Importação
Quando você clica em "Importar para Site", o produto é enviado para o seu WooCommerce e salvo como **Rascunho**.

Vá no seu WordPress > Produtos > Rascunhos, adicione o seu link de afiliado oficial no campo do botão, e publique!