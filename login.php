<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<script>let errorMessage = '" . $_SESSION['error_message'] . "';</script>";
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Login</title>
    <link href="login.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home-without-login.php">
                    <img src="images/ongedin-logo.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home-without-login.php">Início</a>
                        <a href="search-without-login.php">Pesquisar</a>
                        <a href="help-without-login.php">Suporte</a>
                    </ul>
                    <a href="profile.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
        <nav class="mobile-nav">
            <a href="home-without-login.php">
                <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
            </a>
            <div class="mobile-menu">
              <div class="line1"></div>
              <div class="line2"></div>
              <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="home-without-login.php">Início</a>
                <li><a href="search-without-login.php">Pesquisar</a></li>
                <li><a href="help-without-login.php">Suporte</a></li>
                <li><a href="profile-without-login.php">Perfil</a></li>
            </ul>
          </nav>
    </header>
    <section class="content">
        <section class="box-content">

                <div class="banner-image">
                    <img src="images/banner.jpg">
                </div>

                <div class="form-container">

                    <section class="btn">
                        <div class="btn-back">
                            <a class="back-btn" href="home-without-login.php">Voltar</a>
                        </div>

                        <div class="btn-register">
                            <h3> Você ainda não tem uma conta?</h3>
                            <a class="register-btn" href="choose-register.php">Cadastre-se</a>
                        </div>
                    </section>
                    
                    <form id="form" name="form" action="login-process.php" method="POST">
                        <div class="inputBox">
                            <h1 class="form-title">Login</h1>
                            <div class="inputBox-1">
                                <input type="text" name="email" id="email" class="inputUser" required>
                                <label for="email" class="labelInput">E-mail</label>
                            </div>
                            <br>
                            <div class="inputBox-1">
                                <input type="password" name="password" id="password" class="inputUser" required>
                                <label for="password" class="labelInput">Senha:</label>
                            </div>
                            <br>
                        </div>
        
                        <div class="btn-login">
                            <input type="submit" value="Login" class="login-btn">
                        </div>
                        
                    </form>
                </div>    
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="login.js"> </script>

    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>