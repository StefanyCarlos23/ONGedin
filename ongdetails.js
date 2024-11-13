let debounceTimer;

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
            })
            .catch(error => console.error('Erro ao buscar sugestões:', error));
    }, 300);
}

document.getElementById('search-input').addEventListener('input', function() {
    showSuggestions(this.value);
});

function showSuggestions(term) {
    const suggestionsContainer = document.getElementById('suggestions');
    suggestionsContainer.innerHTML = '';

    if (term.length < 1) {
        suggestionsContainer.style.display = 'none';
        return;
    }

    fetch(`search.php?term=${encodeURIComponent(term)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                suggestionsContainer.style.display = 'flex';
                data.forEach(item => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('suggestion-item');
                    suggestionItem.textContent = item;
                    suggestionItem.onclick = () => selectSuggestion(item);
                    suggestionsContainer.appendChild(suggestionItem);
                });
            } else {
                suggestionsContainer.style.display = 'none';
            }
        })
        .catch(error => console.error('Error fetching suggestions:', error));
}

function selectSuggestion(value) {
    document.getElementById('search-input').value = value;
    document.getElementById('suggestions').innerHTML = '';
    document.getElementById('suggestions').style.display = 'none';
}

function initMap() {
    const endereco = document.querySelector('.ong-item').dataset.endereco;
    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({ address: endereco }, (results, status) => {
        if (status === 'OK') {
            const map = new google.maps.Map(document.getElementById('map'), {
                center: results[0].geometry.location,
                zoom: 16
            });
            new google.maps.Marker({
                position: results[0].geometry.location,
                map: map
            });
        } else {
            alert("Erro ao encontrar o endereço: " + status);
        }
    });
}

function verMais(idOng) {
    window.location.href = "/ong/" + idOng;
}
