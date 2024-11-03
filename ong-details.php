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

$nome_ong = $_GET['nome_ong'] ?? null;

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

        $stmt = $conn->prepare("
            SELECT e.titulo, e.descricao, 
                   e.local_rua, e.local_numero, e.local_complemento, 
                   e.local_bairro, e.local_cidade, aoc.data_evento 
            FROM evento e 
            INNER JOIN admin_ong_cadastra_evento aoc ON e.id_evento = aoc.id_evento 
            WHERE aoc.id_admin_ong = ?
        ");
        $stmt->bind_param('i', $id_ong);
        $stmt->execute();
        $eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $ongDetails = [];
        $eventos = [];
    }
} else {
    $ongDetails = [];
    $eventos = [];
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
            <a class="back-btn" href="search.php">Voltar</a>
            <div class="text-suggestions">
                <input class="search-text" type="text" id="search-input" placeholder="Insira o nome da ONG ou título do evento" oninput="showSuggestions(this.value)">
                <div id="suggestions" class="suggestions"></div>
            </div>
            <a class="search-btn" href="search.php">Buscar</a>
        </div>
    </section>
    <section class="ong-details">
        <div class="container">
            <div class="image">
                <img src="<?php echo htmlspecialchars($ongDetails['foto'] ?? 'images/default-logo.png'); ?>" alt="Logo da ONG">
            </div>
            <div class="ong-text">
                <h3><?php echo htmlspecialchars($nome_ong); ?></h3>
                <p>
                    <?php
                    $enderecoCompleto = 
                        ($ongDetails['endereco_rua'] ?? '') . 
                        ', ' . ($ongDetails['endereco_numero'] ?? '') . 
                        (!empty($ongDetails['endereco_complemento']) ? ' - ' . htmlspecialchars($ongDetails['endereco_complemento']) : '') . 
                        ', ' . ($ongDetails['endereco_bairro'] ?? '') . 
                        ', ' . ($ongDetails['endereco_cidade'] ?? '');

                    echo "Área de Atuação: " . htmlspecialchars($ongDetails['area_atuacao'] ?? 'N/A') . "<br>" .
                        "Data de Fundação: " . htmlspecialchars($ongDetails['data_fundacao'] ?? 'N/A') . "<br>" .
                        "Descrição: " . htmlspecialchars($ongDetails['descricao'] ?? 'N/A') . "<br>" .
                        "Endereço: " . htmlspecialchars(trim($enderecoCompleto)) ?: 'Endereço não disponível';
                    ?>
                </p>
            </div>
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
                            <h4><?php echo htmlspecialchars($evento['titulo']); ?></h4>
                            <p>Data: <?php echo htmlspecialchars($evento['data_evento']); ?></p>
                            <p><?php echo htmlspecialchars($evento['descricao']); ?></p>
                            <p>Local: <?php echo htmlspecialchars($evento['local_rua']) . ', ' . 
                                        htmlspecialchars($evento['local_numero']) . 
                                        (!empty($evento['local_complemento']) ? ' - ' . htmlspecialchars($evento['local_complemento']) : ''); ?></p>
                            <p><?php echo htmlspecialchars($evento['local_bairro']) . ', ' . 
                                        htmlspecialchars($evento['local_cidade']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script src="ong-details.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>