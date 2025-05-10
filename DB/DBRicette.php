<?php
    class DBRicette {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        // Controlla se una ricetta esiste già nel database
        public function checkRicettaEsistente($nomeRicetta) {
            $query = "SELECT id FROM ricetta WHERE nome = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $nomeRicetta);
            $stmt->execute();
            return $stmt->get_result();
        }

        // Inserisce una nuova ricetta nel database
        public function inserisciRicetta($nome, $immagine, $porzioni, $tempoPreparazione, $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio) {
            $query = "INSERT INTO ricetta (nome, immagine, porzioni, tempoPreparazione, calorie, proteine, carboidrati, grassi, zuccheri, sodio) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssiiiiiiii", $nome, $immagine, $porzioni, $tempoPreparazione, $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio);
            $stmt->execute();
            return $stmt->insert_id; // Restituisce l'ID della ricetta appena inserita
        }

        // Controlla se una ricetta è già nei preferiti di un utente
        public function checkRicettaNeiPreferiti($idUtente, $idRicetta) {
            $query = "SELECT * FROM ricettePreferite WHERE idUtente = ? AND idRicetta = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $idUtente, $idRicetta);
            $stmt->execute();
            return $stmt->get_result();
        }

        // Aggiunge una ricetta ai preferiti di un utente
        public function aggiungiAiPreferiti($idUtente, $idRicetta) {
            $query = "INSERT INTO ricettePreferite (idUtente, idRicetta) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $idUtente, $idRicetta);
            return $stmt->execute();
        }

        public function checkPianoCalorico($data, $idUtente) {
            $query = "SELECT * FROM pianoCalorico WHERE data = ? AND idUtente = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $data, $idUtente);
            $stmt->execute();
            return $stmt->get_result();
        }
        public function aggiornaPianoCalorico($calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio, $data, $idUtente) {
            $query = "UPDATE pianoCalorico 
                    SET calorie = ?, proteine = ?, carboidrati = ?, grassi = ?, zuccheri = ?, sodio = ? 
                    WHERE data = ? AND idUtente = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ddddddsi", $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio, $data, $idUtente);
            return $stmt->execute();
        }

        // Inserisce una nuova riga nel piano calorico
        public function inserisciPianoCalorico($data, $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio, $idUtente) {
            $query = "INSERT INTO pianoCalorico (data, calorie, proteine, carboidrati, grassi, zuccheri, sodio, idUtente) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sddddddi", $data, $calorie, $proteine, $carboidrati, $grassi, $zuccheri, $sodio, $idUtente);
            return $stmt->execute();
        }

        public function getIdRicettaByNome($nomeRicetta) {
            $query = "SELECT id FROM ricetta WHERE nome = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $nomeRicetta);
            $stmt->execute();
            return $stmt->get_result();
        }

        // Rimuove una ricetta dai preferiti di un utente
        public function rimuoviRicettaDaiPreferiti($userId, $idRicetta) {
            $query = "DELETE FROM ricettePreferite WHERE idUtente = ? AND idRicetta = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $userId, $idRicetta);
            $stmt->execute();
            return $stmt->affected_rows; // Restituisce il numero di righe modificate
        }

        public function getRicettePreferite($userId) {
            $query = "
                SELECT r.* 
                FROM ricettePreferite rp
                JOIN ricetta r ON rp.idRicetta = r.id
                WHERE rp.idUtente = ?
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return $stmt->get_result();
        }
    }
?>