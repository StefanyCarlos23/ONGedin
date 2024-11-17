<?php
session_start();
include('connection.php');

if (!isset($_SESSION['id_admin_ong'])) {
    die("Erro: Você precisa estar logado para cadastrar um evento.");
}

$idAdminOng = $_SESSION['id_admin_ong'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $eventDate = $_POST['event-date'] ?? '';
    $road = $_POST['road'] ?? '';
    $num = $_POST['num'] ?? '';
    $neighborhood = $_POST['neighborhood'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $country = $_POST['country'] ?? '';
    $complement = $_POST['complement'] ?? '';

    if (!empty($eventDate)) {
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $eventDate, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];

            if (checkdate($month, $day, $year)) {
                $eventDate = "$year-$month-$day";
            } else {
                die("Erro: Data inválida.");
            }
        } else {
            die("Erro: Formato de data inválido. Use DD/MM/YYYY.");
        }
    } else {
        die("Erro: Data do evento não foi fornecida.");
    }

    $stmt = $conn->prepare("INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais, data_evento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("ssssssssss", $title, $description, $road, $num, $complement, $neighborhood, $city, $state, $country, $eventDate);

    if ($stmt->execute()) {
        $eventId = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento) VALUES (?, ?)");
        $stmt2->bind_param("ii", $idAdminOng, $eventId);

        if ($stmt2->execute()) {
            $_SESSION['message'] = "Evento cadastrado com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao associar o evento ao administrador: " . $stmt2->error;
        }

        $stmt2->close();
    } else {
        $_SESSION['message'] = "Erro ao cadastrar o evento: " . $stmt->error;
    }

    $stmt->close();

    header("Location: event-management.php");
    exit;
}

$conn->close();
?>
