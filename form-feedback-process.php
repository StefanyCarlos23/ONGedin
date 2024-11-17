<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $description = $_POST['description'] ?? null;
    $id_voluntario = $_SESSION['id_voluntario'];
    $id_evento = $_POST['id_evento'];


    $stmt = $conn->prepare(
        "INSERT INTO avaliacao (id_voluntario, id_evento, nota, comentario, data_avaliacao) 
         VALUES (?, ?, ?, ?, NOW())"
    );

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("iiis", $id_voluntario, $id_evento, $rating, $description);

    if ($stmt->execute()) {
        echo "Feedback enviado com sucesso! Obrigado por compartilhar sua opinião.";
    } else {
        echo "Erro: Não foi possível enviar o feedback. Tente novamente mais tarde.";
    }

    $stmt->close();
    $conn->close();
}
?>
