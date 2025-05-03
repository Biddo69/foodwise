<?php

    session_start();

    // Recupera il parametro 'q' dalla query string
    $query = isset($_GET['parametro']) ? urlencode($_GET['parametro']) : '';
    $numEl = 5;
    $apiKey = '231a8a4b07354057a8d4a56f0fb7716c';

    // Controlla se il parametro 'q' è vuoto
    if (empty($query)) {
        echo json_encode(['error' => "Parametro 'q' mancante o vuoto."]);
        exit;
    }

    // Costruisci l'URL per l'API Spoonacular
    $url = "https://api.spoonacular.com/recipes/complexSearch?query={$query}&number={$numEl}&apiKey={$apiKey}";

    try {
        // Effettua la richiesta all'API
        $response = file_get_contents($url);

        // Controlla se la risposta è valida
        if ($response === false) {
            throw new Exception("Errore nella richiesta all'API.");
        }

        // Decodifica il JSON
        $data = json_decode($response, true);

        // Controlla se il JSON è valido
        if (!is_array($data) || !isset($data['results'])) {
            throw new Exception("Risposta JSON non valida o risultati mancanti.");
        }

        // Estrai i risultati
        $results = array_map(function ($item) {
            return [
                'nome' => $item['title'] ?? 'Nome non disponibile',
                'urlImmagine' => $item['image'] ?? 'Immagine non disponibile',
                'id' => $item['id'] ?? null
            ];
        }, $data['results']);
        
        // Restituisci i risultati come JSON
        echo json_encode($results);
    } catch (Exception $e) {
        // Gestione degli errori
        echo json_encode(['error' => $e->getMessage()]);
    }

?>