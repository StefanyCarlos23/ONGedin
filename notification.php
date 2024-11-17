<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Notificações</title>
    <link href="notification.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <nav class="nav">
                <a href="home.php">
                    <img src="images/ongedin-logo.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home.php">Início</a>
                        <a href="search.php">Pesquisar</a>
                        <a href="donations.php">Doações</a>
                        <a href="help.php">Suporte</a>
                    </ul>
                    <a href="notification.php">
                        <img src="images/notificação.png" alt="notificações">
                    </a>
                    <a href="profile.php">
                        <img src="images/perfil.png" alt="perfil">
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <section class="notifications">
        <div class="container">
            <h2>Minhas Notificações</h2>
            <div class="notification-list">
                <div class="notification-item">
                    <img src="images/notificação.png" alt="Ícone de Notificação" class="notification-icon">
                    <p>Você se inscreveu com sucesso no evento "Feira de Adoção de Animais".</p>
                    <span class="notification-time">há 2 horas</span>
                </div>
                <div class="notification-item">
                    <img src="images/notificação.png" alt="Ícone de Notificação" class="notification-icon">
                    <p>Nova campanha de doação aberta: "Campanha de Reflorestamento".</p>
                    <span class="notification-time">há 1 dia</span>
                </div>
                <div class="notification-item">
                    <img src="images/notificação.png" alt="Ícone de Notificação" class="notification-icon">
                    <p>Seu perfil foi atualizado com sucesso.</p>
                    <span class="notification-time">há 3 dias</span>
                </div>
            </div>
            <button class="btn clear-notifications">Limpar Notificações</button>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>

    <script>
        document.querySelector('.clear-notifications').addEventListener('click', function() {
            const notificationList = document.querySelector('.notification-list');
            notificationList.innerHTML = '<p>Não há notificações.</p>';
        });
    </script>
</body>
</html>