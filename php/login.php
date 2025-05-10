<?php
    require_once("../includes/conn.php");

    if (isset($_GET["messaggio"])) {
        echo "<p class='messaggio'>" . htmlspecialchars($_GET["messaggio"]) . "</p>";
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>FoodWise - Login</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/loginScript.js"></script>
</head>
<body>
    <div class="header">
        <img src="../img/logo.png" alt="FoodWise Logo">
    </div>
    <div id="messaggio" class="messaggio"></div>
    <div class="container">
        <h2>Accedi</h2>
        
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button onclick="verificaCredenziali()">Invia</button>    
    </div>

    <div class="link-container">
        <a href="registra.php">Non hai un account? Registrati</a>
    </div>
</body>
</html>