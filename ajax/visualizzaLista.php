<?php

    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
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