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
        $eventos = [];
    }
} else {
    $ongDetails = [];
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
        nav ul a img {
            width: 35px;
            height: 35px;
            margin: 0 15px;
        }

        .events {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 50px 20px;
            background-color: #fff;
        }

        .events .container {
            width: 90%;
            max-width: 1200px;
            text-align: center;
        }

        h3 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .event-box {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 15px;
            text-align: center;
            margin-top: 30px;
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

        .event-item h4 {
            font-size: 16px;
            font-weight: bold;
        }

        .event-item p {
            font-size: 14px;
            color: #666;
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

        /* Estilo para os dias do calendário */
        .event-box .event-item:nth-child(7n) {
            background-color: #e3e3e3; /* Lighter background for weekends */
        }

        /* Responsividade para telas pequenas */
        @media screen and (max-width: 768px) {
            .event-box {
                grid-template-columns: repeat(4, 1fr); /* 4 colunas em telas menores */
            }

            .event-item {
                font-size: 14px; /* Menor texto em telas pequenas */
                padding: 10px;
            }

            .event-item h4 {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 480px) {
            .event-box {
                grid-template-columns: repeat(3, 1fr); /* 3 colunas em telas muito pequenas */
            }
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
                            <div class="date"><?php echo date('d/m', strtotime($evento['data_evento'])); ?></div>
                            <h4><?php echo htmlspecialchars($evento['titulo']); ?></h4>
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
    <script src="ongdetails.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>