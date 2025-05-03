<?php
    session_start();

    // Distruggi tutte le variabili di sessione
    session_unset();

    // Distruggi la sessione
    session_destroy();

    // Reindirizza l'utente alla pagina di login o alla home
    header("Location: login.php?messaggio=Logout effettuato con successo.");
    exit;
?>