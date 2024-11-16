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
        <nav class="mobile-nav">
            <a href="home-volunteer.php">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="home-volunteer.php">Início</a>
                <li><a href="search-volunteer.php">Pesquisar</a></li>
                <li><a href="donations-volunteer.php">Doações</a></li>
                <li><a href="help-volunteer.php">Suporte</a></li>
                <li><a href="profile-volunteer.php">Perfil</a></li>
            </ul>
            </nav>
    </header>

    <div class="content">
        <main>
            <h2>Faça Sua Doação</h2>
            <h3>Ajude-nos a continuar nosso trabalho.</h3>
            <p>Com a sua doação, podemos alcançar mais pessoas e fazer a diferença. Qualquer doação é bem-vinda!</p>

            <form id="form" action="donation-process.php" method="POST">


            <div>
                <label for="nome"><b>Nome: *</b></label>
                <input type="text" id="name" name="name"class="required" placeholder="Insira seu nome completo" oninput="inputWithoutNumbersValidate()">
                <span class="span-required">Nome não pode conter números.</span>
            </div>
                
            <div>
                <label for="email"><b>Email: *</b></label>
                <input type="text" name="email" id="email" class="required" placeholder="exemplo@gmail.com" oninput="emailValidate()">
                <span class="span-required">Insira um e-mail válido!</span>
            </div>

            <div>
                <label class="label" for="ong"><b>ONG: *</b></label>
                <select id="ong" name="ong" required>
                    <option value="ong1">ONG 1</option>
                    <option value="ong2">ONG 2</option>
                    <option value="ong3">ONG 3</option> 
                   
                </select>
            </div>

            <div>
                <label for="tipo-doacao"><b>Tipo de Doação: *</b></label>
                <select id="tipo-doacao" name="tipo-doacao" onchange="mostrarCamposEspecificos()" required>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="alimentos">Alimentos</option>
                    <option value="roupas">Roupas</option>
                </select>

            </div>

                <div id="doacao-dinheiro" class="campo-doacao">
                    <label for="valor"><b>Valor da Doação: *</b></label>
                    <input type="number" id="valor" name="valor" placeholder="R$ 50,00" min="1" class="required">
                    <label for="metodo"><b>Método de Pagamento: *</b></label>
                    <select id="metodo" name="metodo" required>
                        <option value="cartao">Cartão de Crédito</option>
                        <option value="boleto">Boleto Bancário</option>
                        <option value="pix">PIX</option>
                    </select>
                </div>

                <div id="doacao-alimentos" class="campo-doacao" style="display: none;">
                    <label for="quantidade-alimentos">Quantidade de Alimentos:</label>
                    <input type="number" id="quantidade-alimentos" name="quantidade-alimentos" placeholder="Ex: 10 pacotes de arroz" required min="1">
                </div>

                <div id="doacao-roupas" class="campo-doacao" style="display: none;">
                    <label for="quantidade-roupas">Quantidade de Roupas:</label>
                    <input type="number" id="quantidade-roupas" name="quantidade-roupas" placeholder="Ex: 5 camisetas, 3 calças" required min="1">
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

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="donations.js"> </script>