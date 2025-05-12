<?php
    if(!isset($_SESSION)) {
        session_start();
    }

    require_once("../includes/conn.php");
    require_once("../DB/DBUtenti.php");

    try {
        $username = $_GET["username"] ?? null;
        $email = $_GET["email"] ?? null;
        $passmd5 = md5($_GET["password"] ?? null);
        $dataNascita = $_GET["dataNascita"] ?? null;
        $peso = $_GET["peso"] ?? null;
        $altezza = $_GET["altezza"] ?? null;
        $sesso = $_GET["sesso"] ?? null;

        // Validazione dei dati
        if (!$username || !$email || !$dataNascita || !$peso || !$altezza || !$sesso) {
            echo json_encode([ "status" => "ERR", "msg" => "Tutti i campi sono obbligatori." ]);
            die();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([ "status" => "ERR", "msg" => "L'indirizzo email non è valido." ]);
            die();
        }

        $dbUtenti = new DBUtenti($conn);

        // Controlla se username o email esistono già
        $result = $dbUtenti->checkUtenteEsistente($username, $email);
        if ($result->num_rows > 0) {
            echo json_encode([ "status" => "ERR", "msg" => "L'username o l'email sono già in uso." ]);
            die();
        }

        // Calcola l'età
        $dataCorrente = new DateTime();
        $dataNascitaObj = new DateTime($dataNascita);
        $eta = $dataCorrente->diff($dataNascitaObj)->y;

        if ($eta < 12 || $eta > 120) {
            echo json_encode([ "status" => "ERR", "msg" => "L'utente deve avere un'età compresa tra i 12 e i 120 anni." ]);
            die();
        }

        // Validazione del peso e dell'altezza
        if ($peso < 10 || $peso > 700) {
            echo json_encode([ "status" => "ERR", "msg" => "Il peso deve essere un numero valido." ]);
            die();
        }

        if ($altezza < 50 || $altezza > 270) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'altezza deve essere un numero valido."
            ]);
            die();
        }

        // Calcolo del peso ideale utilizzando la formula di Lorenz
        if ($sesso == "M") {
            $pesoGoal = $altezza - 100 - (($altezza - 150) / 4);
        } else if ($sesso == "F") {
            $pesoGoal = $altezza - 100 - (($altezza - 150) / 2.5);
        }

        // Registra l'utente
        if ($dbUtenti->registraUtente($username, $email, $passmd5, $dataNascita, $peso, $altezza, $sesso, $pesoGoal)) {
            echo json_encode([
                "status" => "OK",
                "msg" => "Registrazione completata con successo."
            ]);
        } else {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Errore durante la registrazione. Riprova più tardi."
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "ERR",
            "msg" => "Si è verificato un errore: " . $e->getMessage()
        ]);
    }
?>