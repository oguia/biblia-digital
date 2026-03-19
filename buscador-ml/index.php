<?php
// Acesso: /app/buscador-ml/index.php
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
        $_SESSION['logged_in'] = true;
    } else {
        $error = "Senha Incorreta.";
    }
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Tela de Login
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Curador Oculto - Faro de Ouro</title>
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
                <h1 class="text-3xl font-bold font-serif mb-2">Curador Urbano</h1>
                <p class="text-sm text-gray-500">Painel Secreto de Pesquisa ML</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2" for="password">Senha de Acesso</label>
                    <input class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-destaque" id="password" name="password" type="password" placeholder="••••••••" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-destaque hover:bg-[#A88152] text-white font-bold py-3 px-4 rounded w-full focus:outline-none focus:shadow-outline transition duration-300" type="submit">
                        Entrar no Cofre
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
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// O código principal da aplicação virá aqui (Painel de Busca)
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curador de Ofertas - Faro de Ouro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #F8F9FA; color: #4A4A4A; }
        h1, h2, h3, h4, h5, h6, .font-serif { font-family: 'Playfair Display', serif; color: #1A2B3C; }
        .bg-primaria { background-color: #1A2B3C; }
        .bg-destaque { background-color: #C19A6B; }
        .text-primaria { color: #1A2B3C; }
        .text-destaque { color: #C19A6B; }
        .border-destaque { border-color: #C19A6B; }

        /* Loading spinner */
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
        .title-min-h { min-height: 2.5rem; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-[#F8F9FA]">
    <input type="hidden" id="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Navbar -->
    <nav class="bg-primaria p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-white font-serif text-xl font-bold flex items-center gap-2">
                <i class="fas fa-search-dollar text-destaque"></i>
                Radar de Ofertas
            </div>
            <a href="?logout=1" class="text-white hover:text-destaque text-sm font-semibold transition duration-200">
                <i class="fas fa-sign-out-alt mr-1"></i> Sair
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4 mt-6">

        <!-- Search Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 text-center">
            <h2 class="text-2xl font-serif mb-2">O que vamos curar hoje?</h2>
            <p class="text-gray-500 mb-6">Busque no Mercado Livre e crie links de afiliado direto para o Faro de Ouro com 1 clique.</p>

            <form id="searchForm" class="flex flex-col md:flex-row gap-4 justify-center max-w-3xl mx-auto">
                <input type="text" id="query" name="query" class="flex-grow shadow-sm appearance-none border border-gray-300 rounded py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:border-destaque focus:ring-1 focus:ring-destaque" placeholder="Ex: iPhone 13, Fritadeira Mondial, Notebook Gamer..." required>

                <button type="submit" class="bg-destaque hover:bg-[#A88152] text-white font-bold py-3 px-8 rounded focus:outline-none focus:shadow-outline transition duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fas fa-search"></i> Pesquisar
                </button>
            </form>
        </div>

        <!-- Feedback Area -->
        <div id="loading" class="hidden flex-col items-center justify-center py-12">
            <div class="loader mb-4"></div>
            <p class="text-gray-500 font-semibold mt-2">Buscando na internet (isso pode levar alguns segundos)...</p>
        </div>

        <div id="alertArea" class="mb-6"></div>

        <!-- Results Grid -->
        <div id="resultsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Os cards dos produtos aparecerão aqui via JS -->
        </div>

    </div>

    <!-- Scripts -->
    <script>
        const searchForm = document.getElementById('searchForm');
        const resultsGrid = document.getElementById('resultsGrid');
        const loadingIndicator = document.getElementById('loading');
        const alertArea = document.getElementById('alertArea');
        const csrfToken = document.getElementById('csrf_token').value;

        // Formatar Moeda (Real Brasileiro)
        const formatter = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        });

        function showAlert(message, type = 'blue') {
            const colors = {
                'red': 'bg-red-100 border-red-400 text-red-700',
                'green': 'bg-green-100 border-green-400 text-green-700',
                'blue': 'bg-blue-100 border-blue-400 text-blue-700',
                'yellow': 'bg-yellow-100 border-yellow-400 text-yellow-700'
            };
            const theme = colors[type] || colors['blue'];
            // Permite renderizar a div preta de debug sem problemas de escape HTML excessivo
            alertArea.innerHTML = `<div class="${theme} border-l-4 p-4 mb-4 rounded" role="alert"><div>${message}</div></div>`;
            if(type !== 'red') {
                 setTimeout(() => { alertArea.innerHTML = ''; }, 5000);
            }
        }

        // Função simples para sanitizar HTML e evitar XSS
        function sanitizeHTML(str) {
            var temp = document.createElement('div');
            temp.textContent = str;
            return temp.innerHTML;
        }

        searchForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const query = document.getElementById('query').value;

            loadingIndicator.classList.remove('hidden');
            loadingIndicator.classList.add('flex');
            resultsGrid.innerHTML = '';
            alertArea.innerHTML = '';

            try {
                // Ao invés de chamar um api.php bloqueado pela Hostinger IP, usamos o proxy CORS AllOrigins Client-Side
                // com um navegador (Chrome/Safari) para raspar a versão mobile ou desktop do ML
                const proxyUrl = 'https://api.allorigins.win/get?url=';
                const mlUrl = encodeURIComponent(`https://lista.mercadolivre.com.br/${encodeURIComponent(query)}`);

                const response = await fetch(proxyUrl + mlUrl);

                if (!response.ok) {
                    throw new Error(`Erro de proxy (${response.status})`);
                }

                const data = await response.json();

                if(!data.contents) {
                     throw new Error('Retorno vazio do Mercado Livre.');
                }

                // Parse HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(data.contents, 'text/html');

                // Extração dos itens (UI Mobile/Desktop wrapper)
                const items = doc.querySelectorAll('.ui-search-result__wrapper, .ui-search-layout__item');
                let parsedResults = [];

                for(let i=0; i<items.length; i++) {
                     if(parsedResults.length >= 20) break;

                     let item = items[i];

                     // Título
                     let titleEl = item.querySelector('.ui-search-item__title');
                     if(!titleEl) titleEl = item.querySelector('a.ui-search-link h2');
                     let title = titleEl ? titleEl.textContent.trim() : '';

                     // Link
                     let linkEl = item.querySelector('a.ui-search-link');
                     let permalink = linkEl ? linkEl.href : '';
                     if(permalink) {
                         permalink = permalink.split('#')[0].split('?')[0]; // Clean query strings
                     }

                     // Preço
                     let priceEl = item.querySelector('.andes-money-amount__fraction');
                     let priceStr = priceEl ? priceEl.textContent : '0';
                     let price = parseFloat(priceStr.replace(/\./g, '').replace(',', '.'));

                     // Imagem (Lazy loading support)
                     let imgEl = item.querySelector('img');
                     let image = '';
                     if(imgEl) {
                         image = imgEl.getAttribute('data-src') || imgEl.getAttribute('src');
                         if(image && !image.startsWith('data:image')) {
                             image = image.replace('-I.jpg', '-O.jpg'); // Force High-Res
                         }
                     }

                     // Frete
                     let shippingEl = item.querySelector('.ui-pb-highlight');
                     let free_shipping = shippingEl && shippingEl.textContent.includes('Frete grátis');

                     // ID
                     let match = permalink.match(/MLB-?(\d+)/);
                     let id = match ? 'MLB' + match[1] : 'mlb_' + Math.floor(Math.random()*100000);

                     if(title && price && permalink) {
                          parsedResults.push({
                              id: id,
                              title: title,
                              price: price,
                              currency: 'BRL',
                              permalink: permalink,
                              image: image,
                              is_catalog: false, // Cannot guarantee without deeper JSON inspection
                              condition: 'new',
                              sold_quantity: 0,
                              free_shipping: free_shipping,
                              is_proxy_search: false
                          });
                     }
                }

                loadingIndicator.classList.add('hidden');
                loadingIndicator.classList.remove('flex');

                if (parsedResults.length > 0) {
                    renderResults(parsedResults);
                } else {
                    showAlert('Nenhum produto encontrado com essa palavra-chave (ou o Mercado Livre bloqueou a extração). Tente novamente mais tarde.', 'yellow');
                }

            } catch (error) {
                loadingIndicator.classList.add('hidden');
                loadingIndicator.classList.remove('flex');
                console.error("Erro Fetch:", error);
                showAlert(`Ocorreu um erro ao buscar os dados: ${error.message}`, 'red');
            }
        });

        function renderResults(products) {
            resultsGrid.innerHTML = products.map(product => {
                const safeTitle = sanitizeHTML(product.title);
                const safePermalink = sanitizeHTML(product.permalink);
                const safeImage = sanitizeHTML(product.image);

                // Selo Frete Grátis
                let shippingBadge = product.free_shipping ? `<span class="text-green-600 font-bold text-xs"><i class="fas fa-truck-fast"></i> Frete Grátis</span>` : '';

                // Link para copiar (Para afiliados)
                const copyBtnHtml = `<button onclick="navigator.clipboard.writeText('${safePermalink}'); showAlert('Link do Mercado Livre copiado!', 'green');" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold py-2 px-3 rounded w-full flex items-center justify-center gap-1 transition duration-200"><i class="fas fa-copy"></i> Copiar Link Puro</button>`;

                // Botão de Importação (Opção B)
                const encodedProduct = encodeURIComponent(JSON.stringify(product));
                const importBtnHtml = `<button onclick="importToWooCommerce('${product.id}')" class="bg-primaria hover:bg-[#2A445D] text-white text-sm font-bold py-2 px-3 rounded w-full flex items-center justify-center gap-1 transition duration-200 mt-2 import-btn-${product.id}" data-product="${encodedProduct}">
                    <i class="fas fa-download"></i> Criar Rascunho no Site
                </button>`;

                return `
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition-shadow relative">
                    <a href="${safePermalink}" target="_blank" rel="noopener noreferrer" class="h-48 bg-white flex items-center justify-center p-4 border-b border-gray-100">
                        <img src="${safeImage}" alt="Imagem do produto" class="max-h-full max-w-full object-contain mix-blend-multiply" onerror="this.src='https://via.placeholder.com/150?text=Imagem+Indispon%C3%ADvel'">
                    </a>
                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wider">${product.condition === 'new' ? 'Novo' : 'Usado'} ${shippingBadge}</div>
                        <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 title-min-h" title="${safeTitle}">
                            ${safeTitle}
                        </h3>
                        <div class="mt-auto">
                            <p class="text-xl font-bold font-serif text-primaria mb-4">${formatter.format(product.price)}</p>

                            <div class="flex flex-col gap-2">
                                <a href="${safePermalink}" target="_blank" rel="noopener noreferrer" class="text-primaria border border-primaria hover:bg-primaria hover:text-white text-xs font-bold py-2 px-3 rounded text-center transition duration-200">
                                    Abrir Anúncio ML <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                                ${copyBtnHtml}
                                ${importBtnHtml}
                            </div>
                        </div>
                    </div>
                </div>
                `;
            }).join('');
        }

        async function importToWooCommerce(productId) {
            const btn = document.querySelector(`.import-btn-${productId}`);
            const originalText = btn.innerHTML;

            // Recuperar o JSON embedado no botão de forma segura
            const productData = JSON.parse(decodeURIComponent(btn.getAttribute('data-product')));

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';
            btn.disabled = true;
            btn.classList.add('opacity-70');

            try {
                const formData = new FormData();
                formData.append('product', JSON.stringify(productData));
                formData.append('csrf_token', csrfToken); // Envia o token de segurança para validar no backend

                const response = await fetch('import.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Importado!';
                    btn.classList.remove('bg-primaria', 'hover:bg-[#2A445D]');
                    btn.classList.add('bg-green-600', 'hover:bg-green-700');
                    showAlert(`Produto "<b>${sanitizeHTML(productData.title)}</b>" importado com sucesso para o Faro de Ouro! (ID: ${result.wp_id})`, 'green');
                } else {
                    throw new Error(result.error || 'Erro desconhecido na importação.');
                }

            } catch (error) {
                console.error("Erro na Importação:", error);
                showAlert(`Erro ao importar o produto: ${error.message}`, 'red');
                btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Falhou';
                btn.classList.remove('bg-primaria', 'hover:bg-[#2A445D]');
                btn.classList.add('bg-red-600', 'hover:bg-red-700');

                // Retornar ao estado original após 3 segundos
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'bg-red-600', 'hover:bg-red-700');
                    btn.classList.add('bg-primaria', 'hover:bg-[#2A445D]');
                }, 3000);
            }
        }
    </script>
</body>
</html>