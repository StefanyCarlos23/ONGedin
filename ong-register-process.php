<?php

session_start();

    include('connection.php');
    echo "Conexão estabelecida.";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "Método POST recebido.";

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $areaActivity = $_POST['area-activity'] ?? '';
        $fundationDate = $_POST['fundation-date'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $socialMedia = $_POST['social-media'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPass = $_POST['confirm-pass'] ?? '';
        $CEP = $_POST['CEP'] ?? '';
        $road = $_POST['road'] ?? '';
        $num = $_POST['num'] ?? '';
        $neighborhood = $_POST['neighborhood'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $country = $_POST['country'] ?? '';
        $complement = $_POST['complement'] ?? '';

        if (!empty($fundationDate)) {
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fundationDate, $matches)) {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3];
    
                if (checkdate($month, $day, $year)) {
                    $fundationDate = "$year-$month-$day";
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
        $funcao = 'O';
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

            $stmt = $conn->prepare("INSERT INTO administrador (id_administrador) VALUES (?)");
            if (!$stmt) {
                die("Erro ao preparar a consulta de administrador: " . $conn->error);
            }
            $stmt->bind_param("i", $id_usuario);

            if ($stmt->execute()) {
                echo "Administrador cadastrado com sucesso!";
            } else {
                echo "Erro ao cadastrar administrador: " . $stmt->error;
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO administrador_ong 
                (id_admin_ong, area_atuacao, data_fundacao, endereco_rua, endereco_numero, endereco_complemento, 
                endereco_bairro, endereco_cidade, endereco_estado, endereco_pais, endereco_cep) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("Erro ao preparar a consulta de administrador_ong: " . $conn->error);
            }
            $stmt->bind_param("issssssssss", $id_usuario, $areaActivity, $fundationDate, $road, $num, $complement, 
                                $neighborhood, $city, $state, $country, $CEP);

            if ($stmt->execute()) {
                echo ">> ONG CADASTRADA COM SUCESSO <<";
                $_SESSION['id_admin_ong'] = $conn->insert_id;
            } else {
                echo "Erro ao cadastrar administrador_ong: " . $stmt->error;
                exit;
            }

        } else {
            echo "Erro ao cadastrar usuário" . $stmt->error;
            exit;
        }

            $stmt->close();
    }
    $conn->close();
    header("Location: home-ong.php");
    exit;
?>
