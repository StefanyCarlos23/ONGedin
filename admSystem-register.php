<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Cadastre-se</title>
    <link href="admSystem-register.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home.php">
                    <img src="images/ongedin-logo.png" alt="ongedin-logo">
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
    
    
            <h1> Dados cadastrais - Administrador do Sistema</h1>
    
            <form id="form" name="form" method="POST" action="admSystem-register-process.php">
                <div class="inputBox">
                    <label for="text"><b>Nome: *</b></label>
                    <input type="text" id="name" name="name"class="inputUser required" placeholder="Insira seu nome completo" oninput="inputWithoutNumbersValidate(0)">
                    <span class="span-required">Nome não pode conter números ou caracteres especiais.</span>
                </div>

                <div class="inputBox">
                    <label for="text"><b>E-mail: *</b></label>
                    <input type="text" id="email" name="email" class="inputUser required" placeholder="Insira seu e-mail" oninput="emailValidate()">

                    <span class="span-required">Insira um e-mail válido!</span>
                </div>

                <div class="inputBox">
                    <label for="telephone"><b>Telefone: *</b></label>
                    <input type="text" name="telephone" id="telephone" class="inputUser required"placeholder="Insira seu número de telefone" oninput="telephoneValidate()">
                    <span class="span-required">Telefone inválido!</span>
                </div>

                <div class="inputBox">
                    <label for="password"><b>Senha: *</b></label>
                    <input type="password" name="password" id="password" class="inputUser required" placeholder="Crie uma senha" oninput="passwordValidate()">
                    <span class="span-required">Sua senha deve conter no mínimo 8 caracteres, combinando letras maiúsculas, minúsculas, números e símbolos especiais.</span>
                </div>

                <div class="inputBox">
                    <label for="confirm-pass"><b>Confirme sua senha: *</b></label>
                    <input type="password" name="confirm-pass" id="confirm-pass" class="inputUser required" placeholder="Repita a senha" oninput="confirmPasswordValidate()">
                    <span class="span-required">As senhas não coincidem.</span>
                </div>

                <div class="btn-register">
                    <input id='submit' type="submit" value="Cadastrar-se" class="register-btn" onclick="btnRegisterOnClick(event)">
                </div>
            </form>
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="admSystem-register.js"></script>
    
    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>