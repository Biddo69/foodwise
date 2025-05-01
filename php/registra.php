<?php 
    require_once("../includes/conn.php");
    require_once("../includes/header.php"); 
?>

  <h2>Registrati</h2>

  Username <input type="text" name="username"  required><br>
  E-mail <input type="email" name="email" required><br>
  Data di nascita <input type="date" name="data_nascita" required><br>
  Peso in kg <input type="number" name="password" max="700" min="10"><br>
  Altezza in cm <input type="password" name="password" max="270" min="50" required><br>
  Sesso biologico: <br>
    <input type="radio" name="sesso" value="M" required> Maschio
    <input type="radio" name="sesso" value="F" required> Femmina<br>
  <button onclick="verificaCredenziali()">Invia</button>
    
    

<?php 
    require_once("../includes/footer.php"); 
?>