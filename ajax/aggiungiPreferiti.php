<?php
session_start();
require_once("../includes/conn.php");
require_once("../DB/DBRicette.php");

if (!isset($_SESSION['userData']['id'])) {
    echo json_encode(['error' => 'Utente non autenticato.']);
    exit;
}

// Recupera l'ID dell'utente dalla sessione
$userId = $_SESSION['userData']['id'];

if (!isset($_GET['nome']) || empty($_GET['nome'])) {
    echo json_encode(['error' => "Parametro 'nome' mancante o vuoto."]);
    exit;
}

$nomeRicetta = htmlspecialchars($_GET['nome']); // Sanifica l'input

try {
    $urlDettagli = "http://localhost/5C/progetto/foodwise/foodwise/ajax/ottieniDettagliRicetta.php?nome=" . urlencode($nomeRicetta);
    $responseDettagli = file_get_contents($urlDettagli);

    if ($responseDettagli === false) {
        throw new Exception("Errore nella richiesta a ottieniDettagliRicetta.php.");
    }

    $dataDettagli = json_decode($responseDettagli, true);

    if (!isset($dataDettagli['success']) || !$dataDettagli['success']) {
        throw new Exception($dataDettagli['error'] ?? "Errore sconosciuto durante il recupero dei dettagli della ricetta.");
    }

    // Estrai i dettagli della ricetta
    $ricetta = $dataDettagli['results'][0];
    $titoloRicetta = $ricetta['title'];
    $immagineRicetta = $ricetta['image'];
    $porzioni = $ricetta['servings'] !== 'Non disponibile' ? intval($ricetta['servings']) : null;
    $tempoPreparazione = $ricetta['readyInMinutes'] !== 'Non disponibile' ? intval($ricetta['readyInMinutes']) : null;

    // Estrai i nutrienti
    $nutrienti = $ricetta['nutrients'];
    $calorie = $proteine = $carboidrati = $grassi = $zuccheri = $sodio = 0;

    foreach ($nutrienti as $nutriente) {
        switch (strtolower($nutriente['title'])) {
            case 'calories':
                $calorie = floatval($nutriente['amount']);
                break;
            case 'protein':
                $proteine = floatval($nutriente['amount']);
                break;
            case 'carbohydrates':
                $carboidrati = floatval($nutriente['amount']);
                break;
            case 'fat':
                $grassi = floatval($nutriente['amount']);
                break;
            case 'sugar':
                $zuccheri = floatval($nutriente['amount']);
                break;
            case 'sodium':
                $sodio = floatval($nutriente['amount']);
                break;
        }
    }

    $dbRicette = new DBRicette($conn);

    // Controlla se la ricetta esiste già nel database
    $resultCheck = $dbRicette->checkRicettaEsistente($titoloRicetta);
    if ($resultCheck->num_rows == 0) {
        $idRicetta = $dbRicette->inserisciRicetta($titoloRicetta, $immagineRicetta, $porzioni, $tempoPreparazione, $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio);
    } else {
        $row = $resultCheck->fetch_assoc();
        $idRicetta = $row['id'];
    }

    // Controlla se la ricetta è già nei preferiti dell'utente
    $resultPreferitiCheck = $dbRicette->checkRicettaNeiPreferiti($userId, $idRicetta);
    if ($resultPreferitiCheck->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => "La ricetta è già nei preferiti."]);
        exit;
    }

    // Aggiungi la ricetta ai preferiti dell'utente
    if ($dbRicette->aggiungiAiPreferiti($userId, $idRicetta)) {
        echo json_encode(['success' => true, 'message' => "Ricetta aggiunta ai preferiti con successo."]);
    } else {
        throw new Exception("Errore durante l'aggiunta della ricetta ai preferiti.");
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>