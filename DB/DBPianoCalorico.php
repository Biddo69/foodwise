<?php
class DBPianoCalorico {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Recupera i dati del piano calorico per un utente, ordinati per data decrescente
    public function getPianoCaloricoPerUtente($idUtente) {
        $query = "SELECT * FROM pianoCalorico WHERE idUtente = ? ORDER BY data DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $idUtente);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>