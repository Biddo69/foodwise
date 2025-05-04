<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controlla se il parametro 'nome' è stato passato
if (!isset($_GET['nome']) || empty($_GET['nome'])) {
    echo json_encode(['error' => "Parametro 'nome' mancante o vuoto."]);
    exit;
}

$nomeRicetta = $_GET['nome'];

// URL per richiamare `ottieniDettagliRicetta.php`
$url = "http://localhost/5C/progetto/foodwise/foodwise/ajax/ottieniDettagliRicetta.php?nome=" . urlencode($nomeRicetta);

try {
    // Effettua la richiesta a `ottieniDettagliRicetta.php`
    $response = file_get_contents($url);

    // Controlla se la risposta è valida
    if ($response === false) {
        throw new Exception("Errore nella richiesta a ottieniDettagliRicetta.php.");
    }

    // Decodifica il JSON restituito
    $data = json_decode($response, true);

    // Controlla se la risposta contiene un errore
    if (!isset($data['success']) || !$data['success']) {
        throw new Exception($data['error'] ?? "Errore sconosciuto durante il recupero dei dettagli della ricetta.");
    }

    // Ottieni i dettagli della ricetta
    $dettagliRicetta = $data['results'][0] ?? null;

    if (!$dettagliRicetta) {
        throw new Exception("Dettagli della ricetta non disponibili.");
    }

    

} catch (Exception $e) {
    // Gestione degli errori
    echo "<p>Errore: " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>