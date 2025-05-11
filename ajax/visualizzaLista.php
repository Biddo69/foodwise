<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    require_once("../includes/conn.php");
    require_once("../DB/DBIngredienti.php");

    if (!isset($_SESSION['userData']['id'])) {
        echo json_encode(['error' => 'Utente non autenticato.']);
        exit;
    }

    $userId = $_SESSION['userData']['id'];

    try {
        $dbIngredienti = new DBIngredienti($conn);

        // Recupera gli elementi della lista della spesa
        $result = $dbIngredienti->visualizzaListaSpesa($userId);

        // Controlla se ci sono risultati
        if ($result->num_rows > 0) {
            $listaSpesa = [];
            while ($row = $result->fetch_assoc()) {
                $listaSpesa[] = [
                    'idLista' => $row['idLista'],
                    'idIngrediente' => $row['idIngrediente'],
                    'nomeIngrediente' => $row['nomeIngrediente'],
                    'immagine' => $row['immagine']
                ];
            }
            echo json_encode($listaSpesa);
        } else {
            echo json_encode([]); // Nessun elemento trovato
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
?>