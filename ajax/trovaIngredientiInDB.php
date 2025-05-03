<?php

    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("../includes/conn.php");

    // Controlla se l'utente è autenticato
    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    $userId = $_SESSION['userData']['id'];

    try {
        // Query per recuperare gli ingredienti associati alle liste della spesa dell'utente
        $stmt = $conn->prepare("
            SELECT 
                i.id AS idIngrediente,
                i.nome AS nomeIngrediente,
                i.immagine AS immagine
            FROM 
                ingredienteInLista il
            JOIN 
                listaSpesa ls ON il.idLista = ls.id
            JOIN 
                ingrediente i ON il.idIngrediente = i.id
            WHERE 
                ls.idUtente = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

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