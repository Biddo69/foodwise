<?php
    if (!isset($_SESSION))
        session_start();

    require_once("conn.php");

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
</head>
<body>
    
    <div class="header">
    <img src="../img/logo.png" alt="FoodWise Logo">
      <!-- Barra di navigazione superiore con link alle diverse sezioni -->
      <nav class="navbar">
        <a href="ingredienti.php">Ingredienti</a>
        <a href="listaSpesa.php">Lista Spesa</a>
        <a href="ricette.php">Ricette</a>
        <a href="ricettePreferite.php">Preferiti</a>
        <a href="pianoCalorico.php">Piano Calorico</a>
        <a href="logout.php" class="logout">Logout</a>
      </nav>
      <!-- Contenuto principale della pagina; qui viene inserito il markup specifico di ogni sezione -->
      <main class="content">
    </div> <br><br><br>