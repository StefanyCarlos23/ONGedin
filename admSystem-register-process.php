<?php
    include('connection.php');
    echo "Conexão estabelecida.";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "Método POST recebido.";

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPass = $_POST['confirm-pass'] ?? '';

        $stmt = $conn->prepare("INSERT INTO usuario (nome, funcao, senha, data_cadastro) VALUES (?, ?, ?, NOW())");
        
        if (!$stmt) {
            die("Erro ao preparar a consulta de usuário" . $conn->error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $funcao = 'A';
        $stmt->bind_param("sss", $name, $funcao, $hashedPassword);

        if ($stmt->execute()) {
            echo "Usuário cadastrado com sucesso!";
            $id_usuario = $conn->insert_id;

            $stmt = $conn->prepare("INSERT INTO contato (id_usuario, telefone, email) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Erro ao preparar a consulta de contato: " . $conn->error);
            }
            $stmt->bind_param("iss",$id_usuario, $telephone, $email);

            if ($stmt->execute()) {
                echo "Contato cadastrado com sucesso!";
            } else {
                echo "Erro ao cadastrar contato" . $stmt->error;
                exit;
            }

        } else {
            echo "Erro ao cadastrar usuário" . $stmt->error;
            exit;
        }

        $stmt->close();
    }

    $conn->close();
    header("Location: home-adm.php");
    exit;
?>