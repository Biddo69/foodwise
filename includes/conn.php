<?php

    $host = "localhost";
    $username = "root";  
    $password = "";      
    $database = "foodwise";

    $mysqli = new mysqli($host, $username, $password, $database);

    // Verifica della connessione
    if ($mysqli->connect_error) {
        die("Connessione fallita: " . $mysqli->connect_error);
    }


    $mysqli->set_charset("utf8");
?>