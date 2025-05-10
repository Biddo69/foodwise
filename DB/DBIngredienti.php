<?php
class DBIngredienti {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Controlla se un ingrediente esiste già nel database
    public function checkIngredienteEsistente($nomeIngrediente) {
        $query = "SELECT id FROM ingrediente WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $nomeIngrediente);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Inserisce un nuovo ingrediente nel database
    public function inserisciIngrediente($idIngrediente, $nome, $immagine) {
        $query = "INSERT INTO ingrediente (id, nome, immagine) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $idIngrediente, $nome, $immagine);
        return $stmt->execute();
    }

    // Controlla se una lista della spesa esiste già per un utente
    public function checkListaEsistente($idUtente) {
        $query = "SELECT id FROM listaSpesa WHERE idUtente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $idUtente);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Crea una nuova lista della spesa
    public function creaLista($nomeLista, $idUtente) {
        $query = "INSERT INTO listaSpesa (nome, idUtente) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $nomeLista, $idUtente);
        return $stmt->execute();
    }

    // Controlla se un ingrediente è già presente in una lista
    public function checkIngredienteInLista($idLista, $idIngrediente) {
        $query = "SELECT idLista FROM ingredienteinlista WHERE idLista = ? AND idIngrediente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $idLista, $idIngrediente);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Aggiunge un ingrediente a una lista
    public function aggiungiIngredienteALista($idLista, $idIngrediente) {
        $query = "INSERT INTO ingredienteinlista (idLista, idIngrediente) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $idLista, $idIngrediente);
        return $stmt->execute();
    }

    public function rimuoviIngredienteDaLista($userId, $nomeIngrediente) {
        $query = "
            DELETE il
            FROM ingredienteInLista il
            JOIN listaSpesa ls ON il.idLista = ls.id
            JOIN ingrediente i ON il.idIngrediente = i.id
            WHERE ls.idUtente = ? AND i.nome = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $userId, $nomeIngrediente);
        $stmt->execute();
        return $stmt->affected_rows; // Restituisce il numero di righe modificate
    }

    public function trovaIngredientiPerUtente($userId) {
        $query = "
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
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function visualizzaListaSpesa($userId) {
        $query = " SELECT 
                    ls.id AS idLista,
                    i.id AS idIngrediente,
                    i.nome AS nomeIngrediente,
                    i.immagine AS immagine
                FROM ingredienteInLista il
                JOIN listaSpesa ls ON il.idLista = ls.id
                JOIN ingrediente i ON il.idIngrediente = i.id
                WHERE ls.idUtente = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>