<?php

    session_start();
    require_once("../includes/conn.php");

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
        $calorie = 0;
        $proteine = 0;
        $carboidrati = 0;
        $grassi = 0;
        $zuccheri = 0;
        $sodio = 0;

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

        // Controlla se la ricetta esiste già nel database
        $queryCheck = "SELECT id FROM ricetta WHERE nome = ?";
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->bind_param("s", $titoloRicetta);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        // Se la ricetta non esiste, la inserisce nel database
        if ($resultCheck->num_rows == 0) {
            $queryInsert = "INSERT INTO ricetta (nome, immagine, porzioni, tempoPreparazione, calorie, proteine, carboidrati, grassi, zuccheri, sodio) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($queryInsert);
            $stmtInsert->bind_param(
                "ssiiiiiiii",
                $titoloRicetta,
                $immagineRicetta,
                $porzioni,
                $tempoPreparazione,
                $calorie,
                $proteine,
                $carboidrati,
                $grassi,
                $zuccheri,
                $sodio
            );
            $stmtInsert->execute();
            $idRicetta = $stmtInsert->insert_id; // Ottieni l'ID della ricetta appena inserita
        } else {
            $row = $resultCheck->fetch_assoc();
            $idRicetta = $row['id']; // Recupera l'ID della ricetta esistente
        }

        // Controlla se la ricetta è già nei preferiti dell'utente
        $queryPreferitiCheck = "SELECT * FROM ricettePreferite WHERE idUtente = ? AND idRicetta = ?";
        $stmtPreferitiCheck = $conn->prepare($queryPreferitiCheck);
        $stmtPreferitiCheck->bind_param("ii", $userId, $idRicetta);
        $stmtPreferitiCheck->execute();
        $resultPreferitiCheck = $stmtPreferitiCheck->get_result();

        if ($resultPreferitiCheck->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => "La ricetta è già nei preferiti."]);
            exit;
        }

        // Aggiungi la ricetta ai preferiti dell'utente
        $queryPreferiti = "INSERT INTO ricettePreferite (idUtente, idRicetta) VALUES (?, ?)";
        $stmtPreferiti = $conn->prepare($queryPreferiti);
        $stmtPreferiti->bind_param("ii", $userId, $idRicetta);
        $stmtPreferiti->execute();

        echo json_encode(['success' => true, 'message' => "Ricetta aggiunta ai preferiti con successo."]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

?>