<?php
    require_once("../includes/conn.php");

    if (isset($_GET["messaggio"])) {
        echo $_GET["messaggio"];
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>FoodWise</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/loginScript.js"></script>
</head>
<body>
    <img src="../img/logo.png"><br>
    <div class="container">
    <h2>Accedi</h2>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button onclick="verificaCredenziali()">Invia</button>    
    </div>
    
</body>
</html>