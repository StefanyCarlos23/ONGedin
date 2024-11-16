<?php
include('connection.php');

function getSuggestions($conn, $term) {
    $results = [];
    
    if ($term) {
        $queryOngs = $conn->prepare("
            SELECT u.nome 
            FROM usuario u
            JOIN administrador a ON u.id_usuario = a.id_administrador
            JOIN administrador_ong ao ON a.id_administrador = ao.id_admin_ong
            WHERE u.nome LIKE ? AND u.funcao = 'A'
            LIMIT 10");
        
        $likeTerm = '%' . $term . '%';
        $queryOngs->bind_param('s', $likeTerm);
        $queryOngs->execute();
        $resultOngs = $queryOngs->get_result();

        while ($row = $resultOngs->fetch_assoc()) {
            $results[] = $row['nome'];
        }

        $queryEventos = $conn->prepare("SELECT titulo FROM evento WHERE titulo LIKE ? LIMIT 10");
        $queryEventos->bind_param('s', $likeTerm);
        $queryEventos->execute();
        $resultEventos = $queryEventos->get_result();

        while ($row = $resultEventos->fetch_assoc()) {
            $results[] = $row['titulo'];
        }
    }

    return $results;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGedin | Detalhes do Evento</title>
    <link href="event-details.css" rel="stylesheet">
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

    .search{
        padding-top: 70px;
        padding-bottom: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 18px;
    }

    .search .search-text{
        margin: 0 20px;
        padding: 8px;
        width: 600px;
        font-size: 16px;
        border: #8A8A8A solid;
        border-width: 1px;
        border-radius: 10px;
    }

    .search a{
        margin: 0 20px;
    }

    form{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-container{
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .search-text {
        width: 100%;
        box-sizing: border-box;
    }

    .suggestions {
        color: #666666;
        display: none;
        flex-direction: column;
        position: absolute;
        top: 47px;
        width: 600px;
        max-height: 200px;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        overflow-y: auto;
    }

    .suggestion-item{
        padding: 10px;
        cursor: pointer;
    }

    .suggestion-item:hover{
        background-color: #f0f0f0;
    }

    .button-container{
        display: flex;
        justify-content: flex-end;
    }

    button{
        margin: 0 20px;
    }

    .search-btn{
        display: block;
        padding: 10px 15px;
        background-color: #87BFC7;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 10px;
        transition: color 0.1s;
        border: 1px solid #87BFC7;
        cursor: pointer;
    }

    .search-bar .search .back-btn{
        padding: 10px 15px;
        background-color: #b9b9b9;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 10px;
        transition: color 0.1s;
        border: 1px solid #b9b9b9;
    }

    .search-bar .search .search-btn:active{
        color: #666666;
    }

    .search-bar .search .search-btn:hover {
        background-color: #4d909a;
        transition: 0.4s;
    }

    .search-bar .search .back-btn:active{
        color: #666666;
    }

    .search-bar .search .back-btn:hover{
        background-color: #909090;
        transition: 0.4s;
    }

    .event-details{
        display: flex;
        align-items: flex-start;
        width: 80%;
        margin: 30px auto;
        margin-bottom: 70px;
    }

    .event-details .container{
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
    }

    .event-details .event-text{
        color: #666666;
    }

    .event-details .event-text h3,
    .event-details .event-text p{
        text-align: left;
    }

    .event-details .event-text h3{
        font-size: 30px;
    }

    .event-details .event-text p{
        margin-top: 20px;
        line-height: 1.5em;
    }

    .event-details .event-button .btn{
        display: flex;
        align-items: center;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #87BFC7;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        transition: color 0.1s;
        white-space: nowrap;
        border: none;
        font-size: 18px;
        cursor: pointer;
    }

    .event-details .event-button .btn:hover{
        background-color: #4d909a;
        transition: 0.4s;
    }

    .event-details .event-button .btn:active{
        color: #666666;
        background-color: #4d909a;
    }

    footer{
        border-top: 3px solid #87BFC7;
        background-color: #F6F6F6;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 90px;
        text-align: center;
    }
</style>
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
    </header>

    <section class="search-bar">
        <div class="search">
            <a class="back-btn" href="home.php">Voltar</a>
            <div class="form">
                <form action="search.php" method="GET">
                    <div class="search-container">
                        <input class="search-text" type="text" id="search-input" name="searchTerm" placeholder="Insira o nome da ONG ou título do evento" oninput="showSuggestions(this.value)" value="<?php echo isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : ''; ?>">
                        <div id="suggestions" class="suggestions"></div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="search-btn">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="event-details">
        <div class="container">
            <div class="event-text">
                <h3>Título do Evento</h3>
                <p>Descrição do Evento</p>
            </div>
            <div class="event-button">
                <button class="btn" id="subscribe-btn" onclick="subscribeEvent()">Inscrever-se</button>
            </div>
        </div>
    </section>
    <script src="event-details.js"></script>
    <footer>
        <p>&copy; 2024 - ONGedin - Conectando quem transforma o mundo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>