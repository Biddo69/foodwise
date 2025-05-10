<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../includes/conn.php");
require_once("../DB/DBRicette.php");

try {
    if (!isset($_GET['nome']) || empty($_GET['nome'])) {
        echo json_encode(['success' => false, 'error' => "Parametro 'nome' mancante o vuoto."]);
        exit;
    }

    $nomeRicetta = $_GET['nome'];

    // URL per richiamare `ottieniDettagliRicetta.php`
    $urlDettagli = "http://localhost/5C/progetto/foodwise/foodwise/ajax/ottieniDettagliRicetta.php?nome=" . urlencode($nomeRicetta);
    $responseDettagli = file_get_contents($urlDettagli);

    if ($responseDettagli === false) {
        throw new Exception("Errore nella richiesta a ottieniDettagliRicetta.php.");
    }

    $data = json_decode($responseDettagli, true);

    if (!isset($data['success']) || !$data['success']) {
        throw new Exception($data['error'] ?? "Errore sconosciuto durante il recupero dei dettagli della ricetta.");
    }

    $dettagliRicetta = $data['results'][0] ?? null;

    if (!$dettagliRicetta) {
        throw new Exception("Dettagli della ricetta non disponibili.");
    }

    // Estrai le porzioni totali della ricetta
    $porzioniTotali = $dettagliRicetta['servings'] ?? 1;

    // Estrai i nutrienti
    $nutrienti = $dettagliRicetta['nutrients'] ?? [];

    // Numero di porzioni consumate
    $porzioniConsumate = $_GET['porzioni'] ?? 1;

    // Ottieni i valori nutrizionali per le porzioni consumate
    $calorie = 0;
    $proteine = 0;
    $carboidrati = 0;
    $grassi = 0;
    $zuccheri = 0;
    $sodio = 0;

    foreach ($nutrienti as $nutriente) {
        switch (strtolower($nutriente['title'])) {
            case 'calories':
                $calorie = number_format((floatval($nutriente['amount']) * $porzioniConsumate) / $porzioniTotali, 2);
                break;
            case 'protein':
                $proteine = number_format((floatval($nutriente['amount']) * $porzioniConsumate) / $porzioniTotali, 2);
                break;
            case 'carbohydrates':
                $carboidrati = number_format((floatval($nutriente['amount']) * $porzioniConsumate) / $porzioniTotali, 2);
                break;
            case 'fat':
                $grassi = number_format((floatval($nutriente['amount']) * $porzioniConsumate) / $porzioniTotali, 2);
                break;
            case 'sugar':
                $zuccheri = number_format((floatval($nutriente['amount']) * $porzioniConsumate) / $porzioniTotali, 2);
                break;
            case 'sodium':
                $sodio = number_format(((floatval($nutriente['amount']) * $porzioniConsumate) / $porzioniTotali) / 1000, 2);
                break;
        }
    }

    // Ottieni l'idUtente dalla sessione
    $idUtente = $_SESSION['userData']['id'] ?? null;

    if (!$idUtente) {
        throw new Exception("ID utente non trovato nella sessione.");
    }

    // Ottieni la data corrente
    $dataCorrente = date('Y-m-d');

    $dbRicette = new DBRicette($conn);

    // Controlla se esiste già una riga per la data corrente e l'utente
    $resultCheck = $dbRicette->checkPianoCalorico($dataCorrente, $idUtente);

    if ($resultCheck->num_rows > 0) {
        // Esiste già una riga, somma i valori
        $row = $resultCheck->fetch_assoc();
        $calorie += $row['calorie'];
        $proteine += $row['proteine'];
        $carboidrati += $row['carboidrati'];
        $grassi += $row['grassi'];
        $zuccheri += $row['zuccheri'];
        $sodio += $row['sodio'];

        if ($dbRicette->aggiornaPianoCalorico($calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio, $dataCorrente, $idUtente)) {
            echo json_encode(['success' => true, 'message' => "Ricetta consumata con successo."]);
        } else {
            throw new Exception("Errore durante l'aggiornamento del piano calorico.");
        }
    } else {
        // Non esiste una riga, inserisci i nuovi valori
        if ($dbRicette->inserisciPianoCalorico($dataCorrente, $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio, $idUtente)) {
            echo json_encode(['success' => true, 'message' => "Ricetta consumata con successo."]);
        } else {
            throw new Exception("Errore durante l'inserimento nel piano calorico.");
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>