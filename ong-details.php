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

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $suggestions = getSuggestions($conn, $term);
    echo json_encode($suggestions);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Detalhes da ONG</title>
    <link href="ong-details.css" rel="stylesheet" >
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
            <div class="text-suggestions">
                <input class="search-text" type="text" id="search-input" placeholder="Insira o nome da ONG ou título do evento" oninput="showSuggestions(this.value)">
                <div id="suggestions" class="suggestions"></div>
            </div>
            <a class="search-btn" href="search.php">Buscar</a>
        </div>
    </section>
    <script src="ong-details.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>