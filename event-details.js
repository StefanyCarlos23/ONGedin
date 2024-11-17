let isLoggedIn = false;
let isSubscribed = false;
let debounceTimer;

function subscribeEvent() {
    if (!isLoggedIn) {
        alert("Você precisa estar logado para se inscrever no evento.");
        return;
    }
        
    if (!isSubscribed) {
        isSubscribed = true;
        document.getElementById("inscrever-btn").innerText = "Inscrito";
        document.getElementById("inscrever-btn").style.backgroundColor = "#b9b9b9";
        showNotificationButton();
    }
}

function showNotificationButton() {
    const btnNotificacoes = document.createElement("button");
    btnNotificacoes.innerText = "Ativar Notificações";
    btnNotificacoes.id = "notificacoes-btn";
    btnNotificacoes.style.marginLeft = "10px";
    btnNotificacoes.onclick = ativarNotificacoes;
    document.querySelector(".image-btn").appendChild(btnNotificacoes);
}

function activateNotifications() {
    const btnNotificacoes = document.getElementById("notificacoes-btn");
    btnNotificacoes.style.backgroundColor = "#1bce00";
    btnNotificacoes.disabled = true;
}

function submitFeedback() {
    if (!isLoggedIn) {
        alert("Você precisa estar logado para realizar o feedback.");
        return;
    }

    if (!isSubscribed) {
        alert("Você precisa estar inscrito no evento para realizar o feedback.");
        return;
    }

    const eventIdElement = document.getElementById('id_evento');
    const eventId = eventIdElement ? eventIdElement.value : null;

    if (!eventId) {
        alert("Erro ao localizar o ID do evento.");
        return;
    }

    window.location.href = `feedback-form.php?id_evento=${eventId}`;
}

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