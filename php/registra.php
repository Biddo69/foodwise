<?php
require_once("../includes/conn.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>FoodWise</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>
<script>

  async function verificaCredenziali() {
    let username = document.querySelector('input[name="username"]').value;
    let email = document.querySelector('input[name="email"]').value;
    let password = document.querySelector('input[name="password"]').value;
    let dataNascita = document.querySelector('input[name="dataNascita"]').value;
    let peso = document.querySelector('input[name="peso"]').value;
    let altezza = document.querySelector('input[name="altezza"]').value;
    let sesso = document.querySelector('input[name="sesso"]:checked').value;

    let url = "../ajax/gestoreRegistra.php?username=" + username + "&email=" + email + "&password=" + password + "&dataNascita=" + dataNascita + "&peso=" + peso + "&altezza=" + altezza + "&sesso=" + sesso;
    let response = await fetch(url);

    if (!response.ok){
        throw new Error("non sono riuscito a fare la fetch!");
    }

    let txt = await response.text();
    console.log(txt);
    //la converto in JSON
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);

    if (datiRicevuti["status"]=="ERR")
      alert(datiRicevuti["msg"]);
    
    else if (datiRicevuti["status"]=="OK"){
      alert("Registrazione avvenuta con successo!");
      window.location.href = "index.php";
    }

  }
</script>
<body>

  <h2>Registrati</h2>

  Username <input type="text" name="username"  required><br>
  E-mail <input type="email" name="email" required><br>
  Password <input type="password" name="password" required><br>
  Data di nascita <input type="date" name="dataNascita" required><br>
  Peso in kg <input type="number" name="peso" max="700" min="10"><br>
  Altezza in cm <input type="number" name="altezza" max="270" min="50" required><br>
  Sesso biologico: <br>
    <input type="radio" name="sesso" value="M" required> Maschio
    <input type="radio" name="sesso" value="F" required> Femmina<br>
  <button onclick="verificaCredenziali()">Invia</button>
    
</body>
</html>