function toggleFilters() {
    const filterBox = document.getElementById('filter-box');
    filterBox.style.display = filterBox.style.display === 'block' ? 'none' : 'block';
}

function closeFilters() {
    document.getElementById('filter-box').style.display = 'none';
}

function applyFilters() {
    alert('Filtros aplicados!');
    closeFilters();
}

function cleanFilters() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    const regionSelect = document.getElementById('region');
    regionSelect.value = '';

    const neighborhoodSelect = document.getElementById('neighborhood');
    neighborhoodSelect.innerHTML = '<option value="">Selecione um bairro</option>';

    for (const region in neighborhoodsByRegion) {
        neighborhoodsByRegion[region].forEach(function(neighborhood) {
            const option = document.createElement('option');
            option.value = neighborhood.toLowerCase().replace(/\s+/g, '_');
            option.textContent = neighborhood;
            neighborhoodSelect.appendChild(option);
        });
    }
}

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
}

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
}