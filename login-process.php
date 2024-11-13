<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuario u INNER JOIN contato c ON u.id_usuario = c.id_usuario WHERE c.email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            if ($user['funcao'] == 'A') {
                header("Location: home-adm.php");
            } elseif ($user['funcao'] == 'O') {
                header("Location: home-ong.php");
            } elseif ($user['funcao'] == 'C') {
                header("Location: home-volunteer.php");
            } else{
                exit(); 
            }
            
        } else {
            header("Location: login.php");
            $_SESSION['error_message'] = "Senha incorreta.";
            exit();
        }
    } else {
        header("Location: login.php");
        $_SESSION['error_message'] = "Usuário não encontrado.";
        exit();
    }

} else {
    echo "O método de envio não é POST. Dados não recebidos.";
}

?>