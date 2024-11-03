function toggleFilters() {
    const filterBox = document.getElementById('filter-box');
    filterBox.style.display = filterBox.style.display === 'block' ? 'none' : 'block';
};

function closeFilters() {
    document.getElementById('filter-box').style.display = 'none';
};

function applyFilters() {
    alert('Filtros aplicados!');
    closeFilters();
};

function cleanFilters() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    const regionSelect = document.getElementById('region');
    regionSelect.value = '';

    const neighborhoodSelect = document.getElementById('neighborhood');
    neighborhoodSelect.innerHTML = '<option value="">Selecione um bairro</option>';

    const allNeighborhoods = [
        "Abranches", "Água Verde", "Ahú", "Alto Boqueirão", "Alto da Glória", "Alto da XV",
        "Atuba", "Augusta", "Bacacheri", "Bairro Alto", "Barreirinha", "Batel", "Bigorrilho", "Boa Vista",
        "Bom Retiro", "Boqueirão", "Butiatuvinha", "Cabral", "Cachoeira", "Cajuru", "Campina do Siqueira",
        "Campo Comprido (Norte)", "Campo Comprido (Sul)", "Campo de Santana", "Capão da Imbuia", "Capão Raso", 
        "Cascatinha", "Caximba", "Centro", "Centro Cívico", "Cidade Industrial de Curitiba (CIC)", "Cristo Rei", 
        "Fanny", "Fazendinha", "Ganchinho", "Guabirotuba", "Guaira", "Hauer", "Hugo Lange", "Jardim Botânico", 
        "Jardim das Américas", "Jardim Social", "Juvevê", "Lamenha Pequena", "Lindóia", "Mercês", "Mossunguê", 
        "Novo Mundo", "Orleans", "Parolin", "Pilarzinho", "Pinheirinho", "Portão", "Prado Velho", "Rebouças", 
        "Riviera", "Santa Cândida", "Santa Felicidade", "Santa Quitéria", "Santo Inácio", "São Braz", 
        "São Francisco", "São João", "São Lourenço", "São Miguel", "Seminário", "Sítio Cercado", "Taboão",
        "Tarumã", "Tatuquara", "Tingui", "Uberaba", "Umbará", "Vila Izabel", "Vista Alegre", "Xaxim"
    ];

    allNeighborhoods.forEach(function(neighborhood) {
        const option = document.createElement('option');
        option.value = neighborhood.toLowerCase().replace(/\s+/g, '_');
        option.textContent = neighborhood;
        neighborhoodSelect.appendChild(option);
    });
};

document.getElementById('clearButton').addEventListener('click', cleanFilters);

const neighborhoodsByRegion = {
    bairro_novo: ["Ganchinho", "Sítio Cercado", "Umbará"],
    boa_vista: [
        "Abranches", "Atuba", "Bacacheri", "Bairro Alto", 
        "Barreirinha", "Boa Vista", "Cachoeira", "Pilarzinho", 
        "Santa Cândida", "São Lourenço", "Taboão", "Tingui"
    ],
    boqueirao: ["Alto Boqueirão", "Boqueirão", "Hauer", "Xaxim"],
    cajuru: [
        "Cajuru", "Capão da Imbuia", "Guabirotuba", 
        "Jardim das Américas", "Tarumã", "Uberaba"
    ],
    cidade_industrial: ["Augusta", "Cidade Industrial de Curitiba (CIC)", "Riviera", "São Miguel"],
    fazendinha_portao: [
        "Água Verde", "Campo Comprido (Sul)", "Fazendinha", 
        "Guaira", "Parolin", "Portão", "Santa Quitéria", 
        "Seminário", "Vila Izabel"
    ],
    matriz: [
        "Ahú", "Alto da Glória", "Alto da XV", "Batel", 
        "Bigorrilho", "Bom Retiro", "Cabral", "Centro", 
        "Centro Cívico", "Cristo Rei", "Hugo Lange", 
        "Jardim Botânico", "Jardim Social", "Juvevê", 
        "Mercês", "Prado Velho", "Rebouças", "São Francisco"
    ],
    pinheirinho: ["Capão Raso", "Fanny", "Lindóia", "Novo Mundo", "Pinheirinho"],
    santa_felicidade: [
        "Butiatuvinha", "Campina do Siqueira", "Cascatinha", "Campo Comprido (Norte)", 
        "Lamenha Pequena", "Mossunguê", "Orleans", "Santa Felicidade", 
        "Santo Inácio", "São Braz", "São João", "Vista Alegre"
    ],
    tatuquara: ["Campo de Santana", "Caximba", "Tatuquara"]
};

function updateNeighborhoods() {
    const regionSelect = document.getElementById('region');
    const neighborhoodSelect = document.getElementById('neighborhood');
    const selectedRegion = regionSelect.value;

    neighborhoodSelect.innerHTML = '<option value="">Selecione um bairro</option>';

    if (selectedRegion && neighborhoodsByRegion[selectedRegion]) {
        const neighborhoods = neighborhoodsByRegion[selectedRegion];

        neighborhoods.forEach(function(neighborhood) {
            const option = document.createElement('option');
            option.value = neighborhood.toLowerCase().replace(/\s+/g, '_');
            option.textContent = neighborhood;
            neighborhoodSelect.appendChild(option);
        });
    }
};

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