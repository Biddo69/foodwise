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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cercaIngredienti(false); // Chiamata automatica alla funzione
        });
    </script>

<?php
    require_once("../includes/footer.php");
?>