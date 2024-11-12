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