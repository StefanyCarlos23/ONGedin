<?php
session_start();
include('connection.php');

$usuarioLogado = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$inscritoNoEvento = false;

if ($usuarioLogado) {
    $sqlInscricao = "SELECT * FROM inscricao_evento WHERE id_usuario = ? AND id_evento = ?";
    $stmtInscricao = $conn->prepare($sqlInscricao);
    $stmtInscricao->bind_param("ii", $usuarioLogado, $eventoId);
    $stmtInscricao->execute();
    $resultInscricao = $stmtInscricao->get_result();
    if ($resultInscricao->num_rows > 0) {
        $inscritoNoEvento = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback'])) {
    $feedback = htmlspecialchars($_POST['feedback']);
    $usuarioId = $_SESSION['id_usuario'];
    $eventoId = $_GET['id_evento'];

    $sql = "INSERT INTO feedbacks (id_usuario, id_evento, feedback) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $usuarioId, $eventoId, $feedback);

    if ($stmt->execute()) {
        echo "Feedback enviado com sucesso!";
        header('Location: event-details.php?titulo=' . urlencode($eventoId));
        exit;
    } else {
        echo "Erro ao enviar o feedback. Tente novamente.";
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

$eventDetails = null;

function getEventDetails($conn, $titulo) {
    $queryEvent = $conn->prepare("
        SELECT e.titulo, e.descricao, e.local_rua, e.local_numero, e.local_complemento, e.local_bairro, 
            e.local_cidade, e.local_estado, e.local_pais, 
            ae.data_evento, ae.horario_evento, ao.area_atuacao, ao.endereco_rua, ao.endereco_bairro, ao.endereco_cidade,
            u.nome, ao.id_admin_ong
        FROM evento e
        JOIN admin_ong_cadastra_evento ae ON e.id_evento = ae.id_evento
        JOIN administrador_ong ao ON ae.id_admin_ong = ao.id_admin_ong
        JOIN usuario u ON u.id_usuario = ao.id_admin_ong
        WHERE e.titulo = ?");
    $queryEvent->bind_param('s', $titulo);
    $queryEvent->execute();
    return $queryEvent->get_result()->fetch_assoc();
}

if (isset($_GET['titulo'])) {
    $eventDetails = getEventDetails($conn, $_GET['titulo']);
}

$horario = new DateTime($eventDetails['horario_evento']);
$dataEvento = DateTime::createFromFormat('Y-m-d', $eventDetails['data_evento']);

function getOngImage($conn, $adminOngId) {
    $sql = "SELECT p.foto, u.nome AS nome_ong
            FROM perfil p
            JOIN usuario u ON p.id_perfil = u.id_usuario
            JOIN administrador_ong ao ON u.id_usuario = ao.id_admin_ong
            WHERE ao.id_admin_ong = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $adminOngId);
    $stmt->execute();
    $perfil = $stmt->get_result()->fetch_assoc();

    if (!$perfil || !$perfil['foto']) {
        $perfil['foto'] = 'caminho/para/imagem/alternativa.jpg';
    }

    return $perfil;
}

if ($eventDetails) {
    $adminOngId = $eventDetails['id_admin_ong'];
    $ongDetails = getOngImage($conn, $adminOngId);

    $fotoOng = $ongDetails['foto'];
    $nomeOng = htmlspecialchars($ongDetails['nome_ong']);
}

$nomeEvento = $_GET['titulo'];

$sql_evento = "SELECT id_evento FROM evento WHERE titulo = ?";
$stmt_evento = $conn->prepare($sql_evento);
$stmt_evento->bind_param("s", $nomeEvento);
$stmt_evento->execute();
$result_evento = $stmt_evento->get_result();

if ($result_evento->num_rows > 0) {
    $row = $result_evento->fetch_assoc();
    $eventoId = $row['id_evento'];
} else {
    echo "Erro: Evento não encontrado!";
    exit;
}

$sql_ong = "SELECT ong.area_atuacao 
            FROM administrador_ong ong
            JOIN admin_ong_cadastra_evento aoce ON aoce.id_admin_ong = ong.id_admin_ong
            JOIN evento e ON e.id_evento = aoce.id_evento
            WHERE e.id_evento = ?";
$stmt = $conn->prepare($sql_ong);
$stmt->bind_param("i", $eventoId);
$stmt->execute();
$result = $stmt->get_result();

$areaAtuacao = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $areaAtuacao = $row['area_atuacao'];
} else {
    echo "Evento não encontrado!";
    exit;
}

$sql_eventos_similares = "SELECT e.id_evento, e.titulo 
                          FROM evento e
                          JOIN admin_ong_cadastra_evento aoce ON aoce.id_evento = e.id_evento
                          JOIN administrador_ong ong ON ong.id_admin_ong = aoce.id_admin_ong
                          WHERE ong.area_atuacao = ? AND e.id_evento != ?";
$stmt_similares = $conn->prepare($sql_eventos_similares);
$stmt_similares->bind_param("si", $areaAtuacao, $eventoId);
$stmt_similares->execute();
$result_similares = $stmt_similares->get_result();

$eventosSimilares = [];
while ($evento = $result_similares->fetch_assoc()) {
    $eventosSimilares[] = $evento;
}

$titulo_evento = $_GET['titulo'];

$sqlEvento = "SELECT id_evento FROM evento WHERE titulo = ?";
$stmtEvento = $conn->prepare($sqlEvento);
$stmtEvento->bind_param("s", $titulo_evento);
$stmtEvento->execute();
$resultEvento = $stmtEvento->get_result();
$id_evento = "";

if ($resultEvento->num_rows > 0) {
    $rowEvento = $resultEvento->fetch_assoc();
    $id_evento = $rowEvento['id_evento'];
} else {
    echo "Evento não encontrado.";
    exit;
}

$sqlFeedbacks = "SELECT u.nome AS usuario, a.data_avaliacao AS data, a.nota, a.comentario
                FROM avaliacao a
                JOIN voluntario v ON a.id_voluntario = v.id_voluntario
                JOIN usuario u ON u.id_usuario = a.id_voluntario
                WHERE a.id_evento = ?";
$stmtFeedbacks = $conn->prepare($sqlFeedbacks);
$stmtFeedbacks->bind_param("i", $id_evento);
$stmtFeedbacks->execute();
$resultFeedbacks = $stmtFeedbacks->get_result();

$feedbacks = [];
while ($rowFeedback = $resultFeedbacks->fetch_assoc()) {
    $feedbacks[] = $rowFeedback;
}

$stmtEvento->close();
$stmtFeedbacks->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Detalhes do Evento</title>
    <link href="event-details.css" rel="stylesheet">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
        outline: none;
    }

    body{
        background-color: #F6F6F6;
        font-family: "Inter", sans-serif;
    }

    nav{
        background-color: #F6F6F6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 35px;
        padding-left: 20px;
        border-bottom: 3px solid #87BFC7;
    }

    nav ul{
        display: flex;
        align-items: center;
    }

    nav ul .ul-text a{
        color: rgb(0, 0, 0);
        margin: 0 20px;
        font-size: 18px;
        display: block;
    }

    nav ul .ul-text a:not(.btn)::after{
        content: "";
        background-color: rgb(0, 0, 0);
        height: 2px;
        width: 0;
        display: block;
        margin: 0 auto;
        transition: 0.3s;
    }

    nav ul .ul-text a:hover::after{
        width: 100%;
    }

    nav a img{
        width: 90px;
        height: 90px;
    }

    nav ul a img{
        width: 40px;
        height: 40px;
        margin: 0 15px;
    }

    .search{
        padding-top: 70px;
        padding-bottom: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 18px;
    }

    .search .search-text{
        margin: 0 20px;
        padding: 8px;
        width: 600px;
        font-size: 16px;
        border: #8A8A8A solid;
        border-width: 1px;
        border-radius: 10px;
    }

    .search a{
        margin: 0 20px;
    }

    form{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-container{
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .search-text {
        width: 100%;
        box-sizing: border-box;
    }

    .suggestions {
        color: #666666;
        display: none;
        flex-direction: column;
        position: absolute;
        top: 47px;
        width: 600px;
        max-height: 200px;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        overflow-y: auto;
    }

    .suggestion-item{
        padding: 10px;
        cursor: pointer;
    }

    .suggestion-item:hover{
        background-color: #f0f0f0;
    }

    .button-container{
        display: flex;
        justify-content: flex-end;
    }

    button{
        margin: 0 20px;
    }

    .search-btn{
        display: block;
        padding: 10px 15px;
        background-color: #87BFC7;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 10px;
        transition: color 0.1s;
        border: 1px solid #87BFC7;
        cursor: pointer;
    }

    .search-bar .search .back-btn{
        padding: 10px 15px;
        background-color: #b9b9b9;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 10px;
        transition: color 0.1s;
        border: 1px solid #b9b9b9;
    }

    .search-bar .search .search-btn:active{
        color: #666666;
        background-color: #4d909a;
    }

    .search-bar .search .search-btn:hover {
        background-color: #4d909a;
        transition: 0.4s;
    }

    .search-bar .search .back-btn:active{
        color: #666666;
    }

    .search-bar .search .back-btn:hover{
        background-color: #909090;
        transition: 0.4s;
    }

    .event-details {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        margin: 30px auto 70px;
        width: 80%;
    }

    .event-details .container {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .event-layout {
        display: flex;
        gap: 30px;
        align-items: center;
        justify-content: flex-start;
    }

    .event-left {
        flex: 1;
        text-align: center;
    }

    .event-left .ong {
        margin-bottom: 15px;
        font-size: 18px;
        color: #666666;
    }

    .event-left .image-box {
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
        margin-top: 20px;
    }

    .event-left .image-box img {
        width: 60%;
        object-fit: cover;
        margin: 0 auto;
    }

    .event-right {
        flex: 2;
        text-align: left;
        padding-left: 50px;
    }

    .event-right .nome-ong {
        margin-bottom: 20px;
        color: #87BFC7;
        font-size: 28px;
    }

    .event-details-list p {
        margin-bottom: 15px;
        font-size: 16px;
        line-height: 1.5;
        color: #666666;
    }

    .event-button {
        display: flex;
        justify-content: center;
    }

    .event-details .event-button .btn {
        display: flex;
        align-items: center;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #87BFC7;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        transition: color 0.1s;
        white-space: nowrap;
        border: none;
        font-size: 18px;
        cursor: pointer;
    }

    .event-details .event-button .btn:hover {
        background-color: #4d909a;
        transition: 0.4s;
    }

    .event-details .event-button .btn:active {
        color: #666666;
        background-color: #4d909a;
    }

    .more {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 auto;
        margin-bottom: 80px;
        width: 84%;
    }

    .more .event-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        flex: 1;
    }

    .more .event-left .title{
        font-size: 20px;
        color: #666666;
    }

    .more .event-left .none {
        margin-top: 40px;
        text-align: center;
        font-size: 16px;
        color: #666;
    }

    .event-item {
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

    .event-nome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        margin-left: 20px;
        white-space: nowrap;
        width: 100%;
        margin-top: 20px;
    }

    .event-nome p {
        color: #666666;
        font-size: 16px;
        margin-right: 10px;
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

    .more .event-right {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        flex: 2;
        color: #666666;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .more .event-right h3 {
        font-weight: bold;
        text-align: center;
        color: #87BFC7;
        font-size: 28px;
    }

    .rating-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        gap: 20px;
        width: 100%;
        padding: 20px;
    }

    .rating {
        width: 100%;
    }

    .rating label {
        display: block;
        font-size: 18px;
        color: #555;
        margin-bottom: 10px;
    }

    .rating select {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #fff;
        color: #333;
        appearance: none;
        cursor: pointer;
    }

    textarea {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 6px;
        resize: none;
        min-height: 100px;
    }

    .more .btn {
        width: 100%;
        padding: 12px;
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        background-color: #4CAF50;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .more .btn:hover {
        background-color: #45a049;
    }

    footer{
        border-top: 3px solid #87BFC7;
        background-color: #F6F6F6;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 90px;
        text-align: center;
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
    </header>

    <section class="search-bar">
        <div class="search">
            <a class="back-btn" href="home.php">Voltar</a>
            <div class="form">
                <form action="search.php" method="GET">
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

    <section class="event-details">
        <div class="container">
            <?php if ($eventDetails): ?>
                <div class="event-layout">
                    <div class="event-left">
                        <p class="ong"><strong>ONG responsável:</strong> <?php echo htmlspecialchars($eventDetails['nome']); ?></p>
                        <div class = "image-box">
                            <img class="ong-image" src="<?= $fotoOng; ?>" alt="Logo da <?= $nomeOng; ?>">
                        </div>
                        <div class="event-button">
                            <button class="btn" id="subscribe-btn" onclick="subscribeEvent()">Inscrever-se</button>
                        </div>
                    </div>
                    <div class="event-right">
                        <h3 class="nome-ong"><?php echo htmlspecialchars($eventDetails['titulo']); ?></h3>
                        <div class="event-details-list">
                            <p><strong>Data:</strong> <?php echo $dataEvento->format('d/m/Y'); ?></p>
                            <p><strong>Horário:</strong> <?php echo $horario->format('H\hi'); ?></p>
                            <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($eventDetails['descricao'])); ?></p>
                            <p><strong>Local:</strong>
                                <?php 
                                    echo htmlspecialchars($eventDetails['local_rua']) . ", " . 
                                        htmlspecialchars($eventDetails['local_numero']);
                                    if ($eventDetails['local_complemento']) {
                                        echo " - " . htmlspecialchars($eventDetails['local_complemento']);
                                    }
                                    echo ", " . htmlspecialchars($eventDetails['local_bairro']) . ", " . 
                                        htmlspecialchars($eventDetails['local_cidade']) . " - " . 
                                        htmlspecialchars($eventDetails['local_estado']) . ", " . 
                                        htmlspecialchars($eventDetails['local_pais']);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p>Evento não encontrado.</p>
            <?php endif; ?>
        </div>
    </section>
    <section class="more">
        <div class="event-left">
            <h3 class="title">Eventos Parecidos</h3>
            <?php if (!empty($eventosSimilares)): ?>
                <?php foreach ($eventosSimilares as $index => $evento): ?>
                    <div class="event-item" id="evento<?= $index + 1 ?>">
                        <div class="event-nome">
                            <p><?= $evento['titulo'] ?></p>
                            <a href="event-details.php?titulo=<?= urlencode($evento['titulo']); ?>" class="ver-mais">Ver mais</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="none">Nenhum evento encontrado.</p>
            <?php endif; ?>
        </div>

        <div class="event-right">
            <h3>Feedbacks</h3>
            <div class="feedback-container">
                <?php if (!empty($feedbacks)): ?>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <div class="feedback-item">
                            <p><strong>Nome do Usuário:</strong> <?= htmlspecialchars($feedback['nome']) ?></p>
                            <p><strong>Data da avaliação:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($feedback['data']))) ?></p>
                            <p><strong>Nota:</strong> <?= htmlspecialchars($feedback['nota']) ?></p>
                            <p><strong>Comentário:</strong> <?= nl2br(htmlspecialchars($feedback['comentario'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="none">Nenhum feedback disponível.</p>
                <?php endif; ?>
            </div>

            <div class="feedback-button-container">
                <button class="btn-feedback" id="feedback-btn" onclick="submitFeedback()">Realizar Feedback</button>
            </div>
        </div>
    </section>
    <script src="event-details.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>