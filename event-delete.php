<?php
session_start();
include('connection.php');

if (!isset($_SESSION['id_admin_ong'])) {
    die("Erro: Você precisa estar logado para cadastrar um evento.");
}

$idAdminOng = $_SESSION['id_admin_ong'];

$title = $description = $eventDate = $road = $num = $neighborhood = $city = $state = $country = $complement = "";

if (!empty($_GET['titulo'])) {

    $TITLE = isset($_GET['titulo']) ? htmlspecialchars($_GET['titulo']) : '';

    $sqlSelect = "SELECT * FROM evento WHERE titulo = ?";
    $stmt = $conn->prepare($sqlSelect);
    $stmt->bind_param('s', $TITLE);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $sqlDeleteRel = "DELETE FROM admin_ong_cadastra_evento WHERE id_evento = (SELECT id_evento FROM evento WHERE titulo = ?)";
        $stmtDeleteRel = $conn->prepare($sqlDeleteRel);
        $stmtDeleteRel->bind_param('s', $TITLE);
        $stmtDeleteRel->execute();

        $sqlDelete = "DELETE FROM evento WHERE titulo = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param('s', $TITLE);

        if ($stmtDelete->execute()) {
            header("Location: event-management.php?success=true");
            echo "Evento excluído com sucesso.";
        } else {
            echo "Erro ao excluir o evento.";
        }
    } else {
        echo "Nenhum evento encontrado com esse título.";
    }
} else {
    echo "Título não especificado.";
}

$conn->close();
?>
