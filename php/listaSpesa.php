<?php
    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");
    if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != true) {
        header("Location: login.php?messaggio=Devi effettuare il login per accedere a questa pagina.");
        exit;
    }
?>
    <script src="../js/listaScript.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            generaLista(); // Chiamata automatica alla funzione per generare la lista
        });
    </script>

    <div class="lista-container">
        <h1>La tua lista della spesa</h1>
        <div id="messaggio" class="messaggio"></div>
        <!-- Contenitore per la lista della spesa -->
        <ul id="listaSpesa" class="lista-spesa"></ul>
    </div>

<?php
    require_once("../includes/footer.php");
?>