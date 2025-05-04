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

        $peso = $userData["peso"]; // Peso in kg
        $altezza = $userData["altezza"]; // Altezza in cm
        $dataNascita = $userData["dataNascita"]; // Data di nascita in formato YYYY-MM-DD
        $sesso = $userData["sesso"]; // "M" per uomo, "F" per donna

        // Calcola l'età a partire dalla data di nascita
        $dataCorrente = new DateTime();
        $dataNascitaObj = new DateTime($dataNascita);
        $eta = $dataCorrente->diff($dataNascitaObj)->y; // Calcola la differenza in anni

        // Calcolo delle calorie giornaliere (BMR)
        if ($sesso === "M") {
            $bmr = 10 * $peso + 6.25 * $altezza - 5 * $eta + 5; // Formula per uomini
        } elseif ($sesso === "F") {
            $bmr = 10 * $peso + 6.25 * $altezza - 5 * $eta - 161; // Formula per donne
        } else {
            $bmr = null; // Caso in cui il sesso non sia specificato
        }

        // Aggiungi il BMR e l'età a userData
        $userData["calorie_giornaliere"] = $bmr;
        $userData["eta"] = $eta;

        // Salva i dati nella sessione
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
} finally {
    // Chiusura delle risorse
    if (isset($stmt) && $stmt !== false) {
        $stmt->close();
    }
    if (isset($conn) && $conn !== false) {
        $conn->close();
    }
}
?>