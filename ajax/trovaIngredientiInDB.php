<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../includes/conn.php");
require_once("../DB/DBIngredienti.php");

if (!isset($_SESSION['userData']['id'])) {
    echo json_encode(['error' => 'Utente non autenticato.']);
    exit;
}

$userId = $_SESSION['userData']['id'];

try {
    $dbIngredienti = new DBIngredienti($conn);

    // Recupera gli ingredienti associati alle liste della spesa dell'utente
    $result = $dbIngredienti->trovaIngredientiPerUtente($userId);

    // Controlla se ci sono risultati
    if ($result->num_rows > 0) {
        $ingredients = [];
        while ($row = $result->fetch_assoc()) {
            $ingredients[] = [
                'nome' => $row['nomeIngrediente'],
                'urlImmagine' => $row['immagine'],
                'id' => $row['idIngrediente']
            ];
        }
        echo json_encode($ingredients);
    } else {
        echo json_encode([]); // Nessun ingrediente trovato
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>