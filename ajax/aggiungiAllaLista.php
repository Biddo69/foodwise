<?php

// Avvia la sessione
session_start();
require_once("../includes/conn.php");

// Controlla se il parametro 'nome' è stato passato nella query string

if (isset($_GET['nome'])) {
    // htmlspecialchars() previene input come <script>alert(ciao)</script>, se ho capito bene
    //non è molto utile perchè l'input non lo sceglie l'utente ma non si sa mai
    $nomeProdotto = htmlspecialchars($_GET['nome']);

    // Chiave API di Spoonacular
    $apiKey = 'f3c93c7a719e4b1b86348f2b132b1e2e';

    // URL per ottenere i dettagli dell'ingrediente
    $url = "https://api.spoonacular.com/food/ingredients/search?query=" . urlencode($nomeProdotto) . "&apiKey=" . $apiKey;

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

        // Prepara i dati per l'inserimento nel database
        //?? fa i soliti controlli, nel caso non lo da imposta il nome che ha ottenuto dal get
        $nome = $dettagli['name'] ?? $nomeProdotto;
        $immagine = $dettagli['image'] ?? '';
        //in pratica la quantità del nutriente si trova all'interno di nutrients, a sua volta in nutrition
        $calorie = $dettagli['nutrition']['nutrients'][0]['amount'] ?? 0;
        $proteine = $dettagli['nutrition']['nutrients'][1]['amount'] ?? 0;
        $grassi = $dettagli['nutrition']['nutrients'][2]['amount'] ?? 0;
        $carboidrati = $dettagli['nutrition']['nutrients'][3]['amount'] ?? 0;
        $zucchero = $dettagli['nutrition']['nutrients'][5]['amount'] ?? 0;
        $sodio = $dettagli['nutrition']['nutrients'][7]['amount'] ?? 0;
        $categoria = $dettagli['categoryPath'][0] ?? 'Generico';

        // Query per inserire i dati nel database
        $query = "INSERT INTO ingredienti (nome, immagine, calorie, proteine, carboidrati, grassi, zucchero, sodio, categoria)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepara la query
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssdddddds",
            $nome,
            $immagine,
            $calorie,
            $proteine,
            $carboidrati,
            $grassi,
            $zucchero,
            $sodio,
            $categoria
        );

        // Esegui la query
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Prodotto '$nome' aggiunto al database."]);
        } else {
            throw new Exception("Errore durante l'inserimento nel database.");
        }
    } catch (Exception $e) {
        // Gestione degli errori
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Restituisci un errore se il parametro 'nome' non è stato passato
    echo json_encode(['success' => false, 'message' => "Parametro 'nome' mancante."]);
}

?>