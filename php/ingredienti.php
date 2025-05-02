<?php

    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");
    if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != true) {
        header("Location: login.php?messaggio=Devi effettuare il login per accedere a questa pagina.");
        exit;
    }
?>
    <script src="../js/ingredientiScript.js"></script>
    <h1>Ricerca un ingrediente</h1>

    <div class="search-container">
        <input type="text" id="parametro" placeholder="Es. burro">
        <input type="image" src="../img/cerca.png" width="20" height="20" onclick="cercaIngredienti()">
    </div>
    
    <!-- lista di massimo 5 risultati dei parametri di ricerca -->
    <ul id="risultati"></ul>

<?php
    require_once("../includes/footer.php");
?>