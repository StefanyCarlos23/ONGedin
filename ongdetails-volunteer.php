<?php
include('connection.php');

function geocodeAddress($address) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: LocalApp/1.0 (localhost)'
    ));
    
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return null;
    }

    $json = json_decode($response);

    if (isset($json[0])) {
        $lat = $json[0]->lat;
        $lng = $json[0]->lon;
        return ['lat' => $lat, 'lng' => $lng];
    } else {
        return null;
    }
}

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

function getIdOng($conn, $nome_ong) {
    $stmt = $conn->prepare("
        SELECT ao.id_admin_ong 
        FROM usuario u 
        JOIN administrador a ON u.id_usuario = a.id_administrador 
        JOIN administrador_ong ao ON a.id_administrador = ao.id_admin_ong 
        WHERE u.nome = ? AND u.funcao = 'A'
    ");
    $stmt->bind_param('s', $nome_ong);
    $stmt->execute();
    $result = $stmt->get_result();

    $ongData = $result->fetch_assoc();
    return $ongData ? $ongData['id_admin_ong'] : null;
}

$nome_ong = $_GET['title'] ?? null;
$imgSrc = $_GET['imgSrc'] ?? 'images/default-logo.png';

if ($nome_ong) {
    $id_ong = getIdOng($conn, $nome_ong);
    
    if ($id_ong) {
        $stmtOng = $conn->prepare("
            SELECT ao.area_atuacao, ao.data_fundacao, ao.endereco_rua, ao.endereco_numero, 
                   ao.endereco_complemento, ao.endereco_bairro, ao.endereco_cidade, 
                   p.foto, p.descricao 
            FROM administrador_ong ao 
            JOIN perfil p ON p.id_perfil = ao.id_admin_ong 
            WHERE ao.id_admin_ong = ?
        ");
        $stmtOng->bind_param('i', $id_ong);
        $stmtOng->execute();
        $ongDetails = $stmtOng->get_result()->fetch_assoc();
        
        $enderecoCompleto = $ongDetails['endereco_rua'] . ', ' . $ongDetails['endereco_numero'] . ' ' . 
                            $ongDetails['endereco_complemento'] . ', ' . $ongDetails['endereco_bairro'] . ', ' . 
                            $ongDetails['endereco_cidade'];

        $coordenadas = geocodeAddress($enderecoCompleto);

        $area_atuacao = $ongDetails['area_atuacao'];

        $stmtOngs = $conn->prepare("
            SELECT u.id_usuario, u.nome, p.foto, ao.endereco_rua, ao.endereco_numero, 
                   ao.endereco_complemento, ao.endereco_bairro, ao.endereco_cidade
            FROM usuario u
            JOIN administrador_ong ao ON u.id_usuario = ao.id_admin_ong
            JOIN perfil p ON p.id_perfil = ao.id_admin_ong
            WHERE ao.area_atuacao = ? AND u.funcao = 'A' AND ao.id_admin_ong != ?
            ORDER BY RAND() 
            LIMIT 3
        ");
        $stmtOngs->bind_param('si', $area_atuacao, $id_ong);
        $stmtOngs->execute();
        $ongsSimilares = $stmtOngs->get_result()->fetch_all(MYSQLI_ASSOC);

        if ($ongsSimilares === null) {
            $ongsSimilares = [];
        }

        $stmtEventos = $conn->prepare("
            SELECT e.titulo, e.descricao, 
                   e.local_rua, e.local_numero, e.local_complemento, 
                   e.local_bairro, e.local_cidade, aoc.data_evento, aoc.horario_evento
            FROM evento e 
            INNER JOIN admin_ong_cadastra_evento aoc ON e.id_evento = aoc.id_evento 
            WHERE aoc.id_admin_ong = ?
            ORDER BY aoc.data_evento ASC
        ");
        $stmtEventos->bind_param('i', $id_ong);
        $stmtEventos->execute();
        $eventos = $stmtEventos->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $ongDetails = [];
        $ongsSimilares = [];
        $eventos = [];
    }
} else {
    $ongDetails = [];
    $ongsSimilares = [];
    $eventos = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Detalhes da ONG</title>
    <link href="ongdetails.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
    <style>
        nav{
            background-color: #F6F6F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 35px;
            padding-left: 20px;
            border-bottom: 3px solid #87BFC7;
        }

        nav ul a img {
            width: 35px;
            height: 35px;
            margin: 0 15px;
        }

        .search-bar .search .search-btn:active{
            color: #666666;
            background-color: #4d909a;
        }

        .search-bar .search .search-btn:hover {
            background-color: #4d909a;
            transition: 0.4s;
        }

        .ong-details .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            margin: 30px auto;
            margin-top: 50px;
            width: 80%;
            flex-wrap: wrap;
        }

        .ong-details .container .image {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: rgb(255, 255, 255);
            flex: 1;
            border-radius: 25px;
            box-shadow: 2px 2px 0.8em rgba(47, 47, 47, 0.3);
            height: 250px;
            overflow: hidden;
        }

        .ong-text{
            color: #666666;
            flex: 2;
            font-size: 16px;
            padding-left: 80px;
        }

        .ong-details .container .ong-text h3 {
            margin-bottom: 20px;
            color: #87BFC7;
            font-size: 40px;
        }

        .ong-details .container .ong-text p {
            line-height: 1.5em;
        }

        .events {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-bottom: 80px;
        }

        .events .container {
            width: 90%;
            max-width: 1200px;
            text-align: center;
            margin-top: 20px;
        }

        .events .container h3 {
            margin-bottom: 40px;
            color: #87BFC7;
            font-size: 30px;
        }

        .event-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            text-align: center;
            margin-top: 30px;
            background-color: #e0e0e0;
            border-radius: 25px;
            padding: 30px;
            justify-content: center;
        }

        .event-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .event-item:hover {
            transform: translateY(-5px);
            background-color: #87BFC7;
            color: white;
        }

        .event-item .date {
            font-size: 16px;
            font-weight: bold;
            color: #666666;
            margin-bottom: 10px;
        }

        .event-item h4 {
            font-size: 16px;
            font-weight: bold;
            color: #666666;
            margin-bottom: 10px;
        }

        .event-item p {
            font-size: 16px;
            color: #666666;
            margin-bottom: 20px;
        }

        .event-item .btn-ver-mais {
            margin: 10px auto;
            padding: 10px 15px;
            background-color: #87BFC7;
            color: white;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            width: fit-content;
        }

        .event-item .btn-ver-mais:hover {
            background-color: #4d909a;
            transition: 0.4s;
        }

        .event-item .btn-ver-mais:active {
            color: #666666;
            background-color: #4d909a;
        }

        .event-box .event-item:nth-child(odd) {
            background-color: #f3f3f3;
        }

        .event-box .event-item:nth-child(even) {
            background-color: #f9f9f9;
        }

        .ong-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 auto;
            margin-bottom: 80px;
            width: 80%;
        }

        .ong-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex: 1;
        }

        .ong-section .ong-left .title {
            font-size: 20px;
            color: #666666;
        }

        .ong-item {
            display: flex;
            flex-direction: row;
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            justify-content: space-between;
            align-items: center;
            min-width: 300px;
        }

        .ong-nome {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            margin-left: 20px;
            white-space: nowrap;
        }

        .ong-nome p {
            color: #666666;
            font-size: 16px;
            margin-right: 10px;
        }

        .ong-logo {
            width: 100px;
            height: auto;
        }

        .ver-mais {
            padding: 10px 15px;
            background-color: #b9b9b9;
            color: white;
            border: none;
            text-decoration: none;
            font-size: 16px;
            border-radius: 10px;
            transition: color 0.1s;
            cursor: pointer;
            margin-left: 10px;
        }

        .ver-mais:hover {
            background-color: #909090;
            transition: 0.4s;
        }

        .ver-mais:active {
            color: #666666;
            background-color: #909090;
        }

        .mapBox {
            position: relative;
            height: 400px;
            flex: 2;
            margin-left: 50px;
            border: 3px solid #87BFC7;
        }

        .mapBox iframe {
            width: 100%;
            height: 100%;
        }

        .ong-section .none, 
        .events .none {
            margin-top: 40px;
            text-align: center;
            font-size: 16px;
            color: #666;
        }
    </style>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home-volunteer.php">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home-volunteer.php">Início</a>
                        <a href="search-volunteer.php">Pesquisar</a>
                        <a href="help-volunteer.php">Suporte</a>
                    </ul>
                    <a href="profile-volunteer.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <section class="search-bar">
        <div class="search">
            <a class="back-btn" href="home-volunteer.php">Voltar</a>
            <div class="form">
                <form action="search-volunteer.php" method="GET">
                    <div class="search-container">
                        <input class="search-text" type="text" id="search-input" name="searchTerm" placeholder="Insira o nome da ONG ou título do evento" oninput="showSuggestions(this.value)" value="<?php echo isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : ''; ?>">
                        <div id="suggestions" class="suggestions"></div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="search-btn">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="ong-details">
        <div class="container">
            <div class="image">
                <img src="<?= htmlspecialchars($imgSrc); ?>" alt="Logo da <?= htmlspecialchars($nome_ong); ?>">
            </div>
            <div class="ong-text">
                <h3><?= htmlspecialchars($nome_ong); ?></h3>
                <p>
                    <?php
                    $enderecoCompleto = 
                        ($ongDetails['endereco_rua'] ?? '') . 
                        ', ' . ($ongDetails['endereco_numero'] ?? '') . 
                        (!empty($ongDetails['endereco_complemento']) ? ' - ' . htmlspecialchars($ongDetails['endereco_complemento']) : '') . 
                        ', ' . ($ongDetails['endereco_bairro'] ?? '') . 
                        ', ' . ($ongDetails['endereco_cidade'] ?? '');

                    echo "<strong>Área de Atuação: </strong>" . htmlspecialchars($ongDetails['area_atuacao'] ?? 'N/A') . "<br>" . 
                         "<strong>Data de Fundação: </strong>" . htmlspecialchars($ongDetails['data_fundacao'] ?? 'N/A') . "<br>" . 
                         "<strong>Descrição: </strong>" . htmlspecialchars($ongDetails['descricao'] ?? 'N/A') . "<br>" . 
                         "<strong>Endereço da sede: </strong>" . htmlspecialchars(trim($enderecoCompleto)) ?: 'Endereço não disponível';
                    ?>
                </p>
            </div>
        </div>
    </section>
    <section class="ong-section">
        <div class="ong-left">
            <h3 class="title">ONGs Semelhantes</h3>
            <?php if (!empty($ongsSimilares)): ?>
                <?php foreach ($ongsSimilares as $index => $ong): ?>
                    <div class="ong-item" id="ong<?= $index + 1 ?>" 
                        data-endereco="<?= $ong['endereco_rua'] . ', ' . $ong['endereco_numero'] . ', ' . $ong['endereco_complemento'] . ', ' . $ong['endereco_bairro'] . ', ' . $ong['endereco_cidade'] ?>">
                        <img src="<?= $ong['foto'] ?>" alt="Logo <?= $ong['nome'] ?>" class="ong-logo">
                        <div class="ong-nome">
                            <p><?= $ong['nome'] ?></p>
                            <a href="ongdetails-volunteer.php?title=<?= urlencode($ong['nome']); ?>&imgSrc=<?= urlencode($ong['foto']); ?>" class="ver-mais">Ver mais</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="none">Nenhuma ONG encontrada.</p>
            <?php endif; ?>
        </div>
        <div class="mapBox">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14412.719885890214!2d-49.28112569942132!3d-25.432245990962745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-BR!2sbr!4v1731777822777!5m2!1spt-BR!2sbr" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
    <section class="events">
        <div class="container">
            <h3>Próximos Eventos dessa ONG</h3>
            <div class="event-box">
                <?php if (empty($eventos)): ?>
                    <p class="none">Nenhum evento disponível.</p>
                <?php else: ?>
                    <?php
                    $maxEvents = 6;
                    $eventCount = 0;
                    foreach ($eventos as $evento): 
                        if ($eventCount >= $maxEvents) break;
                    ?>
                        <div class="event-item">
                            <div class="date"><?php echo date('d/m/y', strtotime($evento['data_evento'])); ?></div>
                            <h4><?php echo htmlspecialchars($evento['titulo']); ?></h4>
                            <p><strong>Horário:</strong> <?php echo date('H\hi', strtotime($evento['horario_evento'])); ?></p>
                            <a href="event-details-volunteer.php?titulo=<?php echo urlencode($evento['titulo']); ?>" class="btn-ver-mais">Ver Mais</a>
                        </div>
                    <?php
                        $eventCount++;
                    endforeach;
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script src="ongdetails.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>