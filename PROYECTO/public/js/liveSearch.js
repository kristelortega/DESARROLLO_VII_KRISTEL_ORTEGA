'use strict'
const searchInput = document.getElementById('searchInput');
const liveSearchResults = document.getElementById('liveSearchResults');

// Función para obtener la base URL dinámica
function getBaseUrl() {
    // Si estamos en la carpeta php, devolver la base URL correcta
    const path = window.location.pathname;
    if (path.includes('/php/')) {
        // Si estamos dentro de 'php', usar la URL relativa correcta
        return '';
    }
    // Si no, usamos la raíz
    return 'php/';
}

searchInput.addEventListener('input', function () {
    const query = searchInput.value;

    if (query.length === 0) {
        liveSearchResults.style.display = 'none';
        return;
    }

    // Construir la URL base para el fetch
    const baseUrl = getBaseUrl();

    //solicitud al servidor para buscar recetas en Edamam
    fetch(`${baseUrl}search_live_edamam.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                liveSearchResults.innerHTML = data.map(item => `
                        <div style="padding: 8px; cursor: pointer;"
                             onclick="location.href='${baseUrl}detalle_receta_externa.php?id=${encodeURIComponent(item.uri)}'">
                            <img src="${item.image}" alt="${item.label}"
                                 style="width: 50px; height: 50px; margin-right: 10px;">
                            ${item.label}
                        </div>
                    `).join('');
                liveSearchResults.style.display = 'block';
            } else {
                liveSearchResults.innerHTML = '<div style="padding: 8px;">No se encontraron resultados.</div>';
                liveSearchResults.style.display = 'block';
            }
        })
        .catch(err => {
            console.error('Error al buscar:', err);
        });
});

document.addEventListener('click', function (e) {
    if (!liveSearchResults.contains(e.target) && e.target !== searchInput) {
        liveSearchResults.style.display = 'none';
    }
});
