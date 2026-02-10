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
});

let map; // Variável global para o mapa

function initMap() {
    // Verifica se existem locais definidos na view
    if (typeof window.bibliaLocais === 'undefined' || window.bibliaLocais.length === 0) {
        return;
    }

    const locais = window.bibliaLocais;
    const mapElement = document.getElementById('map');

    if (!mapElement) return;

    // Centralizar no primeiro ponto
    map = L.map('map').setView([locais[0].latitude, locais[0].longitude], 5);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    locais.forEach(local => {
        const marker = L.marker([local.latitude, local.longitude]).addTo(map);

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
}

// Função para focar em um local específico ao clicar na lista
window.focarMapa = function(lat, lon, nome) {
    if (map) {
        map.flyTo([lat, lon], 10, {
            animate: true,
            duration: 1.5
        });

        // Opcional: Abrir popup se encontrar o marcador correspondente (requer rastreamento de markers)
    }
};
