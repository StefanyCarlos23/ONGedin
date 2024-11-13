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
                <li><a href="home.php">Início</a></li>
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

            <h1>Dados cadastrais - ONG</h1>
    
            <form id="form" name="form" method="POST" action="ong-register-process.php">
                <div class="full-inputBox">
                    <label for="name"><b>Nome: *</b></label>
                    <input type="text" id="name" name="name" class="full-inputUser required" placeholder="Insira o nome da ONG" oninput="inputWithoutNumbersValidate(0)">
                    <span class="span-required">Nome não pode conter números e caracteres especiais.</span>
                </div>

                <div class="full-inputBox">
                    <label for="text"><b>E-mail: *</b></label>
                    <input type="text" id="email" name="email"class="full-inputUser required" placeholder="exemplo@gmail.com" oninput="emailValidate()">
                    <span class="span-required">Insira um e-mail válido!</span>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="text" class="required" ><b>Área de Atuação: *</b></label>
                        <select class="mid-inputUser" name="area-activity" >
                            <option value="">Selecione uma área de atuação</option>
                            <option value="meio ambiente" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'meio ambiente') ? 'selected' : ''; ?>>Meio Ambiente</option>
                            <option value="desenvolvimento economico" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'desenvolvimento economico') ? 'selected' : ''; ?>>Desenvolvimento Econômico</option>
                            <option value="direitos humanos" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'direitos humanos') ? 'selected' : ''; ?>>Direitos Humanos</option>
                            <option value="direitos das criancas" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'direitos das criancas') ? 'selected' : ''; ?>>Direitos das Crianças</option>
                            <option value="educacao" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'educacao') ? 'selected' : ''; ?>>Educação</option>
                            <option value="defesa dos animais" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'defesa dos animais') ? 'selected' : ''; ?>>Defesa dos Animais</option>
                            <option value="saude" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'saude') ? 'selected' : ''; ?>>Saúde</option>
                            <option value="direitos das mulheres" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'direitos das mulheres') ? 'selected' : ''; ?>>Direitos das Mulheres</option>
                            <option value="cultura" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'cultura') ? 'selected' : ''; ?>>Cultura e Arte</option>
                            <option value="ajuda humanitaria" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'ajuda humanitaria') ? 'selected' : ''; ?>>Ajuda Humanitaria</option>
                            <option value="direitos das minorias" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'direitos das minorias') ? 'selected' : ''; ?>>Direitos das Minorias</option>
                            <option value="assistencia social" <?php echo (isset($_POST['area-activity']) && $_POST['area-activity'] === 'assistencia social') ? 'selected' : ''; ?>>Assistência Social</option>
                        </select>
                    </div>

                    <div class="mid-inputBox">
                        <label for="text"><b>Data de fundação: *</b></label>
                        <input type="text" id="fundation-date" name="fundation-date" class="mid-inputUser required" placeholder="dd/mm/aaaa" oninput="dateValidate()">
                        <span class="span-required">Insira uma data válida!</span>
                    </div>
                </div>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="telephone"><b>Telefone: *</b></label>
                        <input type="text" name="telephone" id="telephone" class="mid-inputUser required" placeholder="(XX) XXXXX-XXXX" oninput="telephoneValidate()">
                        <span class="span-required">Insira um telefone válido</span>
                    </div>

                    <div class="mid-inputBox">
                        <label for="social-media"><b>Rede Social: *</b></label>
                        <input type="text" name="social-media" id="social-media" class="mid-inputUser required" placeholder="Insira a rede social da ONG">
                        <span class="span-required"></span>
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
    
                <h1>Endereço</h1>

                <div class="container-row">
                    <div class="mid-inputBox">
                        <label for="CEP"><b>CEP: *</b></label>
                        <input type="text" name="CEP" id="CEP" class="mid-inputUser required" placeholder="XXX.XXX.XXX-XX" oninput="cepValidate()">
                        <span class="span-required">CEP inválido!</span>
                    </div>
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
                        <input type="text" name="neighborhood" id="neighborhood" class="mid-inputUser required" placeholder="Insira o bairro" oninput="inputWithoutNumbersValidate(11)">
                        <span class="span-required">Bairro não pode conter números ou caracteres especiais.</span>
                    </div>
                    <diV class="mid-inputBox">
                        <label for="city"><b>Cidade: *</b></label>
                        <input type="text" name="city" id="city" class="mid-inputUser required" placeholder="Insira a cidade" oninput="inputWithoutNumbersValidate(12)">
                        <span class="span-required">Cidade não pode conter números ou caracteres especiais.</span>
                    </div>
                </div>
    
                <div class="container-row" >
                    <div class="mid-inputBox">
                        <label for="state"><b>Estado: *</b></label>
                        <input type="text" name="state" id="state" class="mid-inputUser required" placeholder="Insira o estado" oninput="inputWithoutNumbersValidate(13)">
                        <span class="span-required">Estado não pode conter números ou caracteres especiais.</span>  
                    </div>
                    <div class="mid-inputBox">
                        <label for="country"><b>País: *</b></b></label>
                        <input type="text" name="country" id="country" class="mid-inputUser required" placeholder="Insira o país" oninput="inputWithoutNumbersValidate(14)">
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