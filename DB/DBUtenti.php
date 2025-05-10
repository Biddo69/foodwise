<?php
    class DBUtenti {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        // Controlla se l'email e la password corrispondono a un utente
        public function verificaCredenziali($email, $passmd5) {
            $query = "SELECT * FROM utente WHERE email = ? AND passmd5 = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $email, $passmd5);
            $stmt->execute();
            return $stmt->get_result();
        }

        public function checkUtenteEsistente($username, $email) {
            $query = "SELECT id FROM utente WHERE username = ? OR email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            return $stmt->get_result();
        }

        // Inserisce un nuovo utente nel database
        public function registraUtente($username, $email, $passmd5, $dataNascita, $peso, $altezza, $sesso, $pesoGoal) {
            $query = "INSERT INTO utente (username, email, passmd5, dataNascita, peso, altezza, sesso, pesoGoal) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssdisd", $username, $email, $passmd5, $dataNascita, $peso, $altezza, $sesso, $pesoGoal);
            return $stmt->execute();
        }

    }
?>