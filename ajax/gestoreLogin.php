<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    require_once("../includes/conn.php");
    require_once("../DB/DBUtenti.php");

    try {
        $email = $_GET["email"] ?? null;
        $passmd5 = md5($_GET["password"] ?? null);

        // Controllo se i campi sono stati forniti
        if (!$email || !$passmd5) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Tutti i campi sono obbligatori."
            ]);
            die();
        }

        // Validazione dell'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'indirizzo email non è valido."
            ]);
            die();
        }

        $dbUtenti = new DBUtenti($conn);

        // Verifica le credenziali
        $result = $dbUtenti->verificaCredenziali($email, $passmd5);

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // Calcola il BMR e l'età
            $peso = $userData["peso"];
            $altezza = $userData["altezza"];
            $dataNascita = $userData["dataNascita"];
            $sesso = $userData["sesso"];

            $dataCorrente = new DateTime();
            $dataNascitaObj = new DateTime($dataNascita);
            $eta = $dataCorrente->diff($dataNascitaObj)->y;

            if ($sesso == "M") {
                $bmr = 10 * $peso + 6.25 * $altezza - 5 * $eta + 5; // Formula per uomini
            } 
            else if ($sesso == "F") {
                $bmr = 10 * $peso + 6.25 * $altezza - 5 * $eta - 161; // Formula per donne
            }

            // Salva i dati nella sessione
            $userData["calorie_giornaliere"] = $bmr;
            $userData["eta"] = $eta;

            $_SESSION["userData"] = $userData;
            $_SESSION["autenticato"] = true;

            echo json_encode([ "status" => "OK", "msg" => "Accesso completato con successo.",]);
            die();
        } 
        else {
            echo json_encode([ "status" => "ERR", "msg" => "Credenziali non valide." ]);
            die();
        }
    } 
    catch (Exception $e) {
        echo json_encode([ "status" => "ERR", "msg" => "Si è verificato un errore: " . $e->getMessage() ]);
    }
?>