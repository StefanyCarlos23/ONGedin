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

document.addEventListener('DOMContentLoaded', function() {
    const events = [
        {
            title: "Pequeno Cotolengo",
            description: "O Pequeno Cotolengo é uma ONG dedicada ao acolhimento de pessoas com deficiência intelectual. Desde 1945, promove a inclusão e oferece atendimento especializado, visando melhorar a qualidade de vida dos atendidos.",
            imgSrc: "images/pequeno-cotolengo.png",
            link: "#",
            imageClass: "image-1"
        },
        {
            title: "Passos da Criança",
            description: "A ONG Passos da Criança apoia crianças e adolescentes em vulnerabilidade social, oferecendo educação, saúde e atividades recreativas. Com foco no empoderamento e no fortalecimento de direitos, a organização busca transformar vidas e criar oportunidades para um futuro melhor.",
            imgSrc: "images/passos-da-crianca.png",
            link: "#",
            imageClass: "image-2"
        },
        {
            title: "Força Animal",
            description: "A ONG Força Animal protege animais abandonados e maltratados, oferecendo resgate, cuidados e abrigo. Também realiza campanhas de conscientização sobre adoção responsável e direitos dos animais, sensibilizando a comunidade para a importância do cuidado com os pets.",
            imgSrc: "images/forca-animal.png",
            link: "#",
            imageClass: "image-3"
        },
        {
            title: "Um Lugar ao Sol",
            description: "A ONG Um Lugar ao Sol apoia crianças e adolescentes em vulnerabilidade social, oferecendo atividades educativas e culturais. Seu objetivo é promover a inclusão, fortalecer a autoestima e ajudar os jovens a construir um futuro melhor.",
            imgSrc: "images/um-lugar-ao-sol.png",
            link: "#",
            imageClass: "image-3"
        },
        {
            title: "Gerar",
            description: "A ONG Gerar foca na inclusão social de jovens e famílias em vulnerabilidade. Oferece educação, capacitação profissional e apoio psicossocial, visando empoderar os indivíduos para transformar suas vidas e alcançar autonomia. Além disso, promove atividades culturais e de lazer para o desenvolvimento comunitário.",
            imgSrc: "images/gerar.png",
            link: "#",
            imageClass: "image-1"
        },
        {
            title: "TETO Brasil",
            description: "A ONG TETO Brasil combate a pobreza e a desigualdade social em comunidades vulneráveis. Através da construção de moradias emergenciais e projetos de desenvolvimento comunitário, mobiliza voluntários para promover capacitação e empoderar famílias, buscando transformar realidades e garantir melhores condições de vida.",
            imgSrc: "images/teto.png",
            link: "#",
            imageClass: "image-2"
        }
    ];

    function getRandomEvents(arr, num) {
        const shuffled = arr.sort(() => 0.5 - Math.random());
        return shuffled.slice(0, num);
    }

    const randomEvents = getRandomEvents(events, 3);

    const eventsContainer = document.querySelector('.events-options');

    randomEvents.forEach(event => {
        const eventDiv = document.createElement('div');
        eventDiv.classList.add('event');
        eventDiv.innerHTML = `
            <div class="${event.imageClass}">
                <a href="${event.link}">
                    <img src="${event.imgSrc}" alt="Logo da ${event.title}">
                </a>
            </div>
            <div class="details">
                <div class="important-details">
                    <h4>${event.title}</h4>
                </div>
                <div class="more-details">
                    <p>${event.description}</p>
                    <a href="${event.link}" class="btn">Ver mais</a>
                </div>
            </div>
        `;
        eventsContainer.appendChild(eventDiv);
    });
});