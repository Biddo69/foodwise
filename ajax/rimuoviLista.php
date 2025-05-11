<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    require_once("../includes/conn.php");
    require_once("../DB/DBIngredienti.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    $userId = $_SESSION['userData']['id'];

    if (!isset($_GET['nome']) || empty($_GET['nome'])) {
        echo json_encode(['error' => "Parametro 'nome' mancante o vuoto."]);
        exit;
    }

    $nomeIngrediente = $_GET['nome'];

    try {
        $dbIngredienti = new DBIngredienti($conn);

        // Rimuovi l'ingrediente dalla lista
        $affectedRows = $dbIngredienti->rimuoviIngredienteDaLista($userId, $nomeIngrediente);

        if ($affectedRows > 0) {
            echo json_encode(['success' => true, 'message' => "Ingrediente rimosso con successo."]);
        } else {
            echo json_encode(['success' => false, 'message' => "Ingrediente non trovato nella lista."]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
?>