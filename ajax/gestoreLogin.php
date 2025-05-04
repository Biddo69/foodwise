<?php
    session_start();
    require_once("../includes/conn.php");


    try {
        //?? fai i controlli isset e empty, restituendo null nel caso non siano stati passati, molto figo
        $email = $_GET["email"] ?? null;
        $passmd5 = md5($_GET["password"] ?? null);

        // qui controllo se il risultato del ?? è null o meno
        if (!$email || !$passmd5) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Tutti i campi sono obbligatori."
            ]);
            die();
        }

        //la funzione filter fa quello che farebbero le regex, tipo, controlla se c'è il punto, la chiacciola e il resto
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "ERR",
                "msg" => "L'indirizzo email non è valido."
            ]);
            die();
        }

        //query per controllare se l'email e la password esistono nel database
        $query = "SELECT * FROM utente WHERE email = ? AND passmd5 = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $email, $passmd5);
        $stmt->execute();
        $result = $stmt->get_result();

        //se result->num_rows > 0, significa che è stata trovata una riga
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            //quando l'utente mi fa l'accesso mi calcolo il bmr con la formula di 
            $peso = $userData["peso"]; 
            $altezza = $userData["altezza"];
            $dataNascita = $userData["dataNascita"]; 
            $sesso = $userData["sesso"];

            // Calcola l'età a partire dalla data di nascita
            $dataCorrente = new DateTime();
            $dataNascitaObj = new DateTime($dataNascita);
            $eta = $dataCorrente->diff($dataNascitaObj)->y; // Calcola la differenza in anni

            
            if ($sesso === "M") {
                $bmr = 10 * $peso + 6.25 * $altezza - 5 * $eta + 5; // Formula per uomini
            } elseif ($sesso === "F") {
                $bmr = 10 * $peso + 6.25 * $altezza - 5 * $eta - 161; // Formula per donne
            }

            // mi salvo anche l'età e il bmr
            $userData["calorie_giornaliere"] = $bmr;
            $userData["eta"] = $eta;

            // Salva i dati nella sessione che poi verrà svuotata con la logout
            $_SESSION["userData"] = $userData;
            $_SESSION["autenticato"] = true;

            echo json_encode([
                "status" => "OK",
                "msg" => "Accesso completato con successo.",
                "calorie_giornaliere" => $bmr,
                "eta" => $eta
            ]);
            die();
        } else {
            echo json_encode([
                "status" => "ERR",
                "msg" => "Credenziali non valide."
            ]);
            die();
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "ERR",
            "msg" => "Si è verificato un errore: " . $e->getMessage()
        ]);
    } 
?>