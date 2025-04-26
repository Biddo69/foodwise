<?php
    // gestoreAccesso.php
    //AJAX per il login: riceve email e password via POST e restituisce JSON

    require_once("../includes/conn.php");

    session_start();

    // Controllo per username
    if (empty($_SESSION['username']) || empty($_SESSION['password']) || empty($_SESSION['eta']) || empty($_SESSION['sesso']) || empty($_SESSION['peso']) || empty($_SESSION['altezza'])) {
        $vettoreRitorno = [];
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Credenziali non valide.";
        echo json_encode($vettoreRitorno);
        die();
    }

    if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['eta']) || !isset($_SESSION['sesso']) || !isset($_SESSION['peso']) || !isset($_SESSION['altezza'])) {
        $vettoreRitorno = [];
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Credenziali non valide.";
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


    // Inserisce i dati nel database
    $query = "INSERT INTO utenti (username, passmd5, email, eta, sesso, peso, altezza) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssss",
        $email,
        $password,
        $_SESSION['username'],
        $_SESSION['eta'],
        $_SESSION['sesso'],
        $_SESSION['peso'],
        $_SESSION['altezza']
    );
    $vettoreRitorno = [];
    $vettoreRitorno["status"] = "OK";
    $vettoreRitorno["msg"] = "Registrazione avvenuta con successo";
    echo json_encode($vettoreRitorno);
    $stmt->close();
    die();
?>
