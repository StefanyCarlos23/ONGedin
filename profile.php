<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | ONGedin</title>
    <link href="profile.css" rel="stylesheet">
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
                        <a href="help.html">Suporte</a>
                    </ul>
                    <a href="profile.html">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <main class="profile-container">
        <section class="profile-header">
            <div class="profile-picture">
                <label for="upload-picture">
                    <img src="images/perfil.png" alt="Foto de perfil" id="profile-img">
                </label>
                <input type="file" id="upload-picture" accept="image/*" style="display: none;" onchange="previewImage(event)">
            </div>
            <div class="profile-info">
                <h1 id="user-name">Seu Nome</h1>
                <button onclick="editName()">Editar Nome</button>
            </div>
        </section>

        <section class="event-history">
            <h2>Histórico de Eventos</h2>
            <ul class="event-list">
                <li>
                    <strong>Feira de Adoção de Animais</strong>
                    <p>Data: 12/10/2024</p>
                </li>
                <li>
                    <strong>Campanha de Reflorestamento</strong>
                    <p>Data: 05/09/2024</p>
                </li>
                <li>
                    <strong>Doação de Alimentos</strong>
                    <p>Data: 15/08/2024</p>
                </li>
            </ul>
        </section>

        <section class="logout-section">
            <button onclick="logout()">Sair</button>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const profileImg = document.getElementById('profile-img');
                profileImg.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function editName() {
            const currentName = document.getElementById('user-name').textContent;
            const newName = prompt("Digite seu novo nome:", currentName);
            if (newName) {
                document.getElementById('user-name').textContent = newName;
            }
        }

        function logout() {
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>