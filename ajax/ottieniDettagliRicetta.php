<?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();


    $query = isset($_GET['nome']) ? $_GET['nome'] : '';
    $apiKey = '0072b1f00e0c42dbbd1757f463c8d8c9';

    if (empty($query)) {
        echo json_encode(['error' => "Parametro 'nome' mancante o vuoto."]);
        exit;
    }

    $url = "https://api.spoonacular.com/recipes/complexSearch?query={$query}&number=1&apiKey={$apiKey}";

    try {
        $response = file_get_contents($url);

        if ($response == false) {
            throw new Exception("Errore nella richiesta all'API.");
        }
        $data = json_decode($response, true);

        if ($data == null) {
            throw new Exception("Errore nella decodifica del JSON.");
        }

        if (!is_array($data) || !isset($data['results']) || count($data['results']) === 0) {
            throw new Exception("Nessuna ricetta trovata.");
        }

        // Restituisci solo il primo risultato
        $recipe = $data['results'][0];

        // Effettua una seconda richiesta per ottenere i dettagli completi della ricetta
        $recipeId = $recipe['id'];
        $detailsUrl = "https://api.spoonacular.com/recipes/{$recipeId}/information?apiKey={$apiKey}";
        $detailsResponse = file_get_contents($detailsUrl);

        if ($detailsResponse == false) {
            throw new Exception("Errore nella richiesta dei dettagli della ricetta.");
        }

        $detailsData = json_decode($detailsResponse, true);

        // Estrai gli ingredienti
        $ingredients = [];
        if (isset($detailsData['extendedIngredients']) && is_array($detailsData['extendedIngredients'])) {
            $ingredients = array_map(function ($ingredient) {
                return [
                    'id' => $ingredient['id'] ?? null, // ID dell'ingrediente
                    'nome' => $ingredient['name'] ?? 'Ingrediente non disponibile' // Nome base dell'ingrediente
                ];
            }, $detailsData['extendedIngredients']);
        }

        // Effettua una seconda richiesta per ottenere i nutrienti
        $nutritionUrl = "https://api.spoonacular.com/recipes/{$recipeId}/nutritionWidget.json?apiKey={$apiKey}";
        $nutritionResponse = file_get_contents($nutritionUrl);

        if ($nutritionResponse == false) {
            throw new Exception("Errore nella richiesta dei nutrienti.");
        }

        $nutritionData = json_decode($nutritionResponse, true);

        // Prepara i nutrienti
        $nutrients = [];
        if (is_array($nutritionData) && isset($nutritionData['bad']) && isset($nutritionData['good'])) {
            $nutrients = array_merge($nutritionData['bad'], $nutritionData['good']);
        }

        $result = [
            'title' => $detailsData['title'] ?? 'Titolo non disponibile',
            'image' => $detailsData['image'] ?? 'Immagine non disponibile',
            'readyInMinutes' => $detailsData['readyInMinutes'] ?? 'Non disponibile',
            'servings' => $detailsData['servings'] ?? 'Non disponibile',
            'summary' => $detailsData['summary'] ?? 'Non disponibile',
            'sourceUrl' => $detailsData['sourceUrl'] ?? '#',
            'ingredients' => $ingredients,
            'nutrients' => $nutrients
        ];

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'results' => [$result]]);

    } catch (Exception $e) {
        // Gestione degli errori
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

?>