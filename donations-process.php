<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $selectONG = $_POST['select-ong'] ?? '';
    $donationType = $_POST['tipo-doacao'] ?? '';
    $value = $_POST['valor'] ?? '';
    $method = $_POST['metodo'] ?? '';
    $amountFood = $_POST['quantidade-alimentos'] ?? '';
    $amountClothing = $_POST['quantidade-roupas'] ?? '';

    $queryVerificarONG = "SELECT id_admin_ong FROM administrador_ong WHERE id_admin_ong = ?";
    $stmt = $conn->prepare($queryVerificarONG);
    $stmt->bind_param("i", $selectONG);
    $stmt->execute();
    $stmt->store_result();

    var_dump($selectONG);


    if ($stmt->num_rows == 0) {
        die("Erro: A ONG selecionada não existe.");
    }
    $stmt->close();

    if ($donationType === 'dinheiro') {
        $quantidade = $value;
    } elseif ($donationType === 'alimentos') {
        $quantidade = $amountFood;
    } elseif ($donationType === 'roupas') {
        $quantidade = $amountClothing;
    } else {
        die("Erro: Tipo de doação inválido.");
    }

    if (!$quantidade || $quantidade <= 0) {
        die("Erro: Quantidade inválida para o tipo de doação.");
    }

    $statusDoacao = 'pendente';
    $dataRecebe = date('Y-m-d H:i:s');

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare(
            "INSERT INTO doacao (id_admin_ong, item_doado, quantidade_item, status_doacao, data_recebe) 
             VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta de doação: " . $conn->error);
        }

        $stmt->bind_param("isiss", $selectONG, $donationType, $quantidade, $statusDoacao, $dataRecebe);
        $stmt->execute();
        $idDoacao = $conn->insert_id;

        $idDoador = null;  
        $idVoluntario = null;

        $stmt = $conn->prepare(
            "INSERT INTO realiza (id_doador, id_voluntario, id_doacao, data_realiza) 
             VALUES (?, ?, ?, ?)"
        );
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta de realiza: " . $conn->error);
        }
        $stmt->bind_param("iiis", $idDoador, $idVoluntario, $idDoacao, $dataRecebe);
        $stmt->execute();

        $conn->commit();

        echo "Doação registrada com sucesso!";
        header("Location: home-volunteer.php?alert=success&message=Doação registrada com sucesso");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: home-volunteer.php?alert=error&message=" . urlencode($e->getMessage()));
    exit();
        echo "Erro: " . $e->getMessage();

    } finally {
        $stmt->close();
        $conn->close();
    }
}

?>