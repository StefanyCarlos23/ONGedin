
<?php
include('connection.php');

$query = "SELECT u.nome AS nome_ong, a.id_admin_ong
          FROM usuario u
          INNER JOIN administrador_ong a ON u.id_usuario = a.id_admin_ong";


$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doações</title>
    <link rel="stylesheet" href="donations.css">
</head>
<body>
<header>
    <div class="nav-container">
        <nav class="nav">
            <a href="home-volunteer.php">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <ul class="ul">
                <ul class="ul-text">
                    <a href="home-volunteer.php">Início</a>
                    <a href="search-volunteer.php">Pesquisar</a>
                    <a href="donations-volunteer.php">Doações</a>
                    <a href="help-volunteer.php">Suporte</a>
                </ul>
                <a href="profile.php">
                    <img src="images/perfil.png" alt="ongedin-logo">
                </a>
            </ul>
        </nav>
    </div>
</header>

    <div class="content">
        <main>
            <h2>Faça Sua Doação</h2>
            <h3>Ajude-nos a continuar nosso trabalho.</h3>
            <p>Com a sua doação, podemos alcançar mais pessoas e fazer a diferença. Qualquer doação é bem-vinda!</p>

            <form id="form" action="donations-process.php" method="POST">
                <div>
                    <label for="nome"><b>Nome: *</b></label>
                    <input type="text" id="name" name="name" class="required" placeholder="Insira seu nome completo" oninput="inputWithoutNumbersValidate()">
                    <span class="span-required">Nome não pode conter números.</span>
                </div>
                    
                <div>
                    <label for="email"><b>Email: *</b></label>
                    <input type="text" name="email" id="email" class="required" placeholder="exemplo@gmail.com" oninput="emailValidate()">
                    <span class="span-required">Insira um e-mail válido!</span>
                </div>

                <div>
                    <label class="label" for="ong"><b>ONG: *</b></label>
                    <select id="select-ong" name="select-ong">
                        <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = (isset($_POST['select-ong']) && $_POST['select-ong'] == $row['id_admin_ong']) ? 'selected' : '';
                                echo "<option value='" . $row['id_admin_ong'] . "' $selected>" . $row['nome_ong'] . "</option>";
                            }
                        ?>
                    </select>


                <div>
                    <label for="tipo-doacao"><b>Tipo de Doação: *</b></label>
                    <select id="tipo-doacao" name="tipo-doacao" onchange="mostrarCamposEspecificos()">
                        <option value="dinheiro">Dinheiro</option>
                        <option value="alimentos">Alimentos</option>
                        <option value="roupas">Roupas</option>
                    </select>
                </div>

                <div id="doacao-dinheiro" class="campo-doacao">
                    <label for="valor"><b>Valor da Doação: *</b></label>
                    <input type="number" id="valor" name="valor" placeholder="R$ 50,00">
                    <label for="metodo"><b>Método de Pagamento: *</b></label>
                    <select id="metodo" name="metodo" required>
                        <option value="cartao">Cartão de Crédito</option>
                        <option value="boleto">Boleto Bancário</option>
                        <option value="pix">PIX</option>
                    </select>
                </div>

                <div id="doacao-alimentos" class="campo-doacao" style="display: none;">
                    <label for="quantidade-alimentos">Quantidade de Alimentos:</label>
                    <input type="number" id="quantidade-alimentos" name="quantidade-alimentos" placeholder="Ex: 10 pacotes de arroz">
                </div>

                <div id="doacao-roupas" class="campo-doacao" style="display: none;">
                    <label for="quantidade-roupas">Quantidade de Roupas:</label>
                    <input type="number" id="quantidade-roupas" name="quantidade-roupas" placeholder="Ex: 5 camisetas, 3 calças">
                </div>

                <div class="btn-donation">
                    <input id='submit' type="submit" value="Doar agora" class="donation-btn" onclick="btnRegisterOnClick(event)">
                </div>
            </form>
        </main>

        <footer>
            <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="donations.js"></script>

</body>
</html>
