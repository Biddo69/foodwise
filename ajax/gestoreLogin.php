<?php
    session_start();
    require_once("../includes/conn.php");

    
    try {
        $email = $_GET["email"] ?? null;
        $passmd5 = md5($_GET["password"] ?? null);

        // Validazione dei dati
        if (!$email || !$passmd5) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Tutti i campi sono obbligatori."
            ]);
            die();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'indirizzo email non è valido."
            ]);
            die();
        }

        $query = "SELECT * FROM utente WHERE email = ? AND passmd5 = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $email, $passmd5);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // $_SESSION["nome"] = $userData["nome"]; è come andrò a prendere i dati da userdata
            $_SESSION["userData"] = $userData;
            $_SESSION["autenticato"] = true;
            
            echo json_encode([
                "status" => "OK",
                "msg" => "Accesso completato con successo."
            ]);
            die();

        } 
        else {
            echo json_encode([
            "status" => "ERR",
            "msg" => "Credenziali non valide."
            ]);
            die();
        }

    } 
    
    catch (Exception $e) {
        echo json_encode([
            "status" => "ERR",
            "msg" => "Si è verificato un errore: " . $e->getMessage()
        ]);
    } 
    
    finally {
        // Chiusura delle risorse
        if (isset($stmt) && $stmt !== false) {
            $stmt->close();
        }
        if (isset($conn) && $conn !== false) {
            $conn->close();
        }
    }
?>