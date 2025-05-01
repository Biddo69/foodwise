<?php

    $host = "localhost";
    $username = "root";  
    $password = "";      
    $database = "foodwise";

    $conn = new mysqli($host, $username, $password, $database);

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }


    $conn->set_charset("utf8");
?>