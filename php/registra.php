<?php
require_once("../includes/conn.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>FoodWise</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/registraScript.js"></script>
</head>
<body>
  <div class="header">
    <img src="../img/logo.png" alt="FoodWise Logo">
  </div>
  
  <div class="container">
    
    <div id="messaggio" class="messaggio"></div>
    <h2>Registrati</h2>
    
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required>
    
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <label for="dataNascita">Data di nascita</label>
    <input type="date" id="dataNascita" name="dataNascita" required>
    
    <label for="peso">Peso in kg</label>
    <input type="number" id="peso" name="peso" max="700" min="10">
    
    <label for="altezza">Altezza in cm</label>
    <input type="number" id="altezza" name="altezza" max="270" min="50" required>
    
    <label>Sesso biologico:</label>
    <div class="radio-group">
      <span class="radio-option">
        <label for="male">Maschio</label>
        <input type="radio" id="male" name="sesso" value="M" required>
      </span>
      <span class="radio-option">
        <label for="female">Femmina</label>
        <input type="radio" id="female" name="sesso" value="F" required>
      </span>
    </div>
    
    <button type="button" onclick="verificaCredenziali()">Invia</button>
  </div>
</body>
</html>