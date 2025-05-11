<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("../includes/conn.php");
    require_once("../DB/DBPianoCalorico.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    $idUtente = $_SESSION['userData']['id'];
    $bmr = $_SESSION['userData']['calorie_giornaliere'] ?? null;

    try {
        $dbPianoCalorico = new DBPianoCalorico($conn);

        // Recupera i dati del piano calorico
        $result = $dbPianoCalorico->getPianoCaloricoPerUtente($idUtente);

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
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
?>