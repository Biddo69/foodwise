<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once("../includes/conn.php");
    require_once("../includes/apiKey.php");
    require_once("../DB/DBIngredienti.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    if (isset($_GET['nome'])) {
        $nomeIngrediente = htmlspecialchars($_GET['nome']);
        
        $url = "https://api.spoonacular.com/food/ingredients/search?query=" . urlencode($nomeIngrediente) . "&apiKey=" . $apiKey;

        try {
            $response = file_get_contents($url);
            if ($response == false) {
                throw new Exception("Errore nella richiesta all'API.");
            }

            $data = json_decode($response, true);
            if (!isset($data['results']) || empty($data['results'])) {
                throw new Exception("Nessun ingrediente trovato con il nome specificato.");
            }

            //prendo quello in posizione 0 che dovrebbe essere il risultato più rilevante
            $ingrediente = $data['results'][0];
            $idIngrediente = $ingrediente['id'];

            // Effettua una seconda richiesta per ottenere i dettagli nutrizionali
            $urlDettagli = "https://api.spoonacular.com/food/ingredients/$idIngrediente/information?amount=100&unit=gram&apiKey=" . $apiKey;
            $responseDettagli = file_get_contents($urlDettagli);
            if ($responseDettagli == false) {
                throw new Exception("Errore nella richiesta dei dettagli nutrizionali.");
            }
            $dettagli = json_decode($responseDettagli, true);

            $dbIngredienti = new DBIngredienti($conn);

            // Controlla se l'ingrediente esiste già
            $resultCheck = $dbIngredienti->checkIngredienteEsistente($nomeIngrediente);
            if ($resultCheck->num_rows == 0) {
                $nome = $dettagli['name'] ?? $nomeIngrediente;
                $immagine = isset($dettagli['image']) ? "https://spoonacular.com/cdn/ingredients_100x100/" . $dettagli['image'] : 'Immagine non disponibile';
                $dbIngredienti->inserisciIngrediente($idIngrediente, $nome, $immagine);
            } else {
                $rowIngrediente = $resultCheck->fetch_assoc();
                $idIngrediente = $rowIngrediente['id'];
            }

            // Controlla se la lista esiste
            $resultLista = $dbIngredienti->checkListaEsistente($_SESSION["userData"]["id"]);
            if ($resultLista->num_rows == 0) {
                $nomeLista = "listaDellaSpesa";
                $dbIngredienti->creaLista($nomeLista, $_SESSION["userData"]["id"]);
                $resultLista = $dbIngredienti->checkListaEsistente($_SESSION["userData"]["id"]);
            }
            $rowLista = $resultLista->fetch_assoc();
            $idLista = $rowLista['id'];

            // Controlla se l'ingrediente è già nella lista
            $resultCheckIngredienteInLista = $dbIngredienti->checkIngredienteInLista($idLista, $idIngrediente);
            if ($resultCheckIngredienteInLista->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => "L'ingrediente è già presente nella lista."]);
                exit;
            }

            // Aggiungi l'ingrediente alla lista
            if ($dbIngredienti->aggiungiIngredienteALista($idLista, $idIngrediente)) {
                echo json_encode(['success' => true, 'message' => "Ingrediente aggiunto alla lista della spesa."]);
            } else {
                throw new Exception("Errore durante l'aggiunta dell'ingrediente alla lista.");
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => "Parametro 'nome' mancante."]);
    }
?>