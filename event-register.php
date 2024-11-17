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
                <a href="home-without-login.php">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home-without-login.php">Início</a>
                        <a href="search-without-login.php">Pesquisar</a>
                        <a href="help-without-login.php">Suporte</a>
                    </ul>
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
    
            <form id="form" name="form" method="POST" action="event-register-process.php">

                <div class="full-inputBox">
                    <label for="title"><b>Título do Evento: *</b></label>
                    <input type="text" id="title" name="title" class="full-inputUser required" placeholder="Insira o nome do evento" oninput="inputWithoutNumbersValidate(0)">
                    <h3 id="obs" > OBS: O título de evento não poderá ser alterado após cadastrar o evento</h3>
                <style>
                    #obs {
                        color: #777575;
                        font-size: 12px;
                    }
                </style>
                    <span class="span-required">Nome não pode conter números e caracteres especiais.</span>
                </div>

                <div class="full-inputBox">
                    <label for="description"><b>Descrição do Evento *</b></label>
                    <textarea id="description" name="description" class="required" placeholder="Insira a descrição do evento" oninput="maxLengthValidate()"></textarea>
                    <span class="span-required">A descrição não pode conter mais de 1000 caracteres</span>
                </div>

                <div class="full-inputBox">
                        <label for="text"><b>Data do Evento: *</b></label>
                        <input type="text" id="event-date" name="event-date" class="full-inputUser required" placeholder="dd/mm/aaaa" oninput="dateValidate()">

                        <span class="span-required">Insira uma data válida!</span>
                </div>

                <h1>Endereço do Evento</h1>

                <div class="container-row" >
                    <div class="mid-inputBox">
                        <label for="road"><b>Rua: *</b></label>
                        <input type="text" name="road" id="road" class="mid-inputUser required" placeholder="Insira o nome da rua" oninput="roadValidate()">
                        <span class="span-required"> Rua não pode conter caracteres especias.</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="num"><b>Número: *</b></label>
                        <input type="text" name="num" id="num" class="mid-inputUser required" placeholder="Insira o número" oninput="numValidate()">
                        <span class="span-required">Número não pode conter letras ou caracteres especiais.</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="neighborhood"><b>Bairro: *</b></label>
                        <input type="text" name="neighborhood" id="neighborhood" class="mid-inputUser required" placeholder="Insira o bairro" oninput="inputWithoutNumbersValidate(5)">
                        <span class="span-required">Bairro não pode conter números ou caracteres especiais.</span>
                    </div>
                    <diV class="mid-inputBox">
                        <label for="city"><b>Cidade: *</b></label>
                        <input type="text" name="city" id="city" class="mid-inputUser required" placeholder="Insira a cidade" oninput="inputWithoutNumbersValidate(6)">
                        <span class="span-required">Cidade não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>
    
                <div class="container-row" >
                    <div class="mid-inputBox">
                        <label for="state"><b>Estado: *</b></label>
                        <input type="text" name="state" id="state" class="mid-inputUser required" placeholder="Insira o estado" oninput="inputWithoutNumbersValidate(7)">
                        <span class="span-required">Estado não pode conter números ou caracteres especiais.</span>  
                    </div>
                    <div class="mid-inputBox">
                        <label for="country"><b>País: *</b></b></label>
                        <input type="text" name="country" id="country" class="mid-inputUser required" placeholder="Insira o país" oninput="inputWithoutNumbersValidate(8)">
                        <span class="span-required">País não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="complement"><b>Complemento: (opcional)</b></label>
                        <input type="text" name="complement" id="complement" class="mid-inputUser" placeholder="Insira o complemento">
                    </div>
                </div>


                <div class="btn-register">
                    <input id='submit' type="submit" value="Cadastrar evento" class="register-btn" onclick="btnRegisterOnClick(event)">
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