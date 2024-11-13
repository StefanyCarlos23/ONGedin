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
                   e.local_bairro, e.local_cidade, aoc.data_evento 
            FROM evento e 
            INNER JOIN admin_ong_cadastra_evento aoc ON e.id_evento = aoc.id_evento 
            WHERE aoc.id_admin_ong = ?
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

        .ong-details .container{
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            margin: 30px auto;
            margin-top: 50px;
            width: 80%;
        }

        .ong-details .container .image{
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
        }

        .events .container h3 {
            margin-bottom: 40px;
            color: #87BFC7;
            font-size: 30px;
            margin-top: 20px
        }

        .event-box {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 15px;
            text-align: center;
            margin-top: 30px;
            background-color: #e0e0e0;
            border-radius: 25px;
            padding: 30px;
        }

        .event-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .event-item:hover {
            transform: translateY(-5px);
            background-color: #87BFC7;
            color: white;
        }

        .event-item .date {
            color: #666666;
        }

        .event-item h4 {
            font-size: 16px;
            font-weight: bold;
            color: #666666;
        }

        .event-item p {
            font-size: 14px;
            color: #666666;
        }

        .event-item .date {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .event-item .description {
            font-size: 14px;
            color: #777;
        }

        .event-box .event-item:nth-child(7n) {
            background-color: #e3e3e3;
        }

        .ong-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .ong-left {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
            justify-content: center;
            height: 100%;
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
            flex-direction: row;
            align-items: center;
            text-align: center;
            margin-left: 20px;
        }

        .ong-nome p {
            color: #666666;
            font-size: 16px;
        }

        .ong-logo {
            width: 100px;
            height: auto;
            margin-right: 10px;
        }

        .ver-mais {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .ver-mais:hover {
            background-color: #0056b3;
        }

        .map-right {
            width: 65%;
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
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
                         "<strong>Endereço: </strong>" . htmlspecialchars(trim($enderecoCompleto)) ?: 'Endereço não disponível';
                    ?>
                </p>
            </div>
        </div>
    </section>
    <section class="ong-section">
        <div class="ong-left">
            <h3 class="title">ONGs Semelhantes</h3>
            <?php foreach ($ongsSimilares as $index => $ong): ?>
                <div class="ong-item" id="ong<?= $index + 1 ?>" data-endereco="<?= $ong['endereco_rua'] . ', ' . $ong['endereco_numero'] . ', ' . $ong['endereco_complemento'] . ', ' . $ong['endereco_bairro'] . ', ' . $ong['endereco_cidade'] ?>">
                    <img src="<?= $ong['foto'] ?>" alt="Logo <?= $ong['nome'] ?>" class="ong-logo">
                    <div class="ong-nome">
                        <p><?= $ong['nome'] ?></p>
                        <button class="ver-mais" onclick="verMais(<?= $ong['id_usuario'] ?>)">Ver mais</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="map-right">
            <div id="map"></div>
        </div>
    </section>
    <section class="events">
        <div class="container">
            <h3>Próximos Eventos dessa ONG</h3>
            <div class="event-box">
                <?php if (empty($eventos)): ?>
                    <p>Nenhum evento disponível</p>
                <?php else: ?>
                    <?php foreach ($eventos as $evento): ?>
                        <div class="event-item">
                            <div class="date"><?php echo date('d/m', strtotime($evento['data_evento'])); ?></div>
                            <h4><?php echo htmlspecialchars($evento['titulo']); ?></h4>
                            <a href="eventdetails.php?titulo=<?php echo urlencode($evento['titulo']); ?>" class="btn-ver-mais">Ver Mais</a>
                        </div>
                    <?php endforeach; ?>
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