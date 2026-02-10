document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar Mapa
    initMap();

    // 2. Listeners de Navegação
    const livroSelect = document.getElementById('livroSelect');
    const versaoSelect = document.getElementById('versaoSelect');
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (livroSelect) {
        livroSelect.addEventListener('change', (e) => {
            const params = new URLSearchParams(window.location.search);
            params.set('livro', e.target.value);
            params.set('cap', 1); // Resetar para cap 1 ao mudar livro
            window.location.search = params.toString();
        });
    }

    if (versaoSelect) {
        versaoSelect.addEventListener('change', (e) => {
            const params = new URLSearchParams(window.location.search);
            params.set('versao', e.target.value);
            window.location.search = params.toString();
        });
    }

    // 3. Listener do Menu Mobile
    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', (e) => {
            e.preventDefault();
            mobileMenu.classList.toggle('hidden');
        });
    }

    // 4. Tabs Mobile
    const btnTexto = document.getElementById('tab-btn-texto');
    const btnContexto = document.getElementById('tab-btn-contexto');
    const tabTexto = document.getElementById('tab-content-texto');
    const tabContexto = document.getElementById('tab-content-contexto');

    if (btnTexto && btnContexto && tabTexto && tabContexto) {
        console.log("Abas mobile encontradas e inicializadas.");

        btnTexto.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Aba Texto clicada");

            // Mostrar Texto
            tabTexto.classList.remove('hidden');
            tabContexto.classList.add('hidden');

            // Estilo Botão Ativo
            btnTexto.classList.add('border-primary', 'text-primary', 'font-bold');
            btnTexto.classList.remove('border-transparent', 'text-gray-500', 'font-medium');

            // Estilo Botão Inativo
            btnContexto.classList.remove('border-primary', 'text-primary', 'font-bold');
            btnContexto.classList.add('border-transparent', 'text-gray-500', 'font-medium');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        btnContexto.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Aba Contexto clicada");

            // Mostrar Contexto
            tabTexto.classList.add('hidden');
            tabContexto.classList.remove('hidden');

            // Estilo Botão Ativo
            btnContexto.classList.add('border-primary', 'text-primary', 'font-bold');
            btnContexto.classList.remove('border-transparent', 'text-gray-500', 'font-medium');

            // Estilo Botão Inativo
            btnTexto.classList.remove('border-primary', 'text-primary', 'font-bold');
            btnTexto.classList.add('border-transparent', 'text-gray-500', 'font-medium');

            // Ajustar Mapa e Tiles (Estratégia Agressiva para corrigir tiles cinzas)
            if (typeof map !== 'undefined' && map) {
                console.log("Iniciando redimensionamento agressivo do mapa...");

                // 1. Redimensionamento imediato e em cascata
                const resizeDelays = [0, 100, 300, 500, 1000];
                resizeDelays.forEach(delay => {
                    setTimeout(() => {
                        map.invalidateSize();
                        console.log(`Resize executado em ${delay}ms`);
                    }, delay);
                });

                // 2. Forçar atualização dos tiles após um breve delay
                setTimeout(() => {
                    if (window.bibliaLocais && window.bibliaLocais.length > 0) {
                        const bounds = L.latLngBounds(window.bibliaLocais.map(l => [l.latitude, l.longitude]));
                        map.fitBounds(bounds, { padding: [50, 50] });
                    } else {
                        // Default reset
                        map.setView([31.7683, 35.2137], 6);
                    }
                }, 350);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    } else {
        console.warn("Elementos das abas mobile não encontrados.");
    }

    // 5. Carregar contexto via AJAX ao navegar (Exemplo para navegação de capítulos)
    // Para simplificar, vamos manter a navegação padrão por recarregamento
    // mas expor a função loadContext para uso futuro ou integração
});

let map; // Variável global para o mapa
let markers = []; // Array para armazenar marcadores

function initMap() {
    // Inicializar mapa vazio se não existir
    if (!document.getElementById('map')) return;

    // Se já existem locais carregados pelo PHP (via window.bibliaLocais), usa-os
    // Caso contrário, inicia com visão global
    const locaisIniciais = (typeof window.bibliaLocais !== 'undefined' && window.bibliaLocais.length > 0)
        ? window.bibliaLocais
        : [{latitude: 31.7683, longitude: 35.2137, nome: 'Jerusalém'}]; // Default Jerusalem

    const zoomInicial = (typeof window.bibliaLocais !== 'undefined' && window.bibliaLocais.length > 0) ? 6 : 4;

    map = L.map('map').setView([locaisIniciais[0].latitude, locaisIniciais[0].longitude], zoomInicial);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // Se houver locais carregados via PHP, adiciona os marcadores
    if (typeof window.bibliaLocais !== 'undefined' && window.bibliaLocais.length > 0) {
        addMarkers(window.bibliaLocais);
    }
}

function addMarkers(locais) {
    // Limpar marcadores anteriores se necessário (para uso com AJAX)
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    if (locais.length === 0) return;

    const bounds = L.latLngBounds();

    locais.forEach(local => {
        const marker = L.marker([local.latitude, local.longitude]).addTo(map);
        markers.push(marker);
        bounds.extend([local.latitude, local.longitude]);

        let popupContent = `<div class="p-2 text-center">
            <h4 class="font-bold text-lg text-gray-800 mb-1">${local.nome}</h4>`;

        if (local.imagem) {
            popupContent += `<img src="${local.imagem}" class="w-full h-32 object-cover rounded mb-2">`;
        }

        if (local.descricao) {
            popupContent += `<p class="text-sm text-gray-600">${local.descricao}</p>`;
        }

        popupContent += `</div>`;

        marker.bindPopup(popupContent);
    });

    if (locais.length > 1) {
        map.fitBounds(bounds, { padding: [50, 50] });
    } else {
        map.setView([locais[0].latitude, locais[0].longitude], 8);
    }
}

// Função para carregar contexto via AJAX
window.loadContext = async function(livroId, capitulo) {
    try {
        const response = await fetch(`api/contexto.php?livro=${livroId}&cap=${capitulo}`);
        const result = await response.json();

        if (result.success) {
            // Atualizar Mapa
            const mapContainer = document.getElementById('map-container');
            const noDataMessage = document.getElementById('no-geo-data');

            if (result.data.geo && result.data.geo.length > 0) {
                if (mapContainer) mapContainer.classList.remove('hidden');
                if (noDataMessage) noDataMessage.classList.add('hidden');
                addMarkers(result.data.geo);
                updateGeoList(result.data.geo);
            } else {
                if (mapContainer) mapContainer.classList.add('hidden');
                if (noDataMessage) noDataMessage.classList.remove('hidden');
            }

            // Atualizar Cronologia e Aplicação (Lógica similar de atualização de DOM necessária)
            updateCronologia(result.data.cronologia);

            console.log("Contexto carregado:", result.data);
        }
    } catch (error) {
        console.error("Erro ao carregar contexto:", error);
    }
};

function updateGeoList(locais) {
    const listContainer = document.getElementById('geo-list');
    if (!listContainer) return;

    let html = '';
    locais.forEach(geo => {
        html += `
            <div class="flex gap-3 items-start group cursor-pointer hover:bg-gray-50 p-2 rounded transition" onclick="focarMapa(${geo.latitude}, ${geo.longitude}, '${geo.nome.replace(/'/g, "\\'")}')">
                <div class="w-2 h-2 mt-2 rounded-full bg-primary flex-shrink-0"></div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-primary transition">${geo.nome}</h4>
                    <p class="text-xs text-gray-500 line-clamp-2">${geo.descricao || ''}</p>
                </div>
            </div>`;
    });
    listContainer.innerHTML = html;

    // Atualizar contador
    const countBadge = document.getElementById('geo-count');
    if (countBadge) countBadge.innerText = `${locais.length} Locais`;
}

function updateCronologia(data) {
    const cronoContainer = document.getElementById('cronologia-container');
    if (!cronoContainer) return;

    if (!data) {
        cronoContainer.classList.add('hidden');
        return;
    }

    cronoContainer.classList.remove('hidden');
    // Atualizar campos específicos pelos IDs (necessário adicionar IDs no HTML)
    // Exemplo simplificado:
    if(document.getElementById('crono-periodo')) document.getElementById('crono-periodo').innerText = data.periodo;
    if(document.getElementById('crono-ano')) document.getElementById('crono-ano').innerText = `Aprox. ${data.ano_estimado}`;
    if(document.getElementById('crono-personagens')) document.getElementById('crono-personagens').innerText = data.personagens;
    if(document.getElementById('crono-eventos')) document.getElementById('crono-eventos').innerText = `"${data.eventos_mundiais}"`;

    // Conexão com Jesus
    const jesusContainer = document.getElementById('crono-jesus-container');
    const jesusText = document.getElementById('crono-jesus');

    if (data.conexao_jesus) {
        if(jesusContainer) jesusContainer.classList.remove('hidden');
        if(jesusText) jesusText.innerText = data.conexao_jesus;
    } else {
        if(jesusContainer) jesusContainer.classList.add('hidden');
    }
}

// Função para focar em um local específico ao clicar na lista
window.focarMapa = function(lat, lon, nome) {
    if (map) {
        map.flyTo([lat, lon], 10, {
            animate: true,
            duration: 1.5
        });
    }
};
