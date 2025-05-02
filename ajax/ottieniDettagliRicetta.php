<?php

    session_start();

    // Recupera il parametro 'q' dalla query string
    $query = isset($_GET['nome']) ? urlencode($_GET['nome']) : '';
    $numEl = 5;
    $apiKey = 'f3c93c7a719e4b1b86348f2b132b1e2e';

    // Controlla se il parametro 'q' è vuoto
    if (empty($query)) {
        echo json_encode(['error' => "Parametro 'q' mancante o vuoto."]);
        exit;
    }

    // Costruisci l'URL per l'API Spoonacular
    $url = "https://api.spoonacular.com/recipes/complexSearch?query={$query}&number={$numEl}&addRecipeInformation=true&apiKey={$apiKey}";

    try {
        // Effettua la richiesta all'API
        $response = file_get_contents($url);

        // Controlla se la risposta è valida
        if ($response === false) {
            throw new Exception("Errore nella richiesta all'API.");
        }

        // Decodifica il JSON
        $data = json_decode($response, true);

        if ($data == null) {
            throw new Exception("Errore nella decodifica del JSON.");
        }

        // Controlla se il JSON è valido
        if (!is_array($data) || !isset($data['results'])) {
            throw new Exception("Risposta JSON non valida o risultati mancanti.");
        }

        // Estrai i risultati
        $results = [];
    
        
        // Restituisci i risultati come JSON
        echo json_encode(['success' => true, 'ricetta' => $results]);
    } catch (Exception $e) {
        // Gestione degli errori
        echo json_encode(['error' => $e->getMessage()]);
    }

?>