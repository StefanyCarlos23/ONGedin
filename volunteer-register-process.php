<?php
   include('connection.php');
    echo "Conexão estabelecida.";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "Método POST recebido.";

        $name = $_POST['name'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $birthDate = $_POST['date-birth'] ?? '';
        $telephone = $_POST['telephone'] ?? ''; 
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPass = $_POST['confirm-pass'] ?? '';

        if (!empty($birthDate)) {
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $birthDate, $matches)) {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3];
    
                if (checkdate($month, $day, $year)) {
                    $birthDate = "$year-$month-$day";
                } else {
                    die("Erro: Data inválida.");
                }
            } else {
                die("Erro: Formato de data inválido. Use DD/MM/YYYY.");
            }
        } else {
            die("Erro: Data do evento não foi fornecida.");
        }
        
        $stmt = $conn->prepare("INSERT INTO usuario (nome, funcao, senha, data_cadastro) VALUES (?, ?, ?, NOW())");

        if (!$stmt) {
            die("Erro ao preparar a consulta de usuário: " . $conn->error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $funcao = 'C';
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

            $stmt = $conn->prepare("INSERT INTO contribuinte (id_contribuinte, cpf, data_nascimento) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Erro ao preparar a consulta de contribuinte: " . $conn->error);
            }
            $stmt->bind_param("iss", $id_usuario, $cpf, $birthDate);

            if ($stmt->execute()) {
                echo "Cadastro realizado com sucesso!";
            } else {
                die("Erro ao cadastrar contribuinte: " . $stmt->error);
                exit;
            }

        } else {
            die("Erro ao cadastrar usuário: " . $stmt->error);
        }

        $stmt->close();
        }
    $conn->close();
    header("Location: home-volunteer.php");
    exit;
?>