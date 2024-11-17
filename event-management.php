<?php
session_start();
include('connection.php');

$query = "SELECT titulo, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais, data_evento, id_evento FROM evento ORDER BY data_evento ASC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $_SESSION['eventos'] = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $_SESSION['eventos'] = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Gerencimento de Eventos</title>
    <link href="event-management.css" rel="stylesheet">

</head>
<body>

    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home-ong.php">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home-ong.php">Início</a>
                        <a href="search-ong.php">Pesquisar</a>
                        <a href="donations-ong.php">Doações</a>
                        <a href="manage_event.php">Gerenciamento de Eventos</a>
                        <a href="report.php">Relatório</a>
                        <a href="help-ong.php">Suporte</a>
                    </ul>
                    <a href="profile-ong.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
        <nav class="mobile-nav">
            <a href="home-ong.php">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="home-ong.php">Início</a>
                <li><a href="search-ong.php">Pesquisar</a></li>
                <li><a href="donations-ong.php">Doações</a></li>
                <li><a href="manage_event.php">Gerenciamento <BR> de Eventos</a></li>
                <li><a href="report.php">Relatório</a></li>
                <li><a href="help-ong.php">Suporte</a></li>
                <li><a href="profile-ong.php">Perfil</a></li>
            </ul>
            </nav>
    </header>

    <div class="content">
        <div class="box-content">
            <div class="btn-back">
                <a class="back-btn" href="home-ong.php">Voltar</a>
            </div>

            <div class="title">
                <h2>Gerenciamento de Eventos</h2>
            </div>

            <div class="subtitle">
                <h3>Eventos Futuros</h3>
            </div>

            <div class="table-position">
                <table class="table-style">
                    <thead class="table-title">
                        <tr>
                            <th scope="col">Título</th>
                            <th scope="col">Local</th>
                            <th scope="col">Data do evento</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (isset($_SESSION['eventos']) && !empty($_SESSION['eventos'])) {
                            foreach ($_SESSION['eventos'] as $eventData) {
                                $dataEvento = DateTime::createFromFormat('Y-m-d', $eventData['data_evento']);
                                $dataFormatada = $dataEvento ? $dataEvento->format('d/m/Y') : '';

                                echo "<tr>";
                                echo "<td>" . $eventData['titulo'] . "</td>";
                                echo "<td>" . $eventData['local_rua'] . ", " . $eventData['local_numero'] . " - " . $eventData['local_bairro'] . ", " . $eventData['local_cidade'] . ", " . $eventData['local_estado'] . ", " . $eventData['local_pais'] . "</td>";
                                echo "<td>" . $dataFormatada . "</td>";
                                echo "<td>
                                
                                <a href='event-edit.php?titulo=" . urlencode($eventData['titulo']) . "&id=" . $eventData['id_evento'] . "'>
                                    <img src='images/lapis-icon.png' alt='Editar' style='width: 30px; height: 30px;'>
                                </a> | 
                                
                                <a href='event-delete.php?titulo=" . urlencode($eventData['titulo']) . "&id=" . $eventData['id_evento'] . "'>
                                    <img src='images/delete-icon.png' alt='Deletar' style='width: 30px; height: 30px;'>
                                </a>
                              </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Nenhum evento encontrado.</td></tr>";
                        }
                    ?>

                    </tbody>
                </table>

            </div>

            <div class="event-register">
                <a class="register-event" href="event-register.php">Cadastrar Evento</a>
            </div>

        </div>
    </div>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="event-management.js"> </script>
