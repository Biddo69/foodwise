<?php

    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");
    if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != true) {
        header("Location: login.php?messaggio=Devi effettuare il login per accedere a questa pagina.");
        exit;
    }
?>
    <script src="../js/ricetteScript.js"></script>
    <script src="../js/listaScript.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Leggi il parametro 'nome' dalla query string
            const params = new URLSearchParams(window.location.search);
            const nomeRicetta = params.get('nome');

            // Se il parametro 'nome' esiste, chiama la funzione dettagliRicetta
            if (nomeRicetta) {
                dettagliRicetta(nomeRicetta);
            } else {
                alert("Nome della ricetta non specificato.");
            }
        });
    </script>
    
    <div id="dettagliRicetta">
        <!-- I dettagli della ricetta verranno caricati qui -->
    </div>

<?php
    require_once("../includes/footer.php");
?>