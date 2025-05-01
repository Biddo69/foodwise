<?php
    session_start();
    require_once("../includes/conn.php");

    //il try catch mi serve più che altro per la clausola finally, che chiude le connessioni e le risorse
    //se non ci sono errori, il finally viene eseguito comunque 
    try {

        //l'operatore ?? fa il controllo !isset e empty, se esiste il valore lo assegna altrimenti ritorna null
        $username = $_GET["username"] ?? null;
        $email = $_GET["email"] ?? null;
        $passmd5 = md5($_GET["password"] ?? null);
        $dataNascita = $_GET["dataNascita"] ?? null;
        $peso = $_GET["peso"] ?? null;
        $altezza = $_GET["altezza"] ?? null;
        $sesso = $_GET["sesso"] ?? null;

        // Validazione dei dati
        if (!$username || !$email || !$dataNascita || !$peso || !$altezza || !$sesso) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Tutti i campi sono obbligatori."
            ]);
            die();
        }

        // la funzione filter var praticamente semplifica i controlli Regex usando dei filtri preimpostati...
        //in questo caso uso quello per le email, che controlla 
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

        if ($stmt->num_rows > 0) {
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

        //calcolo l'età dell'utente usando il formato in anni, ma non tiene conto se il mese è gia passato...
        $eta = date("Y") - date("Y", $dataNascitaTimestamp);
        //controllo quindi se il mese e il giorno della nascita sono minori rispetto a quelli attuali, quindi tolgo un anno
        if (date("md", $dataNascitaTimestamp) > date("md")) {
            $eta--;
        }

        //controllo se l'eta è minore di 12 anni, che mi toglie i controlli per la data futura non valida
        if ($eta < 12) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'utente deve avere almeno 12 anni."
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

        // Calcolo il peso ideal utilizzando la formula di Lorenz
        if ($sesso == "M") {
            $pesoGoal = $altezza - 100 - (($altezza - 150) / 4);
        } 
        else if ($sesso == "F") {
            $pesoGoal = $altezza - 100 - (($altezza - 150) / 2.5);
        }

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