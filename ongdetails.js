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