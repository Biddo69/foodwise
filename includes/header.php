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
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
  <!-- Barra di navigazione superiore con link alle diverse sezioni -->
  <nav class="navbar">
    <a href="ingredients.php">Ingredienti</a>
    <a href="shopping_list.php">Lista Spesa</a>
    <a href="recipes.php">Ricette</a>
    <a href="favorites.php">Preferiti</a>
    <a href="logout.php" class="logout">Logout</a>
  </nav>
  <!-- Contenuto principale della pagina; qui viene inserito il markup specifico di ogni sezione -->
  <main class="content">