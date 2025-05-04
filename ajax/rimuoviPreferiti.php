<?php

    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("../includes/conn.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    if (!isset($_GET['nome']) || empty($_GET['nome'])) {
        echo json_encode(['error' => "Nome ricetta mancante."]);
        exit;
    }

    $userId = $_SESSION['userData']['id'];
    $nomeRicetta = htmlspecialchars($_GET['nome']);

    try {
        // Query per ottenere l'ID della ricetta in base al nome
        $queryRicetta = "SELECT id FROM ricetta WHERE nome = ?";
        $stmtRicetta = $conn->prepare($queryRicetta);
        $stmtRicetta->bind_param("s", $nomeRicetta);
        $stmtRicetta->execute();
        $resultRicetta = $stmtRicetta->get_result();

        if ($resultRicetta->num_rows === 0) {
            echo json_encode(['error' => "Ricetta non trovata."]);
            exit;
        }

        $row = $resultRicetta->fetch_assoc();
        $idRicetta = $row['id'];

        // Query per rimuovere la ricetta dai preferiti
        $query = "DELETE FROM ricettePreferite WHERE idUtente = ? AND idRicetta = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $idRicetta);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => "Ricetta rimossa dai preferiti con successo."]);
        } else {
            echo json_encode(['error' => "Ricetta non trovata nei preferiti."]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

?>