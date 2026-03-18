# 🕵️‍♂️ Curador Oculto - Faro de Ouro

O **Curador Oculto** (Buscador ML) é uma ferramenta secreta, separada do seu WordPress principal, projetada para ficar em um subdomínio (ex: `buscador.farodeouro.com.br`) ou numa subpasta.

Ele permite que você pesquise produtos no **Mercado Livre** rapidamente e, com apenas um clique, crie um "Produto Externo/Afiliado" automaticamente no seu site WordPress (Faro de Ouro).

---

## 🛠️ Instalação na Hostinger (Passo a Passo)

1. **Subir os Arquivos:**
   - Acesse o painel da sua hospedagem na Hostinger (hPanel).
   - Vá no **Gerenciador de Arquivos**.
   - Crie a pasta `buscador` dentro do `public_html` do seu site principal (ou aponte um subdomínio para uma nova pasta).
   - Extraia o conteúdo deste arquivo `buscador-ml.zip` dentro dessa pasta.

2. **Gerar as Chaves do WooCommerce:**
   - Acesse o painel de administrador do seu site WordPress (Faro de Ouro).
   - Vá em **WooCommerce > Configurações**.
   - Clique na aba **Avançado**, e depois em **API REST**.
   - Clique em **Adicionar chave**.
   - Dê um nome (ex: "Buscador Oculto"), escolha seu usuário, e em Permissões, coloque **Ler/Escrever**.
   - Clique em **Gerar chave de API**.
   - *Atenção:* Copie as duas chaves que aparecerem na tela, pois elas sumirão depois.

3. **Configurar o Buscador:**
   - Volte para o Gerenciador de Arquivos da Hostinger.
   - Edite o arquivo `config.php` que está dentro da pasta do buscador.
   - Altere `APP_PASSWORD` para a senha secreta que você quer usar para acessar a ferramenta.
   - Em `WC_CONSUMER_KEY`, cole a primeira chave gerada no passo anterior (começa com `ck_`).
   - Em `WC_CONSUMER_SECRET`, cole a segunda chave (começa com `cs_`).
   - Salve o arquivo.

---

## 🚀 Como Usar

1. Acesse `https://farodeouro.com.br/buscador` (ou a URL onde você instalou).
2. Digite a senha secreta que você definiu no `config.php`.
3. Digite o que você quer buscar (ex: "Alexa", "Tênis Nike").
4. Nos resultados:
   - Você pode clicar em **Copiar Link Puro** se quiser apenas pegar o link para jogar num grupo de WhatsApp ou postagem manual.
   - Ou clicar em **Importar para Site** para o sistema enviar a foto, título e preço direto para o WooCommerce.

### ⚠️ Importante sobre a Importação
Quando você clica em "Importar para Site", o produto é enviado para o seu WooCommerce e salvo como **Rascunho**.

Fizemos isso por segurança, para que você possa:
1. Ir no seu WordPress.
2. Entrar em Produtos > Rascunhos.
3. Adicionar o seu link de afiliado oficial (com sua tag de rastreamento).
4. Clicar em **Publicar**.