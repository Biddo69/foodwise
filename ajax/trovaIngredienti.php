<?php

    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // Recupera il parametro 'q' dalla query string
    $query = isset($_GET['parametro']) ? urlencode($_GET['parametro']) : '';
    $numEl = 5;
    $apiKey = '231a8a4b07354057a8d4a56f0fb7716c';

    // Controlla se il parametro 'q' è vuoto
    if (empty($query)) {
        echo json_encode(['error' => "Parametro 'q' mancante o vuoto."]);
        exit;
    }
    else {

        // Costruisci l'URL per l'API Spoonacular
        $url = "https://api.spoonacular.com/food/ingredients/autocomplete?query={$query}&number={$numEl}&apiKey={$apiKey}";

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
            if (!is_array($data)) {
                throw new Exception("Risposta JSON non valida.");
            }

            // Estrai i risultati
            //La funzione map cicla tutti gli elementi dell'array e li modifica in un certo formato
            $results = array_map(function ($item) {
                $baseImageUrl = "https://spoonacular.com/cdn/ingredients_100x100/"; // Base URL per le immagini
                return [
                    'nome' => $item['name'] ?? 'Nome non disponibile',
                    'urlImmagine' => isset($item['image']) ? $baseImageUrl . $item['image'] : 'Immagine non disponibile',
                    'id' => $item['id'] ?? null
                ];
            }, $data);
            
            // Restituisci i risultati come JSON
            echo json_encode($results);
        } catch (Exception $e) {
            // Gestione degli errori
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


?>