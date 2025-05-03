<?php

    session_start();
    require_once("../includes/conn.php");

    // Controlla se l'utente è autenticato
    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    // Recupera l'ID dell'utente dalla sessione
    $userId = $_SESSION['userData']['id'];

    // Controlla se il parametro 'nome' è stato passato
    if (!isset($_GET['nome']) || empty($_GET['nome'])) {
        echo json_encode(['error' => "Parametro 'nome' mancante o vuoto."]);
        exit;
    }

    $nomeIngrediente = $_GET['nome'];

    try {
        // Query per rimuovere l'ingrediente dalla lista dell'utente
        $stmt = $conn->prepare("
            DELETE il
            FROM ingredienteInLista il
            JOIN listaSpesa ls ON il.idLista = ls.id
            JOIN ingrediente i ON il.idIngrediente = i.id
            WHERE ls.idUtente = ? AND i.nome = ?
        ");
        $stmt->bind_param("is", $userId, $nomeIngrediente);
        $stmt->execute();

        // Controlla se è stato rimosso almeno un record
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => "Ingrediente rimosso con successo."]);
        } else {
            echo json_encode(['success' => false, 'message' => "Ingrediente non trovato nella lista."]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

?>