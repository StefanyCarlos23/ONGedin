<?php
session_start();
include('connection.php');

if (!isset($_SESSION['id_admin_ong'])) {
    die("Erro: Você precisa estar logado para cadastrar um evento.");
}

$idAdminOng = $_SESSION['id_admin_ong'];

if (isset($_POST['update'])) {
    $title = ($_POST['title']);
    $description = ($_POST['description']);
    $eventDate = ($_POST['event-date']);
    $road = ($_POST['road']);
    $num = ($_POST['num']);
    $neighborhood = ($_POST['neighborhood']);
    $city = ($_POST['city']);
    $state = ($_POST['state']);
    $country = ($_POST['country']);
    $complement = ($_POST['complement']);

    $dateTime = DateTime::createFromFormat('d/m/Y', $eventDate);
    if ($dateTime === false) {
        die("Erro: Data inválida. Use o formato DD/MM/YYYY.");
    }
    $formattedDate = $dateTime->format('Y-m-d');

    $sqlUpdate = "UPDATE evento 
                  SET titulo = ?, descricao = ?, data_evento = ?, local_rua = ?, 
                      local_numero = ?, local_bairro = ?, local_cidade = ?, 
                      local_estado = ?, local_pais = ?, local_complemento = ?
                  WHERE titulo = ?";
    
    if ($stmt = $conn->prepare($sqlUpdate)) {
        $stmt->bind_param('sssssssssss', $title, $description, $formattedDate, $road, $num, $neighborhood, $city, $state, $country, $complement, $title);
        
        if ($stmt->execute()) {
            header("Location: event-management.php?success=true");
            exit;
        } else {
            echo "Erro ao editar o evento: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erro ao preparar a consulta: " . $conn->error;
    }
}

$conn->close();
?>
