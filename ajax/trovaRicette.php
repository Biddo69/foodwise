<?php

    session_start();

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    $query = isset($_GET['parametro']) ? urlencode($_GET['parametro']) : '';
    $numEl = 5;
    $apiKey = '0072b1f00e0c42dbbd1757f463c8d8c9';

    if (empty($query)) {
        echo json_encode(['error' => "Parametro 'q' mancante o vuoto."]);
        exit;
    }

    $url = "https://api.spoonacular.com/recipes/complexSearch?query={$query}&number={$numEl}&apiKey={$apiKey}";

    try {
        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception("Errore nella richiesta all'API.");
        }

        $data = json_decode($response, true);

        if (!is_array($data) || !isset($data['results'])) {
            throw new Exception("Risposta JSON non valida o risultati mancanti.");
        }

        $results = array_map(function ($item) {
            return [
                'nome' => $item['title'] ?? 'Nome non disponibile',
                'urlImmagine' => $item['image'] ?? 'Immagine non disponibile',
            ];
        }, $data['results']);

        if (empty($results)) {
            throw new Exception("Nessuna ricetta trovata.");
        }
            
        echo json_encode($results);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

?>