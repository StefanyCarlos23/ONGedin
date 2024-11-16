let debounceTimer;
let endereco;
let coordenadas;

function showSuggestions(term) {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const suggestionsContainer = document.getElementById('suggestions');
        suggestionsContainer.innerHTML = '';

        if (term.length < 1) {
            suggestionsContainer.style.display = 'none';
            return;
        }

        fetch(`search.php?term=${encodeURIComponent(term)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }

                data.forEach(item => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.textContent = item;
                    suggestionItem.classList.add('suggestion-item');

                    suggestionItem.onclick = function() {
                        document.getElementById('search-input').value = item;
                        suggestionsContainer.innerHTML = '';
                        suggestionsContainer.style.display = 'none';
                    };

                    suggestionsContainer.appendChild(suggestionItem);
                });

                suggestionsContainer.style.display = 'block';
                suggestionsContainer.classList.add('show'); 
            })
            .catch(error => console.error('Erro ao buscar sugestões:', error));
    }, 300);
}

document.getElementById('search-input').addEventListener('input', function() {
    showSuggestions(this.value);
});

function selectSuggestion(value) {
    document.getElementById('search-input').value = value;
    document.getElementById('suggestions').innerHTML = '';
    document.getElementById('suggestions').style.display = 'none';
}

function getOngDetails() {
    fetch('caminho_do_php?id=ID_DA_ONG')
        .then(response => response.json())
        .then(data => {
            endereco = data.ongDetails;
            coordenadas = data.coordenadas;
            initMap();
        })
        .catch(error => console.error('Erro ao buscar os dados da ONG:', error));
}

function initMap() {
    if (!coordenadas) {
        console.error('Coordenadas não encontradas.');
        return;
    }

    const map = L.map('map').setView([coordenadas.lat, coordenadas.lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([coordenadas.lat, coordenadas.lng]).addTo(map);
}

window.onload = getOngDetails;

function verMais(idOng) {
    window.location.href = "/ong/" + idOng;
}