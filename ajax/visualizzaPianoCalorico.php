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

    // Controlla se l'utente è autenticato
    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => "Utente non autenticato."]);
        exit;
    }

    $idUtente = $_SESSION['userData']['id'];
    $bmr = $_SESSION['userData']['calorie_giornaliere'] ?? null;

    try {  
        // Query per recuperare i dati del piano calorico ordinati per data decrescente
        $query = "SELECT * FROM pianoCalorico WHERE idUtente = ? ORDER BY data DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idUtente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $pianiCalorici = [];

            while ($row = $result->fetch_assoc()) {
                $pianiCalorici[] = $row;
            }

            // Restituisci i dati in formato JSON, includendo il BMR
            echo json_encode([
                'success' => true,
                'data' => $pianiCalorici,
                'bmr' => $bmr
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Nessun piano calorico trovato per l'utente.",
            ]);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

?>