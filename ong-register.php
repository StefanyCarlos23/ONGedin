<?php
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

    $stmt = $conn->prepare("INSERT INTO usuario (nome, funcao, senha, data_cadastro) VALUES (?, ?, ?, NOW())");
    
    if (!$stmt) {
        die("Erro ao preparar a consulta de usuário: " . $conn->error);
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Criptografar a senha
    $funcao = 'O';
    $stmt->bind_param("sss", $name, $funcao, $hashedPassword);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
        $id_usuario = $conn->insert_id;

        // Inserir contato na tabela `contato`
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Cadastro ONG</title>
    <link href="ong-register.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home.html">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home.html">Início</a>
                        <a href="search.html">Pesquisar</a>
                        <a href="donations.html">Doações</a>
                        <a href="events.html">Eventos</a>
                        <a href="help.html">Suporte</a>
                    </ul>
                    <a href="notification.html">
                        <img src="images/notificação.png" alt="ongedin-logo">
                    </a>
                    <a href="profile.html">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
        <nav class="mobile-nav">
            <a href="home.html">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="home.html">Início</a>
                <li><a href="search.html">Pesquisar</a></li>
                <li><a href="donations.html">Doações</a></li>
                <li><a href="events.html">Eventos</a></li>
                <li><a href="help.html">Suporte</a></li>
                <li><a href="notification.html">Notificações</a></li>
                <li><a href="profile.html">Perfil</a></li>
            </ul>
            </nav>
    </header>
    <section class="content">
        <section class="box-content">
        
                <section class="btn">
                    <div class="btn-back">
                        <a class="back-btn" href="choose-register.html">Voltar</a>
                    </div>
                    <div class="btn-login">
                        <h3> Você já tem uma conta?</h3>
                        <a class="login-btn" href="login.html">Login</a>
                    </div>
                </section>

            <h1>Dados cadastrais</h1>
    
            <form id="form" name="form" method="POST" action="">
                <div class="full-inputBox">
                    <label for="name"><b>Nome:</b></label>
                    <input type="text" id="name" name="name" class="full-inputUser required" placeholder="Insira o nome da ONG" oninput="inputWithoutNumbersValidate(0)">
                    <span class="span-required">Nome não pode conter números e caracteres especiais.</span>
                </div>

                <div class="full-inputBox">
                    <label for="text"><b>E-mail:</b></label>
                    <input type="text" id="email" name="email"class="full-inputUser required" placeholder="exemplo@gmail.com" oninput="emailValidate()">
                    <span class="span-required">Insira um e-mail válido!</span>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="text"><b>Área de Atuação:</b></label>
                        <input type="text" id="area-activity" name="area-activity" class="mid-inputUser required" placeholder="Insira a área de atuação da ONG" oninput="inputWithoutNumbersValidate(2)">
                        <span class="span-required">Área de atuação não pode conter números ou caracteres especiais.</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="text"><b>Data de fundação:</b></label>
                        <input type="text" id="fundation-date" name="fundation-date" class="mid-inputUser required" placeholder="dd/mm/aaaa" oninput="dateValidate()">
                        <span class="span-required">Insira uma data válida!</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="telephone"><b>Telefone:</b></label>
                        <input type="text" name="telephone" id="telephone" class="mid-inputUser required" placeholder="(XX) XXXXX-XXXX" oninput="telephoneValidate()">
                        <span class="span-required">Insira um telefone válido</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="social-media"><b>Rede Social:</b></label>
                        <input type="text" name="social-media" id="social-media" class="mid-inputUser required" placeholder="Insira a rede social da ONG">
                        <span class="span-required"></span>
                    </div>
                </div>

                <div class="full-inputBox">
                    <label for="password"><b>Senha:</b></label>
                    <input type="password" name="password" id="password" class="full-inputUser required" placeholder="Crie uma senha" oninput="passwordValidate()">
                    <span class="span-required">Sua senha deve conter no mínimo 8 caracteres, combinando letras maiúsculas, minúsculas, números e símbolos especiais.</span>
                </div>

                <div class="full-inputBox">
                    <label for="confirm-pass"><b>Confirme sua senha:</b></label>
                    <input type="password" name="confirm-pass" id="confirm-pass" class="full-inputUser required" placeholder="Repita a senha" oninput="confirmPasswordValidate()">
                    <span class="span-required">As senhas não coincidem.</span>
                </div>
    
                <h1>Endereço</h1>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="CEP"><b>CEP:</b></label>
                        <input type="text" name="CEP" id="CEP" class="mid-inputUser required" placeholder="XXX.XXX.XXX-XX" oninput="cepValidate()">
                        <span class="span-required">CEP inválido!</span>
                    </div>
                    <div class="mid-inputBox">
                        <label for="road"><b>Rua:</b></label>
                        <input type="text" name="road" id="road" class="mid-inputUser required" placeholder="Insira o nome da rua" oninput="roadValidate()">
                        <span class="span-required"> Rua não pode conter caracteres especias.</span>
                    </div>
                    <div class="mid-inputBox">
                        <label for="num"><b>Número:</b></label>
                        <input type="text" name="num" id="num" class="mid-inputUser required" placeholder="Insira o número" oninput="numValidate()">
                        <span class="span-required">Número não pode conter letras ou caracteres especiais.</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="neighborhood"><b>Bairro:</b></label>
                        <input type="text" name="neighborhood" id="neighborhood" class="mid-inputUser required" placeholder="Insira o bairro" oninput="inputWithoutNumbersValidate(11)">
                        <span class="span-required">Bairro não pode conter números ou caracteres especiais.</span>
                    </div>
                    <diV class="mid-inputBox">
                        <label for="city"><b>Cidade:</b></label>
                        <input type="text" name="city" id="city" class="mid-inputUser required" placeholder="Insira a cidade" oninput="inputWithoutNumbersValidate(12)">
                        <span class="span-required">Cidade não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>
    
                <div class="container-row" >
                    <div class="mid-inputBox">
                        <label for="state"><b>Estado:</b></label>
                        <input type="text" name="state" id="state" class="mid-inputUser required" placeholder="Insira o estado" oninput="inputWithoutNumbersValidate(13)">
                        <span class="span-required">Estado não pode conter números ou caracteres especiais.</span>  
                    </div>
                    <div class="mid-inputBox">
                        <label for="country"><b>País:</b></b></label>
                        <input type="text" name="country" id="country" class="mid-inputUser required" placeholder="Insira o país" oninput="inputWithoutNumbersValidate(14)">
                        <span class="span-required">País não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="complement"><b>Complemento:</b></label>
                        <input type="text" name="complement" id="complement" class="mid-inputUser" placeholder="Insira o complemento">
                    </div>
                </div>


                <div class="btn-register">
                    <input id='submit' type="submit" value="Cadastrar-se" class="register-btn" onclick="btnRegisterOnClick(event)">
                </div>

            </form>
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="ong-register.js"></script>

    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>