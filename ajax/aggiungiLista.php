<?php

// Avvia la sessione
session_start();
require_once("../includes/conn.php");

// Controlla se il parametro 'nome' è stato passato nella query string

if (isset($_GET['nome'])) {
    // htmlspecialchars() previene input come <script>alert(ciao)</script>, se ho capito bene
    //non è molto utile perchè l'input non lo sceglie l'utente ma non si sa mai
    $nomeIngrediente = htmlspecialchars($_GET['nome']);

    // Chiave API di Spoonacular
    $apiKey = 'f3c93c7a719e4b1b86348f2b132b1e2e';

    // URL per ottenere i dettagli dell'ingrediente
    $url = "https://api.spoonacular.com/food/ingredients/search?query=" . urlencode($nomeIngrediente) . "&apiKey=" . $apiKey;

    try {
        // Effettua la richiesta all'API
        $response = file_get_contents($url);

        // Controlla se la risposta è valida
        if ($response == false) {
            throw new Exception("Errore nella richiesta all'API.");
        }

        // Decodifica il JSON
        $data = json_decode($response, true);

        if (!isset($data['results']) || count($data['results']) == 0) {
            throw new Exception("Nessun ingrediente trovato con il nome specificato.");
        }

        // Prendi il primo risultato, che in teoria è il più rilevante ma ci sono delle anomalie
        $ingrediente = $data['results'][0];

        //l'id mi serve per eseguire la richiesta dei dettagli nutrizionali
        $idIngrediente = $ingrediente['id'];

        // URL per ottenere i dettagli nutrizionali dell'ingrediente
        $urlDettagli = "https://api.spoonacular.com/food/ingredients/$idIngrediente/information?amount=100&unit=gram&apiKey=" . $apiKey;
        $responseDettagli = file_get_contents($urlDettagli);

        // Controlla se la risposta è valida
        if ($responseDettagli == false) {
            throw new Exception("Errore nella richiesta dei dettagli nutrizionali.");
        }

        // Decodifica il JSON dei dettagli
        $dettagli = json_decode($responseDettagli, true);

        // Controlla se l'ingrediente esiste già nel database
        $queryCheck = "SELECT id FROM ingrediente WHERE nome = ?";
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->bind_param("s", $nomeIngrediente);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        //se l'ingrediente non esiste nel database lo inserisco 
        if ($resultCheck->num_rows == 0) {
        
            // Prepara i dati per l'inserimento nel database
            //?? fa i soliti controlli, nel caso non lo da imposta il nome che ha ottenuto dal get
            $nome = $dettagli['name'] ?? $nomeIngrediente;
            $immagine = $dettagli['image'] ?? '';

            //in pratica la quantità del nutriente si trova all'interno di nutrients, a sua volta in nutrition
            // $calorie = $dettagli['nutrition']['nutrients'][0]['amount'] ?? 0;
            // $proteine = $dettagli['nutrition']['nutrients'][1]['amount'] ?? 0;
            // $grassi = $dettagli['nutrition']['nutrients'][2]['amount'] ?? 0;
            // $carboidrati = $dettagli['nutrition']['nutrients'][3]['amount'] ?? 0;
            // $zucchero = $dettagli['nutrition']['nutrients'][5]['amount'] ?? 0;
            // $sodio = $dettagli['nutrition']['nutrients'][7]['amount'] ?? 0;
            // $categoria = $dettagli['categoryPath'][0] ?? 'Generico';

            // Query per inserire i dati nel database
            // $query = "INSERT INTO ingrediente (nome, immagine, calorie, proteine, grassi, carboidrati, zucchero, sodio, categoria)

            $query = "INSERT INTO ingrediente (nome, immagine)
                    VALUES (?, ?)";

            // Prepara la query
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                // "ssdddddds",
                "ss",
                $nome,
                $immagine,
                // $calorie,
                // $proteine,
                // $grassi,
                // $carboidrati,
                // $zucchero,
                // $sodio,
                // $categoria
            );

            // Esegui la query
            $stmt->execute();

            //questa funzione ritorna l'ultimo id inserito come autoincrement da stmt, molto figo
            $idIngrediente = $stmt->insert_id;
        } 
        else {
            $rowIngrediente = $resultCheck->fetch_assoc();
            $idIngrediente = $rowIngrediente['id']; // Ottieni l'id dell'ingrediente esistente
        }

    

        // Controlla se la lista esiste già nel database
        $querylista = "SELECT id FROM listaSpesa WHERE idUtente = ?";
        $stmtlista = $conn->prepare($querylista);
        $stmtlista->bind_param("i", $_SESSION["userData"]["id"]);
        $stmtlista->execute();
        $resultlista = $stmtlista->get_result();   

        if ($resultlista->num_rows == 0) {
            // Crea una nuova lista con il nome generico "listaDellaSpesa"
            $nomeLista = "listaDellaSpesa";
            $queryCreaLista = "INSERT INTO listaSpesa (nome, idUtente) VALUES (?, ?)";
            $stmtCreaLista = $conn->prepare($queryCreaLista);
            $stmtCreaLista->bind_param("si", $nomeLista, $_SESSION["userData"]["id"]);
            
            if (!$stmtCreaLista->execute()) {
                throw new Exception("Errore durante la creazione della lista della spesa.");
            }
        }
        else if ($resultlista->num_rows > 0) {
            $rowLista = $resultlista->fetch_assoc();
            $idLista = $rowLista['id'];
        } 
        else {
            throw new Exception("Errore nel recupero della lista della spesa.");
        }

        // Controlla se l'ingrediente è già presente nella lista
        $queryCheckIngredienteInLista = "SELECT idLista FROM ingredienteinlista WHERE idLista = ? AND idIngrediente = ?";
        $stmtCheckIngredienteInLista = $conn->prepare($queryCheckIngredienteInLista);
        $stmtCheckIngredienteInLista->bind_param("ii", $idLista, $idIngrediente);
        $stmtCheckIngredienteInLista->execute();
        $resultCheckIngredienteInLista = $stmtCheckIngredienteInLista->get_result();

        if ($resultCheckIngredienteInLista->num_rows > 0) {
            throw new Exception("L'ingrediente è già presente nella lista.");
        }

        // Inserisce l'ingrediente nella tabella listaIngrediente
        $queryInserisciIngrediente = "INSERT INTO ingredienteinlista (idLista, idIngrediente) VALUES (?, ?)";
        $stmtInserisciIngrediente = $conn->prepare($queryInserisciIngrediente);
        $stmtInserisciIngrediente->bind_param("ii", $idLista, $idIngrediente);

        if ($stmtInserisciIngrediente->execute()) {
            echo json_encode(['success' => true, 'message' => "Ingrediente aggiunto alla lista della spesa."]);
        } else {
            throw new Exception("Errore durante l'aggiunta dell'ingrediente alla lista.");
        }



    } catch (Exception $e) {
        // Gestione degli errori
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Restituisci un errore se il parametro 'nome' non è stato passato
    //con questa riga controlla se il messaggio è settato, in caso contrario significa che è uscito al primo controllo sul nome
    echo json_encode(['success' => false, 'message' => isset($e) ? $e->getMessage() : "Parametro 'nome' mancante."]);
}

?>