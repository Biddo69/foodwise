<?php

    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");

    // Controlla se l'utente è autenticato
    if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != true) {
        header("Location: login.php?messaggio=Devi effettuare il login per accedere a questa pagina.");
        exit;
    }

    // Recupera gli elementi della lista della spesa dal database
    $userId = $_SESSION['user_id']; // Assumendo che l'ID utente sia salvato nella sessione
    $query = "SELECT * FROM lista_spesa WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $listaSpesa = $result->fetch_all(MYSQLI_ASSOC);

?>