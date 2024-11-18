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
        while ($eventData = $result->fetch_assoc()) {
            $eventDate = $eventData['data_evento'] ?? '';
            if ($eventDate) {
                $eventDateFormatted = date('d/m/Y', strtotime($eventDate));
            } else {
                $eventDateFormatted = '';
            }

            $title = $eventData['titulo'] ?? '';
            $description = $eventData['descricao'] ?? '';
            $eventDate = $eventData['data_evento'] ?? '';
            $road = $eventData['local_rua'] ?? '';
            $num = $eventData['local_numero'] ?? '';
            $neighborhood = $eventData['local_bairro'] ?? '';
            $city = $eventData['local_cidade'] ?? '';
            $state = $eventData['local_estado'] ?? '';
            $country = $eventData['local_pais'] ?? '';
            $complement = $eventData['local_complemento'] ?? '';
        }
    } else {
        echo "Nenhum evento encontrado com esse título.";
    }
} else {
    echo "Título não especificado.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Cadastro de Eventos</title>
    <link href="event-register.css" rel="stylesheet">
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
                        <a href="event-management.php">Gerenciamento de Eventos</a>
                        <a href="help-ong.php">Suporte</a>
                    </ul>
                    <a href="profile-ong.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
        <nav class="mobile-nav">
            <a href="home.php">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="home-without-login.php">Início</a></li>
                <li><a href="search-without-login.php">Pesquisar</a></li>
                <li><a href="help-without-login.php">Suporte</a></li>
            </ul>
            </nav>
    </header>
    <section class="content">
        <section class="box-content">
        
                <section class="btn">
                    <div class="btn-back">
                        <a class="back-btn" href="event-management.php">Voltar</a>
                    </div>
                </section>

            <h1>Dados cadastrais do Evento </h1>
    
            <form id="form" name="form" method="POST" action="event-edit-process.php">

                <div class="full-inputBox">
                    <label for="title"><b>Título do Evento: *  (O título do evento não pode ser alterado)</b></label>
                    <input type="text" id="title" name="title" class="full-inputUser required" placeholder="Insira o nome do evento" oninput="inputWithoutNumbersValidate(0)" value="<?php echo $title?> "readonly>
                    <span class="span-required">Nome não pode conter números e caracteres especiais.</span>
                </div>

                <div class="full-inputBox">
                    <label for="description"><b>Descrição do Evento *</b></label>
                    <textarea id="description" name="description" class="required" placeholder="Insira a descrição do evento" oninput="maxLengthValidate()" ><?php echo $description ?></textarea>

                    <span class="span-required">A descrição não pode conter mais de 1000 caracteres</span>
                </div>

                <div class="full-inputBox">
                        <label for="text"><b>Data do Evento: *</b></label>
                        <input type="text" id="event-date" name="event-date" class="full-inputUser required" placeholder="dd/mm/aaaa" oninput="dateValidate()" value="<?php echo $eventDateFormatted ?>">

                        <span class="span-required">Insira uma data válida!</span>
                </div>

                <h1>Endereço do Evento</h1>

                <div class="container-row" >
                    <div class="mid-inputBox">
                        <label for="road"><b>Rua: *</b></label>
                        <input type="text" name="road" id="road" class="mid-inputUser required" placeholder="Insira o nome da rua" oninput="roadValidate()" value="<?php echo $road?>">
                        <span class="span-required"> Rua não pode conter caracteres especias.</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="num"><b>Número: *</b></label>
                        <input type="text" name="num" id="num" class="mid-inputUser required" placeholder="Insira o número" oninput="numValidate()" value="<?php echo $num?>">
                        <span class="span-required">Número não pode conter letras ou caracteres especiais.</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="neighborhood"><b>Bairro: *</b></label>
                        <input type="text" name="neighborhood" id="neighborhood" class="mid-inputUser required" placeholder="Insira o bairro" oninput="inputWithoutNumbersValidate(5)" value="<?php echo $neighborhood?>">
                        <span class="span-required">Bairro não pode conter números ou caracteres especiais.</span>
                    </div>
                    <diV class="mid-inputBox">
                        <label for="city"><b>Cidade: *</b></label>
                        <input type="text" name="city" id="city" class="mid-inputUser required" placeholder="Insira a cidade" oninput="inputWithoutNumbersValidate(6)" value="<?php echo $city?>">
                        <span class="span-required">Cidade não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>
    
                <div class="container-row" >
                    <div class="mid-inputBox">
                        <label for="state"><b>Estado: *</b></label>
                        <input type="text" name="state" id="state" class="mid-inputUser required" placeholder="Insira o estado" oninput="inputWithoutNumbersValidate(7)" value="<?php echo $state?>">
                        <span class="span-required">Estado não pode conter números ou caracteres especiais.</span>  
                    </div>
                    <div class="mid-inputBox">
                        <label for="country"><b>País: *</b></b></label>
                        <input type="text" name="country" id="country" class="mid-inputUser required" placeholder="Insira o país" oninput="inputWithoutNumbersValidate(8)" value="<?php echo $country?>">
                        <span class="span-required">País não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="complement"><b>Complemento: (opcional)</b></label>
                        <input type="text" name="complement" id="complement" class="mid-inputUser" placeholder="Insira o complemento" value="<?php echo $complement?>">
                    </div>
                </div>


                <div class="btn-register">
                    <input id='update' name="update" type="submit" value="Cadastrar evento" class="register-btn" onclick="btnRegisterOnClick(event)">
                </div>

            </form>
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="event-register.js"></script>

    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>