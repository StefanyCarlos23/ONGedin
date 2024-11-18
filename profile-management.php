<?php
// Simula a busca de dados dos usuários
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["search"])) {
        $search = $_GET["search"];
        // Aqui, você realizaria a consulta no banco de dados
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Perfis</title>
    <link rel="stylesheet" href="profile-management.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
        outline: none;
    }

    body{
        background-color: #F6F6F6;
        font-family: "Inter", sans-serif;
    }

    nav{
        background-color: #F6F6F6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 35px;
        padding-left: 20px;
        border-bottom: 3px solid #87BFC7;
    }

    nav ul{
        display: flex;
        align-items: center;
    }

    nav ul .ul-text a{
        color: rgb(0, 0, 0);
        margin: 0 20px;
        font-size: 18px;
        display: block;
    }

    nav ul .ul-text a:not(.btn)::after{
        content: "";
        background-color: rgb(0, 0, 0);
        height: 2px;
        width: 0;
        display: block;
        margin: 0 auto;
        transition: 0.3s;
    }

    nav ul .ul-text a:hover::after{
        width: 100%;
    }

    nav a img{
        width: 90px;
        height: 90px;
    }

    nav ul a img{
        width: 40px;
        height: 40px;
        margin: 0 15px;
    }
    /* Título principal */

    h1 {
        color: #444;
        font-size: 2rem;
        text-align: center;
        margin-top: 30px;
        margin-bottom: 70px;
    }

    /* Dashboard */
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    /* Cartões */
    .card {
        background-color: #ffffff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        border: 1px solid #ddd;
    }

    .card h3 {
        color: #444;
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .card p {
        color: #666;
        font-size: 1rem;
    }

    .card .progress-bar {
        margin-top: 1rem;
    }

    .card .progress-bar {
        background-color: #e0e0e0;
        border-radius: 10px;
        height: 12px;
        overflow: hidden;
    }

    .card .progress {
        background-color: #7cbdb7;
        height: 100%;
        border-radius: 10px;
    }

    /* Cartão especial (bordas personalizadas) */
    .card.blue {
        border: 2px solid #007bff;
    }

    .card.green {
        border: 2px solid #28a745;
    }

    .card.gray {
        border: 2px solid #6c757d;
    }

    /* Barras de progresso */
    .progress-bar {
        background-color: #e0e0e0;
        border-radius: 10px;
        height: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress {
        background-color: #7cbdb7;
        height: 100%;
        border-radius: 10px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .nav-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .ul {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .search-section {
            flex-direction: column;
            gap: 1rem;
        }
    }

    footer{
        border-top: 3px solid #87BFC7;
        background-color: #F6F6F6;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 90px;
        text-align: center;
        margin-top: 50px;
    }
</style>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav">
                <a href="home-adm.php">
                    <img src="images/ongedin-logo-2.png" alt="ongedin-logo">
                </a>
                <ul class="ul">
                    <ul class="ul-text">
                        <a href="home-adm.php">Início</a>
                        <a href="search-adm.php">Pesquisar</a>
                        <a href="profile-management.php">Gerenciamento de Perfis</a>
                        <a href="help-adm.php">Suporte</a>
                    </ul>
                    <a href="profile-adm.php">
                        <img src="images/perfil.png" alt="ongedin-logo">
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h1>Gerenciamento de Perfis</h1>

        <section class="dashboard">
            <div class="card validation">
                <h3>Validação de comprovantes da semana</h3>
                <p>Comprovantes validados: <strong>24</strong></p>
                <p>Comprovantes a validar: <strong>16</strong></p>
                <div class="progress-bar">
                    <div class="progress" style="width: 60%;"></div>
                </div>
                <p>Total de comprovantes validados no mês: <strong>58</strong></p>
            </div>

            <div class="card distribution">
                <h3>Distribuição dos contribuintes</h3>
                <canvas id="contribuinteChart"></canvas>
            </div>

            <div class="card stats">
                <h3>ONGs cadastradas no último mês</h3>
                <p><strong>27 ONGs</strong></p>
                <p>53% a mais do que no último mês</p>
                <button>Visualizar perfis</button>
            </div>

            <div class="card stats">
                <h3>Usuários cadastrados na última semana</h3>
                <p><strong>33 usuários</strong></p>
                <p>27% a mais do que na última semana</p>
                <button>Visualizar perfis</button>
            </div>

            <div class="card sponsor">
                <h3>Maior patrocinador</h3>
                <p>Quantidade de doações: <strong>5</strong></p>
                <p>ONGs beneficiadas: <strong>16</strong></p>
                <p>Valor total: <strong>R$150.000,00</strong></p>
            </div>

            <div class="card volunteer">
                <h3>Usuário com mais horas como voluntário</h3>
                <p><strong>Genoveva Mendes</strong></p>
                <p>Quantidade de horas: <strong>37 horas</strong></p>
            </div>

            <div class="card donor">
                <h3>Maior doador</h3>
                <p><strong>José Bernardino</strong></p>
                <p>Quantidade de doações: <strong>11</strong></p>
                <p>ONGs beneficiadas: <strong>6</strong></p>
                <p>Valor total: <strong>R$48.923,19</strong></p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>

    <script src="profile-management.js"></script>
</body>
</html>