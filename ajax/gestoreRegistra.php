<?php
    session_start();
    require_once("../includes/conn.php");

    try {

        $username = $_GET["username"] ?? null;
        $email = $_GET["email"] ?? null;
        $passmd5 = md5($_GET["password"] ?? null);
        $dataNascita = $_GET["dataNascita"] ?? null;
        $peso = $_GET["peso"] ?? null;
        $altezza = $_GET["altezza"] ?? null;
        $sesso = $_GET["sesso"] ?? null;


        if (!$username || !$email || !$dataNascita || !$peso || !$altezza || !$sesso) {
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

        //controllo se username e email esistono gia nel database
        $query = "SELECT id FROM utente WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'username o l'email sono già in uso."
            ]);
            $stmt->close();
            die();
        }
        $stmt->close();


        // strtotime() converte una data in timestamp, che rappresenta il numero di secondi trascorsi dal 1 gennaio 1970
        $dataNascitaTimestamp = strtotime($dataNascita);


        // Calcola l'età a partire dalla data di nascita, che in realtà mi serve solo per calcolare il peso ideal 
        $dataCorrente = new DateTime();
        $dataNascitaObj = new DateTime($dataNascita);
        $eta = $dataCorrente->diff($dataNascitaObj)->y;

        // Controllo se l'età è valida
        if ($eta < 12 || $eta > 120) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'utente deve avere un'età compresa tra i 12 e i 120 anni."
            ]);
            die();
        }

        // Controllo se il peso è un numero valido e maggiore di 0
        if ($peso < 10 || $peso > 700) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Il peso deve essere un numero valido."
            ]);
            die();
        }

        // Controllo se l'altezza è un numero valido e maggiore di 0
        if ($altezza < 50 || $altezza > 270) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'altezza deve essere un numero valido."
            ]);
            die();
        }

        // Calcolo il peso ideale utilizzando la formula di Lorenz
        if ($sesso == "M") {
            $pesoGoal = $altezza - 100 - (($altezza - 150) / 4);
        } 
        else if ($sesso == "F") {
            $pesoGoal = $altezza - 100 - (($altezza - 150) / 2.5);
        }

        //dopo tutti i controlli faccio la query per inserire i dati nel database
        $query = "INSERT INTO utente (username, email, passmd5, dataNascita, peso, altezza, sesso, pesoGoal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssdisd", $username, $email, $passmd5, $dataNascita, $peso, $altezza, $sesso, $pesoGoal);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "OK",
                "msg" => "Registrazione completata con successo."
            ]);
        } 
        else {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Errore durante la registrazione. Riprova più tardi."
            ]);
        }

    } 
    
    catch (Exception $e) {
        echo json_encode([
            "status" => "ERR",
            "msg" => "Si è verificato un errore: " . $e->getMessage()
        ]);
    } 
?>