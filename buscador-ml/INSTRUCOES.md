# 🕵️‍♂️ Curador Oculto - Faro de Ouro

O **Curador Oculto** (Buscador ML) é uma ferramenta secreta que permite pesquisar produtos no Mercado Livre rapidamente e criar "Produtos Externos/Afiliados" automaticamente no seu site WordPress (Faro de Ouro).

Esta ferramenta funciona via **Web Scraping** (Extração de Dados), o que significa que ela burla o sistema de chaves e bloqueios do Mercado Livre para garantir que você sempre consiga pesquisar e puxar produtos sem burocracia ou erros "403 Forbidden".

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

### 3. Configurar o Buscador
1. Volte para o Gerenciador de Arquivos da Hostinger.
2. Edite o arquivo `config.php` que está dentro da pasta do buscador.
3. Altere `APP_PASSWORD` para a senha secreta que você quer usar para acessar a ferramenta.
4. Preencha as chaves do WooCommerce (`WC_CONSUMER_KEY` e `WC_CONSUMER_SECRET`) geradas no passo anterior.
5. Salve o arquivo.

---

## 🚀 Como Usar

1. Acesse `https://farodeouro.com.br/buscador` no seu navegador.
2. Digite a senha secreta do sistema que você definiu (`curador123` por padrão).
3. Digite o que você quer buscar (Ex: "Alexa", "Tênis Nike") e clique em **Buscar no ML**.
4. Nos resultados:
   - Você pode clicar em **Copiar Link Puro** se quiser apenas pegar o link para jogar num grupo de WhatsApp ou postagem manual.
   - Ou clicar em **Importar para Site** para o sistema enviar a foto, título e preço direto para o WooCommerce.

### ⚠️ Sobre a Importação
Quando você clica em "Importar para Site", o produto é enviado para o seu WooCommerce e salvo como **Rascunho**.

Vá no seu WordPress > Produtos > Rascunhos, adicione o seu link de afiliado oficial no campo do botão, e clique em **Publicar**!