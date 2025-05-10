<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("../includes/conn.php");
    require_once("../DB/DBRicette.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    $userId = $_SESSION['userData']['id'];

    try {
        $dbRicette = new DBRicette($conn);

        // Recupera le ricette preferite dell'utente
        $result = $dbRicette->getRicettePreferite($userId);

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