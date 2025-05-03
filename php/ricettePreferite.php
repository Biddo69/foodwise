<?php

    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");

    // Controlla se l'utente è autenticato
    if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != true) {
        header("Location: login.php?messaggio=Devi effettuare il login per accedere a questa pagina.");
        exit;
    }
?>
    <script src="../js/ricetteScript.js"></script> <!-- Carica il file ricetteScript.js -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            visualizzaPreferiti(); // Chiamata automatica alla funzione per generare la lista
        });
    </script>

    <h1>Le tue ricette preferite</h1>

    <!-- Contenitore per la lista delle ricette preferite -->
    <ul id="preferiti" class="lista-ricette"></ul> <!-- Aggiungi una classe per lo stile -->

<?php
    require_once("../includes/footer.php");
?>