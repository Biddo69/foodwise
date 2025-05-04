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
    <script src="../js/pianoCaloricoScript.js"></script> <!-- Carica il file pianoCaloricoScript.js -->

    <h1>Il tuo piano calorico</h1>

    <!-- Contenitore per il piano calorico -->
    <div id="pianoCalorico" class="piano-calorico-contenitore"></div> <!-- Aggiungi una classe per lo stile -->

<?php
require_once("../includes/footer.php");
?>