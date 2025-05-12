<?php

    if(!isset($_SESSION)) {
        session_start();
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("../includes/apiKey.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    // Recupera il parametro di ricerca dalla query string
    $query = isset($_GET['parametro']) ? urlencode($_GET['parametro']) : '';
    $numEl = 5;
    
    if (empty($query)) {
        echo json_encode(['error' => "Parametro 'parametro' mancante o vuoto."]);
        exit;
    }
    
    $url = "https://api.spoonacular.com/food/ingredients/autocomplete?query={$query}&number={$numEl}&apiKey={$apiKey}";

    try {
        $response = file_get_contents($url);


        if ($response == false) {
            throw new Exception("Errore nella richiesta all'API.");
        }

        // Decodifica il JSON
        $data = json_decode($response, true);
        
        if (!is_array($data)) {
            throw new Exception("Risposta JSON non valida.");
        }

        // Estrai i risultati
        $results = array_map(function ($item) {
            $baseImageUrl = "https://spoonacular.com/cdn/ingredients_100x100/";
            return [
                //in questo caso uso ?? per impostare un valore di default se non esiste
                'nome' => $item['name'] ?? 'Nome non disponibile',
                //per l'immagine mi devo creare l'url mettendo insieme la base dell'url e il nome dell'immagine
                'urlImmagine' => isset($item['image']) ? $baseImageUrl . $item['image'] : 'Immagine non disponibile',
                'id' => $item['id'] ?? null
            ];
        }, $data);
        
        echo json_encode($results);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

?>