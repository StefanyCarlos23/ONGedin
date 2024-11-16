<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

function fetchRandomOngs($conn) {
    $query = "
        SELECT u.nome AS nome_ong, p.foto, p.descricao 
        FROM administrador_ong ao 
        JOIN perfil p ON ao.id_admin_ong = p.id_perfil 
        JOIN usuario u ON ao.id_admin_ong = u.id_usuario 
        ORDER BY RAND() LIMIT 3";
    return $conn->query($query)->fetch_all(MYSQLI_ASSOC);
}

function getCombinedResults($conn, $searchTerm = '', $areas = [], $region = '', $neighborhood = '') {
    $results = [];
    $whereConditions = [];
    $params = [];

    if (!empty($searchTerm)) {
        $whereConditions[] = "u.nome LIKE ?";
        $params[] = '%' . $searchTerm . '%';
    }

    if (!empty($areas)) {
        $placeholders = implode(',', array_fill(0, count($areas), '?'));
        $whereConditions[] = "ao.area_atuacao IN ($placeholders)";
        $params = array_merge($params, $areas);
    }

    $regionsMap = [
        'bairro_novo' => ["Ganchinho", "Sítio Cercado", "Umbará"],
        'boa_vista' => ["Abranches", "Atuba", "Bacacheri", "Bairro Alto", 
                        "Barreirinha", "Boa Vista", "Cachoeira", "Pilarzinho", 
                        "Santa Cândida", "São Lourenço", "Taboão", "Tingui"],
        'boqueirao' => ["Alto Boqueirão", "Boqueirão", "Hauer", "Xaxim"],
        'cajuru' => ["Cajuru", "Capão da Imbuia", "Guabirotuba", 
                     "Jardim das Américas", "Tarumã", "Uberaba"],
        'cidade_industrial' => ["Augusta", "Cidade Industrial de Curitiba (CIC)", "Riviera", "São Miguel"],
        'fazendinha_portao' => ["Água Verde", "Campo Comprido (Sul)", "Fazendinha", 
                               "Guaira", "Parolin", "Portão", "Santa Quitéria", 
                               "Seminário", "Vila Izabel"],
        'matriz' => ["Ahú", "Alto da Glória", "Alto da XV", "Batel", 
                     "Bigorrilho", "Bom Retiro", "Cabral", "Centro", 
                     "Centro Cívico", "Cristo Rei", "Hugo Lange", 
                     "Jardim Botânico", "Jardim Social", "Juvevê", 
                     "Mercês", "Prado Velho", "Rebouças", "São Francisco"],
        'pinheirinho' => ["Capão Raso", "Fanny", "Lindóia", "Novo Mundo", "Pinheirinho"],
        'santa_felicidade' => ["Butiatuvinha", "Campina do Siqueira", "Cascatinha", "Campo Comprido (Norte)", 
                               "Lamenha Pequena", "Mossunguê", "Orleans", "Santa Felicidade", 
                               "Santo Inácio", "São Braz", "São João", "Vista Alegre"],
        'tatuquara' => ["Campo de Santana", "Caximba", "Tatuquara"]
    ];

    if ($region !== '' && isset($regionsMap[$region])) {
        $bairrosRegiao = $regionsMap[$region];
        $placeholders = implode(',', array_fill(0, count($bairrosRegiao), '?'));
        $whereConditions[] = "ao.endereco_bairro IN ($placeholders)";
        $params = array_merge($params, $bairrosRegiao);
    }

    if ($neighborhood !== '') {
        $whereConditions[] = "ao.endereco_bairro = ?";
        $params[] = $neighborhood;
    }

    $whereClause = !empty($whereConditions) ? "WHERE " . implode(' AND ', $whereConditions) : '';

    $queryOngs = $conn->prepare("
        SELECT u.nome AS nome_ong, p.foto, p.descricao, ao.id_admin_ong
        FROM administrador_ong ao
        JOIN perfil p ON ao.id_admin_ong = p.id_perfil
        JOIN usuario u ON ao.id_admin_ong = u.id_usuario
        $whereClause
        ORDER BY u.nome ASC
        LIMIT 10
    ");

    if ($queryOngs === false) {
        die('Erro ao preparar consulta: ' . $conn->error);
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $queryOngs->bind_param($types, ...$params);
    }

    $queryOngs->execute();
    $resultOngs = $queryOngs->get_result();

    while ($row = $resultOngs->fetch_assoc()) {
        $results[] = [
            'type' => 'ong',
            'nome' => $row['nome_ong'],
            'foto' => $row['foto'],
            'descricao' => $row['descricao'],
            'id_admin_ong' => $row['id_admin_ong'],
        ];
    }

    if (!empty($results)) {
        $ongIds = array_column($results, 'id_admin_ong');
        $ongIdsPlaceholder = implode(',', array_fill(0, count($ongIds), '?'));

        $queryEventos = $conn->prepare("
            SELECT e.titulo, e.descricao, a.data_evento, u.nome AS nome_ong, a.id_admin_ong
            FROM admin_ong_cadastra_evento a
            JOIN evento e ON a.id_evento = e.id_evento
            JOIN usuario u ON a.id_admin_ong = u.id_usuario
            WHERE a.id_admin_ong IN ($ongIdsPlaceholder) AND e.titulo LIKE ? AND a.data_evento >= CURDATE()
            ORDER BY a.data_evento ASC
            LIMIT 10
        ");
        $searchTermLike = '%' . $searchTerm . '%';
        $types = str_repeat('i', count($ongIds)) . 's';
        $params = array_merge($ongIds, [$searchTermLike]);

        $queryEventos->bind_param($types, ...$params);
        $queryEventos->execute();
        $resultEventos = $queryEventos->get_result();

        while ($row = $resultEventos->fetch_assoc()) {
            $results[] = [
                'type' => 'evento',
                'titulo' => $row['titulo'],
                'descricao' => $row['descricao'],
                'data_evento' => $row['data_evento'],
                'nome_ong' => $row['nome_ong'],
                'id_admin_ong' => $row['id_admin_ong'],
            ];
        }
    }

    return $results;
}

if (isset($_GET['term'])) {
    header('Content-Type: application/json');
    $searchTerm = trim($_GET['term']);
    echo json_encode(getSuggestions($conn, $searchTerm));
    exit;
}

$randomOngs = fetchRandomOngs($conn);
$searchResults = [];
$errorMessage = '';

if (isset($_GET['searchTerm'])) {
    $searchTerm = trim($_GET['searchTerm']);
    $areas = isset($_GET['area']) ? $_GET['area'] : [];
    $region = isset($_GET['region']) ? $_GET['region'] : '';
    $neighborhood = isset($_GET['neighborhood']) ? $_GET['neighborhood'] : '';

    if (empty($searchTerm) && empty($areas) && empty($region) && empty($neighborhood)) {
        $searchResults = [];
        $ongsResults = [];
        $eventsResults = [];
    } 
    else {
        if ($searchTerm !== '') {
            $searchResults = getCombinedResults($conn, $searchTerm, $areas, $region, $neighborhood);
        }
        elseif (empty($searchTerm) && ($areas !== '' || $region !== '' || $neighborhood !== '')) {
            $searchResults = getCombinedResults($conn, '', $areas, $region, $neighborhood);
        }

        $ongsResults = array_filter($searchResults, function($result) {
            return $result['type'] === 'ong';
        });

        $eventsResults = array_filter($searchResults, function($result) {
            return $result['type'] === 'evento';
        });

        $eventsResults = array_slice($eventsResults, 0, 4);

        if (empty($ongsResults)) {
            $errorMessage = 'Nenhuma ONG encontrada.';
        }

        if (empty($eventsResults)) {
            $eventErrorMessage = 'Nenhum evento encontrado.';
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Pesquisar</title>
    <link href="search.css" rel="stylesheet">
</head>
    <style>
        nav{
            border-bottom: 3px solid #87BFC7;
        }
    </style>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home.php">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home.php">Início</a>
                        <a href="search.php">Pesquisar</a>
                        <a href="help.php">Suporte</a>
                    </ul>
                    <a href="profile.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
        <nav class="mobile-nav">
            <a href="home.php">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="home.php">Início</a>
                <li><a href="search.php">Pesquisar</a></li>
                <li><a href="help.php">Suporte</a></li>
                <li><a href="profile.php">Perfil</a></li>
            </ul>
            </nav>
    </header>

    <section class="search-bar">
        <div class="search">
            <a class="back-btn" href="home.php">Voltar</a>
            <div class="form">
                <form action="" method="GET">
                    <div class="search-container">
                        <input class="search-text" type="text" id="search-input" name="searchTerm" placeholder="Insira o nome da ONG ou título do evento" oninput="showSuggestions(this.value)" value="<?php echo isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : ''; ?>">
                        <div id="suggestions" class="suggestions"></div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="search-btn">Buscar</button>
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
                                        <input type="checkbox" name="area[]" value="meio ambiente" <?php echo (isset($_GET['area']) && in_array('meio ambiente', $_GET['area'])) ? 'checked' : ''; ?>> Meio Ambiente
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="desenvolvimento economico" <?php echo (isset($_GET['area']) && in_array('desenvolvimento economico', $_GET['area'])) ? 'checked' : ''; ?>> Desenvolvimento Econômico
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="direitos humanos" <?php echo (isset($_GET['area']) && in_array('direitos humanos', $_GET['area'])) ? 'checked' : ''; ?>> Direitos Humanos
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="direitos das criancas" <?php echo (isset($_GET['area']) && in_array('direitos das criancas', $_GET['area'])) ? 'checked' : ''; ?>> Direitos das Crianças
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="educacao" <?php echo (isset($_GET['area']) && in_array('educacao', $_GET['area'])) ? 'checked' : ''; ?>> Educação
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="defesa dos animais" <?php echo (isset($_GET['area']) && in_array('defesa dos animais', $_GET['area'])) ? 'checked' : ''; ?>> Defesa dos Animais
                                    </label>
                                </div>
                                <div class="column">
                                    <label>
                                        <input type="checkbox" name="area[]" value="saude" <?php echo (isset($_GET['area']) && in_array('saude', $_GET['area'])) ? 'checked' : ''; ?>> Saúde
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="direitos das mulheres" <?php echo (isset($_GET['area']) && in_array('direitos das mulheres', $_GET['area'])) ? 'checked' : ''; ?>> Direitos das Mulheres
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="cultura e arte" <?php echo (isset($_GET['area']) && in_array('cultura e arte', $_GET['area'])) ? 'checked' : ''; ?>> Cultura e Arte
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="direitos das minorias" <?php echo (isset($_GET['area']) && in_array('direitos das minorias', $_GET['area'])) ? 'checked' : ''; ?>> Direitos das Minorias
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="ajuda humanitaria" <?php echo (isset($_GET['area']) && in_array('ajuda humanitaria', $_GET['area'])) ? 'checked' : ''; ?>> Ajuda Humanitária
                                    </label>
                                    <label>
                                        <input type="checkbox" name="area[]" value="assistencia social" <?php echo (isset($_GET['area']) && in_array('assistencia social', $_GET['area'])) ? 'checked' : ''; ?>> Assistência Social
                                    </label>
                                </div>
                            </div>
                            <p class="highlight-1">Regional</p>
                            <select id="region" name="region" onchange="updateNeighborhoods()">
                                <option value="">Selecione uma regional</option>
                                <option value="bairro novo" <?php echo (isset($_GET['region']) && $_GET['region'] === 'bairro novo') ? 'selected' : ''; ?>>Bairro Novo</option>
                                <option value="boa vista" <?php echo (isset($_GET['region']) && $_GET['region'] === 'boa vista') ? 'selected' : ''; ?>>Boa Vista</option>
                                <option value="boqueirao" <?php echo (isset($_GET['region']) && $_GET['region'] === 'boqueirao') ? 'selected' : ''; ?>>Boqueirão</option>
                                <option value="cajuru" <?php echo (isset($_GET['region']) && $_GET['region'] === 'cajuru') ? 'selected' : ''; ?>>Cajuru</option>
                                <option value="cidade industrial" <?php echo (isset($_GET['region']) && $_GET['region'] === 'cidade industrial') ? 'selected' : ''; ?>>Cidade Industrial de Curitiba (CIC)</option>
                                <option value="fazendinha portao" <?php echo (isset($_GET['region']) && $_GET['region'] === 'fazendinha portao') ? 'selected' : ''; ?>>Fazendinha/Portão</option>
                                <option value="matriz" <?php echo (isset($_GET['region']) && $_GET['region'] === 'matriz') ? 'selected' : ''; ?>>Matriz</option>
                                <option value="pinheirinho" <?php echo (isset($_GET['region']) && $_GET['region'] === 'pinheirinho') ? 'selected' : ''; ?>>Pinheirinho</option>
                                <option value="santa felicidade" <?php echo (isset($_GET['region']) && $_GET['region'] === 'santa felicidade') ? 'selected' : ''; ?>>Santa Felicidade</option>
                                <option value="tatuquara" <?php echo (isset($_GET['region']) && $_GET['region'] === 'tatuquara') ? 'selected' : ''; ?>>Tatuquara</option>
                            </select>
                            <p class="highlight-2">Bairro</p>
                            <select id="neighborhood" name="neighborhood">
                                <option value="">Selecione um bairro</option>
                                <option value="abranches" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'abranches') ? 'selected' : ''; ?>>Abranches</option>
                                <option value="agua verde" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'agua verde') ? 'selected' : ''; ?>>Água Verde</option>
                                <option value="ahu" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'ahu') ? 'selected' : ''; ?>>Ahú</option>
                                <option value="alto boqueirao" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'alto boqueirao') ? 'selected' : ''; ?>>Alto Boqueirão</option>
                                <option value="alto da gloria" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'alto da gloria') ? 'selected' : ''; ?>>Alto da Glória</option>
                                <option value="alto da xv" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'alto da xv') ? 'selected' : ''; ?>>Alto da XV</option>
                                <option value="atuba" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'atuba') ? 'selected' : ''; ?>>Atuba</option>
                                <option value="augusta" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'augusta') ? 'selected' : ''; ?>>Augusta</option>
                                <option value="bacacheri" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'bacacheri') ? 'selected' : ''; ?>>Bacacheri</option>
                                <option value="bairro alto" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'bairro alto') ? 'selected' : ''; ?>>Bairro Alto</option>
                                <option value="barreirinha" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'barreirinha') ? 'selected' : ''; ?>>Barreirinha</option>
                                <option value="batel" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'batel') ? 'selected' : ''; ?>>Batel</option>
                                <option value="bigorrilho" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'bigorrilho') ? 'selected' : ''; ?>>Bigorrilho</option>
                                <option value="boa vista" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'boa vista') ? 'selected' : ''; ?>>Boa Vista</option>
                                <option value="bom retiro" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'bom retiro') ? 'selected' : ''; ?>>Bom Retiro</option>
                                <option value="boqueirao" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'boqueirao') ? 'selected' : ''; ?>>Boqueirão</option>
                                <option value="butiatuvinha" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'butiatuvinha') ? 'selected' : ''; ?>>Butiatuvinha</option>
                                <option value="cabral" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'cabral') ? 'selected' : ''; ?>>Cabral</option>
                                <option value="cachoeira" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'cachoeira') ? 'selected' : ''; ?>>Cachoeira</option>
                                <option value="cajuru" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'cajuru') ? 'selected' : ''; ?>>Cajuru</option>
                                <option value="campina do siqueira" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'campina do siqueira') ? 'selected' : ''; ?>>Campina do Siqueira</option>
                                <option value="campo comprido norte" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'campo comprido norte') ? 'selected' : ''; ?>>Campo Comprido (Norte)</option>
                                <option value="campo comprido sul" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'campo comprido sul') ? 'selected' : ''; ?>>Campo Comprido (Sul)</option>
                                <option value="campo de santana" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'campo de santana') ? 'selected' : ''; ?>>Campo de Santana</option>
                                <option value="capao da imbuia" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'capao da imbuia') ? 'selected' : ''; ?>>Capão da Imbuia</option>
                                <option value="capao raso" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'capao raso') ? 'selected' : ''; ?>>Capão Raso</option>
                                <option value="cascatinha" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'cascatinha') ? 'selected' : ''; ?>>Cascatinha</option>
                                <option value="caximba" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'caximba') ? 'selected' : ''; ?>>Caximba</option>
                                <option value="centro" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'centro') ? 'selected' : ''; ?>>Centro</option>
                                <option value="centro civico" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'centro civico') ? 'selected' : ''; ?>>Centro Cívico</option>
                                <option value="cidade industrial" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'cidade industrial') ? 'selected' : ''; ?>>Cidade Industrial de Curitiba (CIC)</option>
                                <option value="cristo rei" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'cristo rei') ? 'selected' : ''; ?>>Cristo Rei</option>
                                <option value="fanny" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'fanny') ? 'selected' : ''; ?>>Fanny</option>
                                <option value="fazendinha" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'fazendinha') ? 'selected' : ''; ?>>Fazendinha</option>
                                <option value="ganchinho" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'ganchinho') ? 'selected' : ''; ?>>Ganchinho</option>
                                <option value="guabirotuba" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'guabirotuba') ? 'selected' : ''; ?>>Guabirotuba</option>
                                <option value="guaira" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'guaira') ? 'selected' : ''; ?>>Guaira</option>
                                <option value="hauer" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'hauer') ? 'selected' : ''; ?>>Hauer</option>
                                <option value="hugo lange" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'hugo lange') ? 'selected' : ''; ?>>Hugo Lange</option>
                                <option value="jardim botanico" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'jardim botanico') ? 'selected' : ''; ?>>Jardim Botânico</option>
                                <option value="jardim das americas" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'jardim das americas') ? 'selected' : ''; ?>>Jardim das Américas</option>
                                <option value="jardim social" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'jardim social') ? 'selected' : ''; ?>>Jardim Social</option>
                                <option value="juveve" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'juveve') ? 'selected' : ''; ?>>Juvevê</option>
                                <option value="lamenha pequena" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'lamenha pequena') ? 'selected' : ''; ?>>Lamenha Pequena</option>
                                <option value="lindoia" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'lindoia') ? 'selected' : ''; ?>>Lindóia</option>
                                <option value="merces" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'merces') ? 'selected' : ''; ?>>Mercês</option>
                                <option value="mossungue" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'mossungue') ? 'selected' : ''; ?>>Mossunguê</option>
                                <option value="novo mundo" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'novo mundo') ? 'selected' : ''; ?>>Novo Mundo</option>
                                <option value="orleans" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'orleans') ? 'selected' : ''; ?>>Orleans</option>
                                <option value="parolin" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'parolin') ? 'selected' : ''; ?>>Parolin</option>
                                <option value="pilarzinho" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'pilarzinho') ? 'selected' : ''; ?>>Pilarzinho</option>
                                <option value="pinheirinho" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'pinheirinho') ? 'selected' : ''; ?>>Pinheirinho</option>
                                <option value="portao" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'portao') ? 'selected' : ''; ?>>Portão</option>
                                <option value="prado velho" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'prado velho') ? 'selected' : ''; ?>>Prado Velho</option>
                                <option value="reboucas" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'reboucas') ? 'selected' : ''; ?>>Rebouças</option>
                                <option value="riviera" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'riviera') ? 'selected' : ''; ?>>Riviera</option>
                                <option value="santa candida" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'santa candida') ? 'selected' : ''; ?>>Santa Cândida</option>
                                <option value="santa felicidade" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'santa felicidade') ? 'selected' : ''; ?>>Santa Felicidade</option>
                                <option value="santa quiteria" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'santa quiteria') ? 'selected' : ''; ?>>Santa Quitéria</option>
                                <option value="santo inacio" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'santo inacio') ? 'selected' : ''; ?>>Santo Inácio</option>
                                <option value="sao braz" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'sao braz') ? 'selected' : ''; ?>>São Braz</option>
                                <option value="sao francisco" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'sao francisco') ? 'selected' : ''; ?>>São Francisco</option>
                                <option value="sao joao" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'sao joao') ? 'selected' : ''; ?>>São João</option>
                                <option value="sao lourenco" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'sao lourenco') ? 'selected' : ''; ?>>São Lourenço</option>
                                <option value="sao miguel" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'sao miguel') ? 'selected' : ''; ?>>São Miguel</option>
                                <option value="seminario" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'seminario') ? 'selected' : ''; ?>>Seminário</option>
                                <option value="sitio cercado" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'sitio cercado') ? 'selected' : ''; ?>>Sítio Cercado</option>
                                <option value="taboao" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'taboao') ? 'selected' : ''; ?>>Taboão</option>
                                <option value="taruma" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'taruma') ? 'selected' : ''; ?>>Tarumã</option>
                                <option value="tatuquara" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'tatuquara') ? 'selected' : ''; ?>>Tatuquara</option>
                                <option value="tingui" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'tingui') ? 'selected' : ''; ?>>Tingui</option>
                                <option value="uberaba" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'uberaba') ? 'selected' : ''; ?>>Uberaba</option>
                                <option value="umbara" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'umbara') ? 'selected' : ''; ?>>Umbará</option>
                                <option value="vila izabel" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'vila izabel') ? 'selected' : ''; ?>>Vila Izabel</option>
                                <option value="vista alegre" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'vista alegre') ? 'selected' : ''; ?>>Vista Alegre</option>
                                <option value="xaxim" <?php echo (isset($_GET['neighborhood']) && $_GET['neighborhood'] === 'xaxim') ? 'selected' : ''; ?>>Xaxim</option>
                            </select>
                            <div class="buttons">
                                <button type="button" onclick="cleanFilters()" id="clearButton" class="clean">Limpar</button>
                            </div>
                        </section>                
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="events-options">
        <?php if (!empty($ongsResults)): ?>
            <h3>ONGs</h3>
            <?php foreach ($ongsResults as $ong): ?>
                <div class="event">
                    <div class="image-1">
                        <a href="<?= $ong['type'] === 'ong' ? 'ongdetails.php?title=' . urlencode($ong['nome']) : 'event-details.php?title=' . urlencode($ong['titulo']); ?>">
                            <img src="<?= $ong['type'] === 'ong' ? $ong['foto'] : 'images/default-image.png'; ?>" alt="Imagem da <?= htmlspecialchars($ong['type'] === 'ong' ? $ong['nome'] : $ong['titulo']); ?>">
                        </a>
                    </div>
                    <div class="details">
                        <div class="important-details">
                            <h4><?= htmlspecialchars($ong['type'] === 'ong' ? $ong['nome'] : $ong['titulo']); ?></h4>
                        </div>
                        <div class="more-details">
                            <p><?= htmlspecialchars($ong['type'] === 'ong' ? $ong['descricao'] : $ong['descricao'] ?? 'Descrição não disponível.'); ?></p>
                            <?php if ($ong['type'] === 'ong'): ?>
                                <a href="ongdetails.php?title=<?= urlencode($ong['nome']); ?>" class="btn">Ver mais</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($errorMessage)): ?>
            <h3>ONGs</h3>
            <p><?= htmlspecialchars($errorMessage); ?></p>
        <?php else: ?>
            <?php if (!empty($randomOngs)): ?>
                <h3>Sugestões de ONGs</h3>
                <?php foreach ($randomOngs as $ong): ?>
                    <div class="event">
                        <div class="image-1">
                            <a href="ongdetails.php?title=<?= urlencode($ong['nome_ong']); ?>&imgSrc=<?= urlencode($ong['foto']); ?>">
                                <img src="<?= $ong['foto']; ?>" alt="Logo da <?= htmlspecialchars($ong['nome_ong']); ?>">
                            </a>
                        </div>
                        <div class="details">
                            <div class="important-details">
                                <h4><?= htmlspecialchars($ong['nome_ong']); ?></h4>
                            </div>
                            <div class="more-details">
                                <p><?= htmlspecialchars($ong['descricao']); ?></p>
                                <a href="ongdetails.php?title=<?= urlencode($ong['nome_ong']); ?>&imgSrc=<?= urlencode($ong['foto']); ?>" class="btn">Ver mais</a>
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
        <?php
            $ongsResults = [];
            $eventsResults = [];
            $eventErrorMessage = '';

            if (isset($_GET['searchTerm']) || isset($_GET['area']) || isset($_GET['region']) || isset($_GET['neighborhood'])) {
                $areas = isset($_GET['area']) ? $_GET['area'] : [];
                $region = isset($_GET['region']) ? $_GET['region'] : '';
                $neighborhood = isset($_GET['neighborhood']) ? $_GET['neighborhood'] : '';
                $searchTerm = isset($_GET['searchTerm']) ? trim($_GET['searchTerm']) : '';

                if (!empty($searchTerm)) {
                    $ongsResults = getCombinedResults($conn, $searchTerm, $areas, $region, $neighborhood);

                    $eventsQuery = "
                        SELECT e.id_evento, e.titulo, e.descricao, e.local_rua, e.local_numero, e.local_bairro, e.local_cidade, e.local_estado, e.local_pais, 
                            aoc.data_evento, u.nome AS nome_ong
                        FROM evento e
                        JOIN admin_ong_cadastra_evento aoc ON e.id_evento = aoc.id_evento
                        JOIN usuario u ON aoc.id_admin_ong = u.id_usuario
                        WHERE e.titulo LIKE ? 
                        ORDER BY aoc.data_evento ASC
                        LIMIT 4
                    ";

                    $stmt = $conn->prepare($eventsQuery);
                    $searchTermLike = "%" . $searchTerm . "%";
                    $stmt->bind_param('s', $searchTermLike);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $eventsResults[] = $row;
                    }
                } else {
                    $ongsResults = getCombinedResults($conn, '', $areas, $region, $neighborhood);

                    if (!empty($ongsResults)) {
                        $ongIds = [];
                        foreach ($ongsResults as $ong) {
                            if ($ong['type'] === 'ong') {
                                $ongIds[] = $ong['id_admin_ong'];
                            }
                        }

                        if (!empty($ongIds)) {
                            $ongIdsPlaceholder = implode(',', array_fill(0, count($ongIds), '?'));
                            $query = "
                                SELECT e.id_evento, e.titulo, e.descricao, e.local_rua, e.local_numero, e.local_bairro, e.local_cidade, e.local_estado, e.local_pais, 
                                    aoc.data_evento, u.nome AS nome_ong
                                FROM evento e
                                JOIN admin_ong_cadastra_evento aoc ON e.id_evento = aoc.id_evento
                                JOIN usuario u ON aoc.id_admin_ong = u.id_usuario
                                WHERE aoc.id_admin_ong IN ($ongIdsPlaceholder)
                                ORDER BY aoc.data_evento ASC
                                LIMIT 4
                            ";

                            $stmt = $conn->prepare($query);
                            $stmt->bind_param(str_repeat('i', count($ongIds)), ...$ongIds);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $eventsResults[] = $row;
                            }
                        }
                    }
                }
            }
        ?>
        <?php if (!empty($eventsResults)): ?>
            <h3>Eventos</h3>
            <div class="events-container">
                <?php foreach ($eventsResults as $event): ?>
                    <div class="event">
                        <div class="important-details">
                            <h4><?= htmlspecialchars($event['titulo'] ?? $event['nome']); ?></h4>
                            <p><?= isset($event['data_evento']) ? 'Data: ' . date('d/m/Y', strtotime($event['data_evento'])) : ''; ?></p>
                            <p><?= isset($event['nome_ong']) ? 'Organizado por: ' . htmlspecialchars($event['nome_ong']) : ''; ?></p>
                        </div>
                        <div class="more-details">
                            <a href="event-details.php?title=<?= urlencode($event['titulo']); ?>" class="btn">Ver mais</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (empty($ongsResults) && empty($eventsResults) && empty($searchTerm) && (!empty($areas) || !empty($region) || !empty($neighborhood))): ?>
            <h3>Eventos</h3>
            <p class="error">Nenhum evento encontrado.</p>
        <?php elseif (empty($eventsResults) && !empty($ongsResults)): ?>
            <h3>Eventos</h3>
            <div class="events-container">
                <?php
                    foreach ($ongsResults as $ong) {
                        if ($ong['type'] === 'ong') {
                            $eventsQuery = "
                                SELECT e.id_evento, e.titulo, e.descricao, e.local_rua, e.local_numero, e.local_bairro, e.local_cidade, e.local_estado, e.local_pais, 
                                    aoc.data_evento, u.nome AS nome_ong
                                FROM evento e
                                JOIN admin_ong_cadastra_evento aoc ON e.id_evento = aoc.id_evento
                                JOIN usuario u ON aoc.id_admin_ong = u.id_usuario
                                WHERE aoc.id_admin_ong = ? 
                                ORDER BY aoc.data_evento ASC
                                LIMIT 4
                            ";

                            $stmt = $conn->prepare($eventsQuery);
                            $stmt->bind_param('i', $ong['id_admin_ong']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="event">
                                    <div class="important-details">
                                        <h4><?= htmlspecialchars($row['titulo']); ?></h4>
                                        <p>Data: <?= date('d/m/Y', strtotime($row['data_evento'])); ?></p>
                                        <p>Organizado por: <?= htmlspecialchars($row['nome_ong']); ?></p>
                                    </div>
                                    <div class="more-details">
                                        <a href="event-details.php?title=<?= urlencode($row['titulo']); ?>" class="btn">Ver mais</a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    $conn->close();
                ?>
            </div>
        <?php elseif (!empty($eventErrorMessage)): ?>
            <h3>Eventos</h3>
            <p class="error"><?= htmlspecialchars($eventErrorMessage); ?></p>
        <?php else: ?>
            <?php if (!empty($upcomingEvents) && empty($searchTerm) && empty($areas) && empty($region) && empty($neighborhood)): ?>
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
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <script src="search.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>