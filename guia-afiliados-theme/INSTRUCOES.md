# 📘 Guia Afiliados Theme - Instruções de Uso (Premium)

Bem-vindo ao tema **Guia Afiliados**. Este é um tema filho (*Child Theme*) desenvolvido para o WordPress focado em conversão de links de afiliados, seguindo a nova identidade visual "Conceito 2: O Guia Premium" (Azul Marinho Real e Ouro Velho).

---

## 🛠️ 1. Como Instalar o Tema

Este tema funciona em conjunto com o tema base gratuito **Hello Elementor**. Siga os passos abaixo:

1. No painel do WordPress, vá em **Aparência > Temas**.
2. Clique em **Adicionar novo** e pesquise por `Hello Elementor`. Instale e ative o tema pai.
3. Agora, clique novamente em **Adicionar novo**, e depois no botão **Enviar tema**.
4. Faça o upload do arquivo `guia-afiliados-theme.zip` que você baixou.
5. Clique em **Instalar Agora** e, após concluir, clique em **Ativar**.

*Seu site já está com a base visual "Premium" aplicada (cores, fontes Playfair Display e botões elegantes).*

---

## 🧩 2. Plugins Essenciais (Para o visual funcionar 100%)

Para ter o cabeçalho idêntico ao planejado, instale os seguintes plugins gratuitos no repositório do WordPress:

*   **Elementor (Free):** O construtor principal de páginas.
*   **WooCommerce:** Para o catálogo de produtos e botões de afiliados.
*   **Elementor Header & Footer Builder:** Permite que você crie o cabeçalho (Header) Azul Marinho Real fixo usando o Elementor Grátis.
*   **FiboSearch (AJAX Search for WooCommerce):** Substitui a busca padrão do WooCommerce por uma busca "Live" elegante e rápida.

### Configurando o Cabeçalho (Header)
1. Após instalar o *Elementor Header & Footer Builder*, vá em **Aparência > Elementor Header & Footer Builder**.
2. Clique em **Add New**, dê o nome "Header Principal" e defina *Type of Template* como **Header** e *Display on* como **Entire Website**.
3. Edite com Elementor.
4. Crie uma seção com 3 colunas.
5. Na primeira coluna, adicione o widget de Imagem para a sua **Logo** (`logo.png` que está no arquivo zip).
6. Na coluna central, adicione o widget do **FiboSearch** (ou use um widget de Shortcode com `[fibosearch]`).
7. Vá nas configurações da **Seção Principal** > aba **Avançado** > e coloque o campo *Z-Index* como `999`. A cor de fundo (`background-color`) e o `position: sticky` (para prender no topo) já são injetadas automaticamente pelo CSS do tema, caso a classe `.elementor-location-header` esteja ativa (ou você pode aplicar a cor primária #1A2B3C manualmente na seção pelo Elementor).

---

## 🚀 3. Como Importar Produtos (Mercado Livre e Amazon) Automaticamente

O WooCommerce nativo não permite "puxar" os dados automaticamente colando apenas o link. Para criar o seu portal de forma ágil:

### Opção A: Extensões do Chrome + Plugins (Grátis / Freemium)
*   **AliDropship / AliPlugin (ou equivalentes para ML/Amazon):** Permitem que você visite a página do produto no Mercado Livre ou Amazon, clique em um botão na extensão do Chrome, e ele preencha a ficha do produto no seu WooCommerce automaticamente.
*   **Content Egg (Versão Grátis):** Um plugin excelente. Ele adiciona um metabox na página do produto onde você pode buscar itens na Amazon ou Mercado Livre via API ou links e ele importa título, imagem e preço.

### Opção B: Plugins Profissionais (Recomendado se for o negócio principal)
*   **WooCommerce Amazon Affiliates (WZone):** É o mais famoso do mercado para a Amazon. Você digita uma palavra-chave (ex: "Celular Samsung") no seu próprio WordPress, e ele importa centenas de produtos da Amazon já com seu link de afiliado, atualizando os preços automaticamente todos os dias.
*   **WP All Import:** Permite que você importe planilhas (CSV/XML) gigantes com milhares de produtos de redes de afiliados de uma só vez para dentro do WooCommerce como "Produtos Externos/Afiliados".

---

## ⭐ 4. Usando o Selo "Escolha do Guia"

Para que a tag de estrela elegante "Escolha do Guia" apareça em cima de um produto:

1. Vá em **Produtos > Todos os Produtos** no menu do WooCommerce.
2. Na lista de produtos, você verá uma coluna com uma **Estrela** vazia.
3. Clique na estrela ao lado do produto que você deseja destacar. A estrela ficará preenchida em azul.
4. O selo *"Escolha do Guia"* (configurado no `functions.php`) aparecerá instantaneamente naquele produto no site, usando a cor Ouro Velho.

---

## 💡 5. Dicas do "Curador Premium"

*   **Links de Afiliado:** Sempre cadastre os produtos como **"Produto Externo/Afiliado"** no WooCommerce, inserindo o link da Amazon/ML e colocando o texto do botão como "Comprar Agora" ou "Ver Oferta".
*   **Preços:** O tema usa o **Azul Marinho Real (#1A2B3C)** com a fonte Playfair Display para o preço em destaque, garantindo elegância e autoridade.
*   **Imagens:** O CSS foi configurado com `object-fit: contain;`, garantindo que produtos altos ou compridos nunca sejam cortados nos cards (bordas mais quadradas e sombras suaves para um ar luxuoso).
*   **Logo e Favicon:** O tema acompanha os arquivos `logo.png` e `favicon.png` que você forneceu. Faça o upload deles em **Aparência > Personalizar > Identidade do Site**.
