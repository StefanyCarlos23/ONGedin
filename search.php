<?php
include('connection.php');

function getSuggestions($conn, $term) {
    $results = [];
    
    if ($term) {
        $queryOngs = $conn->prepare("
            SELECT u.nome 
            FROM usuario u
            JOIN administrador a ON u.id_usuario = a.id_administrador
            JOIN administrador_ong ao ON a.id_administrador = ao.id_admin_ong
            WHERE u.nome LIKE ? AND u.funcao = 'A'
            LIMIT 10");
        
        $likeTerm = '%' . $term . '%';
        $queryOngs->bind_param('s', $likeTerm);
        $queryOngs->execute();
        $resultOngs = $queryOngs->get_result();

        while ($row = $resultOngs->fetch_assoc()) {
            $results[] = $row['nome'];
        }

        $queryEventos = $conn->prepare("SELECT titulo FROM evento WHERE titulo LIKE ? LIMIT 10");
        $queryEventos->bind_param('s', $likeTerm);
        $queryEventos->execute();
        $resultEventos = $queryEventos->get_result();

        while ($row = $resultEventos->fetch_assoc()) {
            $results[] = $row['titulo'];
        }
    }

    return $results;
}

$searchResults = [];
$errorMessage = '';
$randomOngs = [];

$query = "
    SELECT u.nome AS nome_ong, p.foto, p.descricao 
    FROM administrador_ong ao 
    JOIN perfil p ON ao.id_admin_ong = p.id_perfil 
    JOIN usuario u ON ao.id_admin_ong = u.id_usuario 
    ORDER BY RAND() LIMIT 3";
$result = $conn->query($query);
if ($result !== false) {
    $randomOngs = $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_GET['term'])) {
    header('Content-Type: application/json');
    $searchTerm = trim($_GET['term']);
    
    $searchResults = getSuggestions($conn, $searchTerm);
    
    if (empty($searchResults)) {
        echo json_encode(['message' => 'Nenhuma ONG ou evento encontrado com esse termo.']);
    } else {
        echo json_encode($searchResults);
    }
    exit;
}

if (isset($_GET['searchTerm'])) {
    $searchTerm = trim($_GET['searchTerm']);
    
    if ($searchTerm !== '') {
        $searchResults = getSuggestions($conn, $searchTerm);
        
        if (empty($searchResults)) {
            $errorMessage = 'Nenhuma ONG ou evento encontrado com esse termo.';
        }
    }
}

$upcomingEvents = [];
$queryEvents = "
    SELECT e.titulo, e.descricao, a.data_evento, u.nome AS nome_ong
    FROM admin_ong_cadastra_evento a
    JOIN evento e ON a.id_evento = e.id_evento
    JOIN usuario u ON a.id_admin_ong = u.id_usuario
    WHERE a.data_evento >= CURDATE() AND u.funcao = 'A'
    ORDER BY a.data_evento ASC
    LIMIT 4";
$resultEvents = $conn->query($queryEvents);

if ($resultEvents !== false) {
    $upcomingEvents = $resultEvents->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Pesquisar</title>
    <link href="search.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <nav class="nav">
                <a href="home.php">
                    <img src="images/ongedin-logo.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home.php">Início</a>
                        <a href="search.php">Pesquisar</a>
                        <a href="donations.php">Doações</a>
                        <a href="events.php">Eventos</a>
                        <a href="help.php">Suporte</a>
                    </ul>
                    <a href="notification.php">
                        <img src="images/notificação.png" alt="ongedin-logo">
                    </a>
                    <a href="profile.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
    </header>
    <section class="search-bar">
        <div class="search">
            <a class="back-btn" href="home.php">Voltar</a>
            <div class="form">
                <form action="" method="GET">
                    <div class="search-container">
                        <input class="search-text" type="text" id="search-input" name="searchTerm" placeholder="Insira o nome da ONG ou título do evento" oninput="showSuggestions(this.value)">
                        <div id="suggestions" class="suggestions"></div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="search-btn">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="filter-container">
                <a class="search-filter-btn" href="javascript:void(0);" onclick="toggleFilters()">
                    Filtro <span id="arrow" class="arrow">▼</span>
                </a>
                <section class="filter-box" id="filter-box">
                    <span class="close" onclick="closeFilters()">×</span>
                    <p class="highlight">Área de Atuação</p>
                    <div class="checkbox-container">
                        <div class="column">
                            <label>
                                <input type="checkbox" name="area" value="meio_ambiente"> Meio Ambiente
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="saude"> Desenvolvimento Econômico
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="direitos_humanos"> Direitos Humanos
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="assistencia_social"> Direitos das Crianças
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="educacao"> Educação
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="defesa_animais"> Defesa dos Animais
                            </label>
                        </div>
                        <div class="column">
                            <label>
                                <input type="checkbox" name="area" value="desenvolvimento_economico"> Saúde
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="cultura_arte"> Direito das Mulheres
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="direito_mulheres"> Cultura e Arte
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="direitos_minorias"> Direitos das Minorias
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="ajuda_humanitaria"> Ajuda Humanitária
                            </label>
                            <label>
                                <input type="checkbox" name="area" value="direitos_criancas"> Assistência Social
                            </label>
                        </div>
                    </div>
                    <p class="highlight-1">Regional</p>
                    <select id="region" onchange="updateNeighborhoods()">
                        <option value="">Selecione uma regional</option>
                        <option value="bairro_novo">Bairro Novo</option>
                        <option value="boa_vista">Boa Vista</option>
                        <option value="boqueirao">Boqueirão</option>
                        <option value="cajuru">Cajuru</option>
                        <option value="cidade_industrial">Cidade Industrial de Curitiba (CIC)</option>
                        <option value="fazendinha_portao">Fazendinha/Portão</option>
                        <option value="matriz">Matriz</option>
                        <option value="pinheirinho">Pinheirinho</option>
                        <option value="santa_felicidade">Santa Felicidade</option>
                        <option value="tatuquara">Tatuquara</option>
                    </select>
                    <p class="highlight-2">Bairro</p>
                    <select id="neighborhood">
                        <option value="">Selecione um bairro</option>
                        <option value="abranches">Abranches</option>
                        <option value="agua_verde">Água Verde</option>
                        <option value="ahu">Ahú</option>
                        <option value="alto_boqueirao">Alto Boqueirão</option>
                        <option value="alto_da_gloria">Alto da Glória</option>
                        <option value="alto_da_xv">Alto da XV</option>
                        <option value="atuba">Atuba</option>
                        <option value="augusta">Augusta</option>
                        <option value="bacacheri">Bacacheri</option>
                        <option value="bairro_alto">Bairro Alto</option>
                        <option value="barreirinha">Barreirinha</option>
                        <option value="batel">Batel</option>
                        <option value="bigorrilho">Bigorrilho</option>
                        <option value="boa_vista">Boa Vista</option>
                        <option value="bom_retiro">Bom Retiro</option>
                        <option value="boqueirao">Boqueirão</option>
                        <option value="butiatuvinha">Butiatuvinha</option>
                        <option value="cabral">Cabral</option>
                        <option value="cachoeira">Cachoeira</option>
                        <option value="cajuru">Cajuru</option>
                        <option value="campina_do_siqueira">Campina do Siqueira</option>
                        <option value="campo_comprido_norte">Campo Comprido (Norte)</option>
                        <option value="campo_comprido_sul">Campo Comprido (Sul)</option>
                        <option value="campo_de_santana">Campo de Santana</option>
                        <option value="capao_da_imbuia">Capão da Imbuia</option>
                        <option value="capao_raso">Capão Raso</option>
                        <option value="cascatinha">Cascatinha</option>
                        <option value="caximba">Caximba</option>
                        <option value="centro">Centro</option>
                        <option value="centro_civico">Centro Cívico</option>
                        <option value="cidade_industrial">Cidade Industrial de Curitiba (CIC)</option>
                        <option value="cristo_rei">Cristo Rei</option>
                        <option value="fanny">Fanny</option>
                        <option value="fazendinha">Fazendinha</option>
                        <option value="ganchinho">Ganchinho</option>
                        <option value="guabirotuba">Guabirotuba</option>
                        <option value="guaira">Guaira</option>
                        <option value="hauer">Hauer</option>
                        <option value="hugo_lange">Hugo Lange</option>
                        <option value="jardim_botanico">Jardim Botânico</option>
                        <option value="jardim_das_americas">Jardim das Américas</option>
                        <option value="jardim_social">Jardim Social</option>
                        <option value="juveve">Juvevê</option>
                        <option value="lamenha_pequena">Lamenha Pequena</option>
                        <option value="lindoia">Lindóia</option>
                        <option value="merces">Mercês</option>
                        <option value="mossungue">Mossunguê</option>
                        <option value="novo_mundo">Novo Mundo</option>
                        <option value="orleans">Orleans</option>
                        <option value="parolin">Parolin</option>
                        <option value="pilarzinho">Pilarzinho</option>
                        <option value="pinheirinho">Pinheirinho</option>
                        <option value="portao">Portão</option>
                        <option value="prado_velho">Prado Velho</option>
                        <option value="reboucas">Rebouças</option>
                        <option value="riviera">Riviera</option>
                        <option value="santa_candida">Santa Cândida</option>
                        <option value="santa_felicidade">Santa Felicidade</option>
                        <option value="santa_quiteria">Santa Quitéria</option>
                        <option value="santo_inacio">Santo Inácio</option>
                        <option value="sao_braz">São Braz</option>
                        <option value="sao_francisco">São Francisco</option>
                        <option value="sao_joao">São João</option>
                        <option value="sao_lourenco">São Lourenço</option>
                        <option value="sao_miguel">São Miguel</option>
                        <option value="seminario">Seminário</option>
                        <option value="sitio_cercado">Sítio Cercado</option>
                        <option value="taboao">Taboão</option>
                        <option value="taruma">Tarumã</option>
                        <option value="tatuquara">Tatuquara</option>
                        <option value="tingui">Tingui</option>
                        <option value="uberaba">Uberaba</option>
                        <option value="umbara">Umbará</option>
                        <option value="vila_izabel">Vila Izabel</option>
                        <option value="vista_alegre">Vista Alegre</option>
                        <option value="xaxim">Xaxim</option>
                    </select>
                    <div class="buttons">
                        <button onclick="applyFilters()" class="apply">Aplicar</button>
                        <button onclick="cleanFilters()" id="clearButton" class="clean">Limpar</button>
                    </div>
                </section>                
            </div>
        </div>
    </section>
    <section class="events-options">
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $result): ?>
                <div class="event">
                    <div class="image-1">
                        <a href="ong-details.php?title=<?= urlencode($result['nome'] ?? $result['titulo']); ?>">
                            <img src="<?= $result['foto'] ?? 'images/default-image.png'; ?>" alt="Imagem da <?= htmlspecialchars($result['nome'] ?? $result['titulo']); ?>">
                        </a>
                    </div>
                    <div class="details">
                        <div class="important-details">
                            <h4><?= htmlspecialchars($result['nome'] ?? $result['titulo']); ?></h4>
                        </div>
                        <div class="more-details">
                            <p><?= htmlspecialchars($result['descricao'] ?? 'Descrição não disponível.'); ?></p>
                            <a href="ong-details.php?title=<?= urlencode($result['nome'] ?? $result['titulo']); ?>" class="btn">Ver mais</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($errorMessage)): ?>
            <p><?= htmlspecialchars($errorMessage); ?></p>
        <?php else: ?>
            <?php if (!empty($randomOngs)): ?>
                <h3>Sugestões de ONGs</h3>
                <?php foreach ($randomOngs as $ong): ?>
                    <div class="event">
                        <div class="image-1">
                            <a href="ong-details.php?title=<?= urlencode($ong['nome_ong']); ?>&imgSrc=<?= urlencode($ong['foto']); ?>">
                                <img src="<?= $ong['foto']; ?>" alt="Logo da <?= htmlspecialchars($ong['nome_ong']); ?>">
                            </a>
                        </div>
                        <div class="details">
                            <div class="important-details">
                                <h4><?= htmlspecialchars($ong['nome_ong']); ?></h4>
                            </div>
                            <div class="more-details">
                                <p><?= htmlspecialchars($ong['descricao']); ?></p>
                                <a href="ong-details.php?title=<?= urlencode($ong['nome_ong']); ?>&imgSrc=<?= urlencode($ong['foto']); ?>" class="btn">Ver mais</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma ONG encontrada.</p>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <section class="events-now">
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $result): ?>
                <div class="event-container">
                    <div class="event">
                        <div class="important-details">
                            <h4><?= htmlspecialchars($result['titulo'] ?? $result['nome']); ?></h4>
                            <p><?= isset($result['data_evento']) ? 'Data: ' . date('d/m/Y', strtotime($result['data_evento'])) : ''; ?></p>
                            <p><?= isset($result['nome_ong']) ? 'Organizado por: ' . htmlspecialchars($result['nome_ong']) : ''; ?></p>
                        </div>
                        <div class="more-details">
                            <a href="event-details.php?title=<?= urlencode($result['titulo'] ?? $result['nome']); ?>" class="btn">Ver mais</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($errorMessage)): ?>
            <p></p>
        <?php else: ?>
            <?php if (!empty($upcomingEvents)): ?>
                <h3>Próximos Eventos</h3>
                <div class="events-container">
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div class="event">
                            <div class="important-details">
                                <h4><?= htmlspecialchars($event['titulo']); ?></h4>
                                <p>Data: <?= date('d/m/Y', strtotime($event['data_evento'])); ?></p>
                                <p>Organizado por: <?= htmlspecialchars($event['nome_ong']); ?></p>
                            </div>
                            <div class="more-details">
                                <a href="event-details.php?title=<?= urlencode($event['titulo']); ?>" class="btn">Ver mais</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <script src="search.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>