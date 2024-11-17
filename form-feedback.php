<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Início</title>
    <link href="form-feedback.css" rel="stylesheet">
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
                    <a href="profile-volunteer.php">
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
                <li><a href="help-volunteer.php">Suporte</a></li>
                <li><a href="donations-volunteer.php">Doações</a></li>
                <li><a href="profile-volunteer.php">Perfil</a></li>
            </ul>
            </nav>
    </header>


    <section class="content">
        <section class="box-content">
        
                <section class="btn">
                    <div class="btn-back">
                        <a class="back-btn" href="home-volunteer.php">Voltar</a>
                    </div>
                </section>

            <h1>Feedback do Evento</h1>
    
            <form id="form" name="form" method="POST" action="form-feedback-process.php">

                <div class="full-inputBox">
                <label for="rating"><b>Avaliação do Evento: *</b></label>
                <div id="rating" class="rating-options">
                    <label>
                        <input type="radio" name="rating" value="1" required>
                        1 - Péssimo
                    </label><br>
                    <label>
                        <input type="radio" name="rating" value="2">
                        2 - Ruim
                    </label><br>
                    <label>
                        <input type="radio" name="rating" value="3">
                        3 - Regular
                    </label><br>
                    <label>
                        <input type="radio" name="rating" value="4">
                        4 - Bom
                    </label><br>
                    <label>
                        <input type="radio" name="rating" value="5">
                        5 - Excelente
                    </label>
                </div>
                <span class="span-required">Selecione uma avaliação para o evento.</span>
            </div>

                <div class="full-inputBox">
                    <label for="description"><b>    Deixe um comentário sobre o Evento: (opcional)</b></label>
                    <textarea id="description" name="description" class="required" placeholder="Insira a descrição do evento" oninput="maxLengthValidate()"></textarea>
                    <span class="span-required">A descrição não pode conter mais de 1000 caracteres</span>
                </div>


                <div class="btn-feedback">
                    <input id='submit' type="submit" value="Enviar Feedback" class="feedback-btn" onclick="btnRegisterOnClick(event)">
                </div>

            </form>
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="form-feedback.js"></script>

    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>