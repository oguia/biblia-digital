<?php
// Acesso: /app/gerador-blog/index.php
session_start();
require_once 'config.php';

// Sistema Simples de Login
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_POST['password'])) {
    if ($_POST['password'] === APP_PASSWORD) {
        $_SESSION['logged_in_blog'] = true;
    } else {
        $error = "Senha Incorreta.";
    }
}

if (!isset($_SESSION['logged_in_blog']) || $_SESSION['logged_in_blog'] !== true) {
    // Tela de Login
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Curador AI - Faro de Ouro</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Open Sans', sans-serif; background-color: #F8F9FA; color: #4A4A4A; }
            h1, h2, h3, h4, h5, h6, .font-serif { font-family: 'Playfair Display', serif; color: #1A2B3C; }
            .bg-primaria { background-color: #1A2B3C; }
            .bg-destaque { background-color: #C19A6B; }
            .text-destaque { color: #C19A6B; }
            .border-destaque { border-color: #C19A6B; }
        </style>
    </head>
    <body class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md border border-gray-100">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold font-serif mb-2">Curador AI</h1>
                <p class="text-sm text-gray-500">Gerador Automático de Artigos para Blog</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2" for="password">Senha do Gerador</label>
                    <input class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-destaque" id="password" name="password" type="password" placeholder="••••••••" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-destaque hover:bg-[#A88152] text-white font-bold py-3 px-4 rounded w-full focus:outline-none focus:shadow-outline transition duration-300" type="submit">
                        Entrar no Laboratório
                    </button>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Generate CSRF Token
if (empty($_SESSION['csrf_token_blog'])) {
    $_SESSION['csrf_token_blog'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token_blog'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Blog AI - Faro de Ouro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Quill.js for Rich Text Editing -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #F8F9FA; color: #4A4A4A; }
        h1, h2, h3, h4, h5, h6, .font-serif { font-family: 'Playfair Display', serif; color: #1A2B3C; }
        .bg-primaria { background-color: #1A2B3C; }
        .bg-destaque { background-color: #C19A6B; }
        .text-primaria { color: #1A2B3C; }
        .text-destaque { color: #C19A6B; }
        .border-destaque { border-color: #C19A6B; }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #C19A6B;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Layout Grid para a tela dividida */
        .app-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        @media (max-width: 1024px) { .app-grid { grid-template-columns: 1fr; } }

        /* Editor de Texto Alto */
        #editor-container { height: 500px; background: white; font-family: 'Open Sans'; font-size: 16px; line-height: 1.6;}

        .product-card.selected { border: 2px solid #C19A6B; background-color: #FFFDF8; }
    </style>
</head>
<body class="bg-[#F8F9FA]">
    <input type="hidden" id="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Navbar -->
    <nav class="bg-primaria p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-white font-serif text-xl font-bold flex items-center gap-2">
                <i class="fas fa-robot text-destaque"></i>
                Curador AI: Blog Automático
            </div>
            <a href="?logout=1" class="text-white hover:text-destaque text-sm font-semibold transition duration-200">
                <i class="fas fa-sign-out-alt mr-1"></i> Sair
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4 mt-6">

        <div id="alertArea" class="mb-4"></div>

        <div class="app-grid">
            <!-- Left Column: Product Selection -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col h-[800px]">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center rounded-t-lg">
                    <h2 class="font-serif text-lg text-primaria m-0"><i class="fas fa-box-open mr-2 text-destaque"></i>Seus Produtos (WooCommerce)</h2>
                    <button id="btnRefresh" onclick="fetchProducts()" class="text-gray-500 hover:text-primaria transition" title="Atualizar Lista"><i class="fas fa-sync-alt"></i></button>
                </div>

                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <div class="relative">
                        <input type="text" id="productSearch" placeholder="Filtrar por nome..." class="w-full pl-8 pr-4 py-2 rounded border border-gray-300 focus:outline-none focus:border-destaque text-sm" onkeyup="filterProducts()">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                    </div>
                </div>

                <div id="productsList" class="overflow-y-auto flex-grow p-4 space-y-3 bg-gray-50">
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Carregando seus produtos...
                    </div>
                </div>
            </div>

            <!-- Right Column: AI Generator & Editor -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col h-[800px]">
                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg flex justify-between items-center">
                    <h2 class="font-serif text-lg text-primaria m-0"><i class="fas fa-magic mr-2 text-destaque"></i>Laboratório de Escrita</h2>
                    <div id="aiStatus" class="text-sm font-bold text-gray-400"><i class="fas fa-check-circle"></i> IA Pronta</div>
                </div>

                <!-- Action Bar -->
                <div class="p-4 flex flex-wrap gap-4 items-center bg-white border-b border-gray-100">
                    <button id="btnGenerate" onclick="generateArticle()" class="bg-destaque hover:bg-[#A88152] text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300 flex items-center justify-center gap-2 opacity-50 cursor-not-allowed" disabled>
                        <i class="fas fa-brain"></i> 1. Escrever Artigo Mágico
                    </button>

                    <button id="btnPublish" onclick="publishPost()" class="bg-primaria hover:bg-[#2A445D] text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300 flex items-center justify-center gap-2 opacity-50 cursor-not-allowed" disabled>
                        <i class="fab fa-wordpress"></i> 2. Enviar Rascunho pro Blog
                    </button>
                </div>

                <!-- Title Input -->
                <div class="px-4 pt-4 bg-white">
                    <input type="text" id="postTitle" placeholder="O título do artigo aparecerá aqui (Você pode alterar)..." class="w-full text-xl font-serif font-bold p-2 border-b border-gray-300 focus:outline-none focus:border-destaque mb-2 text-primaria">
                </div>

                <!-- Editor -->
                <div class="flex-grow flex flex-col bg-white">
                    <div id="editor-container"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        const csrfToken = document.getElementById('csrf_token').value;
        const productsList = document.getElementById('productsList');
        const btnGenerate = document.getElementById('btnGenerate');
        const btnPublish = document.getElementById('btnPublish');
        const aiStatus = document.getElementById('aiStatus');
        let quill;
        let selectedProduct = null;
        let allProducts = [];

        // Inicializa o Editor de Texto Rich Text (Quill)
        window.onload = () => {
            quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Selecione um produto na lista ao lado e clique em "Escrever Artigo Mágico" para a IA começar a trabalhar. Ou digite você mesmo...',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });

            // Ativa o botão de publicar se o usuário digitar algo manualmente
            quill.on('text-change', function(delta, oldDelta, source) {
                 if(quill.getText().trim().length > 10 && document.getElementById('postTitle').value.length > 3) {
                      enablePublish();
                 } else if(source === 'user' && quill.getText().trim().length <= 10) {
                      disablePublish();
                 }
            });

            // Monitora o título manual
            document.getElementById('postTitle').addEventListener('input', () => {
                 if(quill.getText().trim().length > 10 && document.getElementById('postTitle').value.length > 3) {
                      enablePublish();
                 } else {
                      disablePublish();
                 }
            });

            fetchProducts();
        };

        // Formatar Moeda (Real Brasileiro)
        const formatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

        function showAlert(message, type = 'blue') {
            const alertArea = document.getElementById('alertArea');
            const colors = {
                'red': 'bg-red-100 border-red-400 text-red-700',
                'green': 'bg-green-100 border-green-400 text-green-700',
                'blue': 'bg-blue-100 border-blue-400 text-blue-700',
                'yellow': 'bg-yellow-100 border-yellow-400 text-yellow-700'
            };
            const theme = colors[type] || colors['blue'];
            alertArea.innerHTML = `<div class="${theme} border-l-4 p-4 mb-4 rounded shadow-sm" role="alert"><div>${message}</div></div>`;
            if(type === 'green') {
                 setTimeout(() => { alertArea.innerHTML = ''; }, 6000);
            }
        }

        function sanitizeHTML(str) {
            var temp = document.createElement('div');
            temp.textContent = str;
            return temp.innerHTML.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        async function fetchProducts() {
            productsList.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500 font-bold"><i class="fas fa-circle-notch fa-spin mr-2"></i> Lendo WooCommerce...</div>';

            try {
                const response = await fetch(`api.php?action=get_products&_t=${new Date().getTime()}`);

                if (!response.ok) throw new Error(`Erro do servidor (${response.status})`);

                const data = await response.json();

                if (data.error) {
                    productsList.innerHTML = `<div class="text-red-500 text-sm p-2 text-center border border-red-200 rounded bg-red-50"><i class="fas fa-exclamation-triangle"></i> ${data.error}</div>`;
                    return;
                }

                allProducts = data.results || [];

                if (allProducts.length === 0) {
                     productsList.innerHTML = '<div class="text-gray-500 text-sm text-center italic p-4">Sua loja não tem produtos (ou são rascunhos). Publique produtos no WooCommerce primeiro.</div>';
                } else {
                     renderProductsList(allProducts);
                }

            } catch (error) {
                productsList.innerHTML = `<div class="text-red-500 text-sm p-2 text-center border border-red-200 rounded bg-red-50"><i class="fas fa-wifi"></i> Falha ao conectar. Veja se o endereço do seu site (WP_URL) no config.php está correto.</div>`;
            }
        }

        function renderProductsList(products) {
            productsList.innerHTML = products.map(product => {
                const imgStr = product.image ? `<img src="${sanitizeHTML(product.image)}" class="w-12 h-12 object-contain rounded bg-white border border-gray-100 p-1 mr-3 flex-shrink-0" onerror="this.style.display='none'">` : `<div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mr-3 flex-shrink-0 text-gray-400"><i class="fas fa-image"></i></div>`;
                const priceStr = formatter.format(product.price);
                const titleStr = sanitizeHTML(product.title);

                return `
                <div class="product-card p-3 rounded-lg border border-gray-200 bg-white cursor-pointer hover:shadow-md transition flex items-center mb-2" onclick="selectProduct(${product.id}, this)" id="prod-${product.id}">
                    ${imgStr}
                    <div class="flex-grow overflow-hidden">
                        <div class="font-semibold text-gray-800 text-sm truncate" title="${titleStr}">${titleStr}</div>
                        <div class="text-xs text-primaria font-bold mt-1">${priceStr}</div>
                    </div>
                </div>
                `;
            }).join('');
        }

        function filterProducts() {
            const query = document.getElementById('productSearch').value.toLowerCase();
            const filtered = allProducts.filter(p => p.title.toLowerCase().includes(query));
            renderProductsList(filtered);

            // Re-aplicar seleção visual se o item ainda estiver na lista filtrada
            if(selectedProduct) {
                const el = document.getElementById(`prod-${selectedProduct.id}`);
                if(el) el.classList.add('selected');
            }
        }

        function selectProduct(id, element) {
            // Remove selection class from all
            document.querySelectorAll('.product-card').forEach(el => el.classList.remove('selected'));

            // Add selection class to clicked
            element.classList.add('selected');

            selectedProduct = allProducts.find(p => p.id === id);

            // Enable Generate Button
            btnGenerate.disabled = false;
            btnGenerate.classList.remove('opacity-50', 'cursor-not-allowed');
            btnGenerate.classList.add('animate-pulse');
            setTimeout(() => btnGenerate.classList.remove('animate-pulse'), 1000);

            // Change AI Status
            aiStatus.innerHTML = '<i class="fas fa-bolt text-destaque"></i> Aguardando sua ordem...';
            aiStatus.className = 'text-sm font-bold text-destaque';
        }

        function enablePublish() {
             btnPublish.disabled = false;
             btnPublish.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function disablePublish() {
             btnPublish.disabled = true;
             btnPublish.classList.add('opacity-50', 'cursor-not-allowed');
        }

        async function generateArticle() {
            if (!selectedProduct) return;

            btnGenerate.innerHTML = '<i class="fas fa-spinner fa-spin"></i> O Gemini está escrevendo...';
            btnGenerate.disabled = true;
            btnGenerate.classList.add('opacity-50', 'cursor-not-allowed');
            disablePublish();

            aiStatus.innerHTML = '<i class="fas fa-brain fa-spin text-primaria"></i> Gerando (leva uns 15s)...';

            // Limpa o editor e mostra loading (usando API do Quill é chatinho injetar HTML de loading, então injetamos texto e trocamos depois)
            document.getElementById('postTitle').value = "Gerando um título persuasivo...";
            quill.root.innerHTML = '<p style="text-align: center; color: #999; margin-top: 50px;"><em>A Inteligência Artificial do Google Gemini está analisando seu produto e redigindo um artigo profissional...</em></p>';

            try {
                const formData = new FormData();
                formData.append('product_id', selectedProduct.id);
                formData.append('product_title', selectedProduct.title);
                formData.append('product_price', selectedProduct.price);
                formData.append('product_permalink', selectedProduct.permalink);
                formData.append('product_image', selectedProduct.image);
                formData.append('csrf_token', csrfToken);

                const response = await fetch('api.php?action=generate_post', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Preenche o Título Sugerido
                    document.getElementById('postTitle').value = result.title;

                    // Preenche o Corpo (Pode vir com Markdown ou HTML)
                    // O Gemini costuma retornar Markdown (ex: **Bold**), vamos inserir como texto ou formatar, ou pedir HTML direto para ele no backend.
                    // O backend já pede HTML no prompt.
                    let htmlContent = result.content;

                    // Tratamento simples caso a API retorne markdown envolto em tags ```html
                    htmlContent = htmlContent.replace(/```html/g, '').replace(/```/g, '');

                    // Insere imagem no topo do texto
                    const imgHtml = selectedProduct.image ? `<p style="text-align: center;"><img src="${sanitizeHTML(selectedProduct.image)}" style="max-width: 300px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;"></p>` : '';

                    quill.clipboard.dangerouslyPasteHTML(0, imgHtml + htmlContent);

                    aiStatus.innerHTML = '<i class="fas fa-check-circle text-green-500"></i> Sucesso! Artigo Criado.';
                    enablePublish();
                    showAlert("Artigo gerado! Você pode editar o texto na caixa abaixo ou enviar direto para o blog.", "green");
                } else {
                    throw new Error(result.error || 'Erro desconhecido na geração.');
                }

            } catch (error) {
                console.error("Erro na IA:", error);
                showAlert(`Erro na Inteligência Artificial: ${error.message}`, 'red');
                aiStatus.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i> Falhou.';
                quill.root.innerHTML = '<p style="color: red;">Erro ao gerar o conteúdo. Verifique se o GEMINI_API_KEY está correto no config.php.</p>';
            } finally {
                btnGenerate.innerHTML = '<i class="fas fa-brain"></i> 1. Reescrever Artigo';
                btnGenerate.disabled = false;
                btnGenerate.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        async function publishPost() {
            const title = document.getElementById('postTitle').value.trim();
            const content = quill.root.innerHTML;

            if (title.length < 5 || content.length < 20) {
                 showAlert("O artigo parece muito curto ou vazio. Escreva algo primeiro.", "yellow");
                 return;
            }

            btnPublish.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando pro WordPress...';
            btnPublish.disabled = true;
            btnPublish.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const formData = new FormData();
                formData.append('title', title);
                formData.append('content', content);
                if (selectedProduct && selectedProduct.image) {
                     formData.append('featured_image_url', selectedProduct.image);
                }
                formData.append('csrf_token', csrfToken);

                const response = await fetch('api.php?action=publish_post', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    btnPublish.innerHTML = '<i class="fas fa-check"></i> Post Criado!';
                    btnPublish.classList.remove('bg-primaria', 'hover:bg-[#2A445D]');
                    btnPublish.classList.add('bg-green-600', 'hover:bg-green-700');

                    const postLink = result.link ? `<a href="${result.link}" target="_blank" class="underline font-bold">Ver Post</a> ou ` : '';

                    showAlert(`🎉 <strong>Arte Finalizada!</strong> Seu post "${sanitizeHTML(title)}" foi criado com sucesso no seu WordPress como Rascunho. Vá em "Posts > Todos os posts" no painel da sua loja, adicione uma categoria e publique!`, 'green');
                } else {
                    throw new Error(result.error || 'Erro desconhecido na publicação.');
                }

            } catch (error) {
                console.error("Erro no WP REST:", error);
                showAlert(`Erro de Publicação: ${error.message}`, 'red');
                btnPublish.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Falha no Envio';
                btnPublish.classList.remove('bg-primaria', 'hover:bg-[#2A445D]');
                btnPublish.classList.add('bg-red-600', 'hover:bg-red-700');
            } finally {
                // Retornar ao estado original após 4 segundos (sucesso ou falha)
                setTimeout(() => {
                    btnPublish.innerHTML = '<i class="fab fa-wordpress"></i> 2. Enviar Rascunho pro Blog';
                    btnPublish.disabled = false;
                    btnPublish.classList.remove('opacity-70', 'bg-red-600', 'hover:bg-red-700', 'bg-green-600', 'hover:bg-green-700');
                    btnPublish.classList.add('bg-primaria', 'hover:bg-[#2A445D]');
                }, 4000);
            }
        }
    </script>
</body>
</html>