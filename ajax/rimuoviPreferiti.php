<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("../includes/conn.php");
    require_once("../DB/DBRicette.php");

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
        $dbRicette = new DBRicette($conn);

        // Ottieni l'ID della ricetta in base al nome
        $resultRicetta = $dbRicette->getIdRicettaByNome($nomeRicetta);

        if ($resultRicetta->num_rows == 0) {
            echo json_encode(['error' => "Ricetta non trovata."]);
            exit;
        }

        $row = $resultRicetta->fetch_assoc();
        $idRicetta = $row['id'];

        // Rimuovi la ricetta dai preferiti
        $affectedRows = $dbRicette->rimuoviRicettaDaiPreferiti($userId, $idRicetta);

        if ($affectedRows > 0) {
            echo json_encode(['success' => true, 'message' => "Ricetta rimossa dai preferiti con successo."]);
        } else {
            echo json_encode(['error' => "Ricetta non trovata nei preferiti."]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
?>