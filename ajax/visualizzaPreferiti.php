<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../includes/conn.php");

// Controlla se l'utente è autenticato
if (!isset($_SESSION['userData']['id'])) {
    echo json_encode(['error' => 'Utente non autenticato.']);
    exit;
}

$userId = $_SESSION['userData']['id'];

try {
    // Query per recuperare tutte le colonne della tabella ricetta per le ricette preferite dell'utente
    $stmt = $conn->prepare("
        SELECT r.* 
        FROM ricettePreferite rp
        JOIN ricetta r ON rp.idRicetta = r.id
        WHERE rp.idUtente = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Controlla se ci sono risultati
    if ($result->num_rows > 0) {
        $ricette = [];
        while ($row = $result->fetch_assoc()) {
            $ricette[] = $row; // Aggiunge direttamente tutte le colonne della riga
        }
        echo json_encode($ricette);
    } else {
        echo json_encode([]); // Nessuna ricetta trovata
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>