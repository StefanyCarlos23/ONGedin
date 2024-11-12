<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Cadastre-se</title>
    <link href="volunteer-register.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home.php">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home.php">Início</a>
                        <a href="search.php">Pesquisar</a>
                        <a href="help.php">Suporte</a>
                    </ul>
                    <a href="profile.php">
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
                <li><a href="home.php">Início</a>
                <li><a href="search.php">Pesquisar</a></li>
                <li><a href="help.php">Suporte</a></li>
                <li><a href="profile.php">Perfil</a></li>
            </ul>
          </nav>
    </header>
    <section class="content">
        <section class="box-content">
        
                <section class="btn">
                    <div class="btn-back">
                        <a class="back-btn" href="choose-register.php">Voltar</a>
                    </div>
                    <div class="btn-login">
                        <h3> Você já tem uma conta?</h3>
                        <a class="login-btn" href="login.php">Login</a>
                    </div>
                </section>

            <h1>Dados cadastrais - Contribuinte</h1>
    
            <form id="form" name="form" method="POST" action="volunteer-register-process.php" onsubmit="buttonDisable()">
                <div class="full-inputBox">
                    <label for="text"><b>Nome: *</b></label>
                    <input type="text" id="name" name="name"class="full-inputUser required" placeholder="Insira seu nome completo" oninput="inputWithoutNumbersValidate()">
                    <span class="span-required">Nome não pode conter números.</span>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="text"><b>CPF: *</b></label>
                        <input type="text" id="cpf" name="cpf" class="mid-inputUser required" placeholder="XXX.XXX.XXX-XX" oninput="cpfValidate()">
                        <span class="span-required">Insira um CPF válido!</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="text"><b>Data de nascimento: *</b></label>
                        <input type=text id="date-birth" name="date-birth" class="mid-inputUser required" placeholder="dd/mm/aaaa" oninput="dateValidate()">
                        <span class="span-required">Insira uma data válida!</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="telephone"><b>Telefone: *</b></label>
                        <input type="text" name="telephone" id="telephone" class="mid-inputUser required" placeholder="(XX) XXXXX-XXXX" oninput="telephoneValidate()">
                        <span class="span-required">Insira um telefone válido!</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="email"><b>E-mail: *</b></label>
                        <input type="text" name="email" id="email" class="mid-inputUser required" placeholder="exemplo@gmail.com" oninput="emailValidate()">
                        <span class="span-required">Insira um e-mail válido!</span>
                    </div>
                </div>

                <div class="full-inputBox">
                    <label for="password"><b>Senha: *</b></label>
                    <input type="password" name="password" id="password" class="full-inputUser required" placeholder="Crie uma senha" oninput="passwordValidate()">
                    <span class="span-required">Sua senha deve conter no mínimo 8 caracteres, combinando letras maiúsculas, minúsculas, números e símbolos especiais.</span>
                </div>

                <div class="full-inputBox">
                    <label for="confirm-pass"><b>Confirme sua senha: *</b></label>
                    <input type="password" name="confirm-pass" id="confirm-pass" class="full-inputUser required" placeholder="Repita a senha" oninput="confirmPasswordValidate()">
                    <span class="span-required">As senhas não coincidem.</span>
                </div>

                <div class="btn-register">
                    <input id='submit' type="submit" value="Cadastrar-se" class="register-btn" onclick="btnRegisterOnClick(event)">
                </div>
            </form>
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="volunteer-register.js"> </script>

    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>