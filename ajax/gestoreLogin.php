<?php
    // gestoreAccesso.php
    //AJAX per il login: riceve email e password via POST e restituisce JSON

    require_once("../includes/conn.php");

    session_start();

    // Recupera email e password dalla sessione
    if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
        $vettoreRitorno = [];
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Email o password non presenti";
        echo json_encode($vettoreRitorno);
        die();
    }
    elseif (empty($_POST['email']) || empty($_POST['password'])) {
        $vettoreRitorno = [];
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Email o password non presenti";
        echo json_encode($vettoreRitorno);
        die();
    }

    //questa riga controlla se l'email in input è valida tramite il filtro FILTER_VALIDATE_EMAIL, invece di fare un controllo manuale
    $email =  filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = md5($_POST['password']);

    if (!$email) {

        $vettoreRitorno = [];
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Email non valida";
        echo json_encode($vettoreRitorno);
        die();
    }



    $query = "SELECT email, passmd5 FROM utenti WHERE email = ? AND passmd5 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $passmd5);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Recupera i dati dell'utente
        $user = $result->fetch_assoc();

        // Controlla se la password md5 corrisponde
        if ($user['passmd5'] == $password) {
            // Login riuscito, imposta la sessione
            $_SESSION['email'] = $user['email'];

            $vettoreRitorno = [];
            $vettoreRitorno["status"] = "ok";
            $vettoreRitorno["msg"] = "Login effettuato con successo";
            echo json_encode($vettoreRitorno);
            $_SESSION["autenticato"] = true;
            die();
        }
    }

    $stmt->close();


    // Se arriva qui, login fallito
    $vettoreRitorno = [];
    $vettoreRitorno["status"] = "ERR";
    $vettoreRitorno["msg"] = "Si è verificato un errore durante il login";
    echo json_encode($vettoreRitorno);
    die();
?>
