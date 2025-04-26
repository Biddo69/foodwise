<?php 
    require_once("../includes/conn.php"); 
?>

<h1>Registrazione</h1>
<form action="gestoreAccesso" id="signin" method="post">

    Nome utente <input type="text" name="username" required>
    Email <input type="email" name="email" required>
    Password <input type="password" name="password" required>

    Età <input type="number" name="age" required>

    Sesso biologico: 
    <input type="radio" name="sesso" value="M" required> Maschio
    <input type="radio" name="sesso" value="F" required> Femmina
    <button type="submit">Registrati</button>

    Peso <input type="number" name="peso" required>
    Altezza <input type="number" name="altezza" required>

</form>
<p>Già registrato? <a href="login.php">Accedi</a></p>

<?php 
    require_once("../footer/conn.php"); 
?>