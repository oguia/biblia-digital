# 🤖 Curador AI - Gerador de Blog

O **Gerador de Blog** (Curador AI) é um "laboratório secreto" projetado para pegar os produtos que você importou para o WooCommerce e usar a Inteligência Artificial do Google Gemini para escrever artigos de blog 100% persuasivos e automáticos sobre eles.

Aumentar o número de artigos no seu blog ajuda muito no **SEO (Busca no Google)** e atrai clientes que estão pesquisando sobre os benefícios dos produtos.

---

## 🛠️ Instalação na Hostinger (Passo a Passo)

### 1. Subir os Arquivos
1. Acesse o painel da sua hospedagem na Hostinger (hPanel).
2. Vá no **Gerenciador de Arquivos**.
3. Crie a pasta `meublog` dentro do `public_html` do seu site principal.
4. Extraia o conteúdo deste arquivo `gerador-blog.zip` dentro dessa pasta.

### 2. Copiar as Chaves do WooCommerce
Se você já configurou o `Buscador ML`, basta copiar as chaves `WC_CONSUMER_KEY` e `WC_CONSUMER_SECRET` que você já tem para o arquivo `config.php` da pasta `meublog`. Elas servem para o gerador conseguir ler os produtos da sua loja.

### 3. Criar uma "Senha de Aplicativo" do WordPress
Para o nosso sistema conseguir criar posts automaticamente na aba de "Posts" do seu WordPress, ele precisa de uma credencial especial.
1. No painel de administrador do WordPress, vá em **Usuários > Perfil**.
2. Role até o final da página e encontre a seção **"Senhas de Aplicativo"**.
3. Digite "Gerador de Blog" no nome e clique em **Adicionar nova senha de aplicativo**.
4. O WordPress vai gerar uma senha enorme cheia de espaços (ex: `abcd efgh ijkl mnop`). Copie-a.
5. Cole essa senha no campo `WP_APP_PASSWORD` do `config.php`. Em `WP_ADMIN_USERNAME`, coloque o nome de usuário (login) desse administrador.

### 4. Pegar a Chave Gratuita do Google Gemini (IA)
1. Acesse o **Google AI Studio**: [https://aistudio.google.com/app/apikey](https://aistudio.google.com/app/apikey)
2. Faça login com qualquer conta do Gmail.
3. Clique no botão azul **"Create API Key"**.
4. Copie a chave gerada (ela começa com `AIzaSy...`).
5. Cole no campo `GEMINI_API_KEY` do arquivo `config.php`.

---

## 🚀 Como Usar

1. Acesse `https://farodeouro.com.br/meublog`.
2. Entre com a senha do painel (`curador123`).
3. O painel esquerdo listará todos os produtos da sua loja. Se for um rascunho sem foto, ele aparecerá com um ícone vazio, mas funciona mesmo assim.
4. **Selecione um Produto** clicando nele.
5. Clique no botão laranja **1. Escrever Artigo Mágico**. O Gemini vai pensar por uns 15 segundos.
6. A tela direita será preenchida com um título sensacionalista e um texto focado em converter o visitante em comprador, já com a foto do produto e o seu link de afiliado no final.
7. **Leia e edite o texto se quiser!** (A caixinha de texto funciona igual o Word).
8. Clique em **2. Enviar Rascunho pro Blog**.
9. Pronto! O texto foi enviado para o menu **Posts** do seu WordPress. Entre lá, escolha as categorias e publique!