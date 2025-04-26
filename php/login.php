
<?php 
    require_once("../includes/conn.php"); 
?>

    <h1>Accedi</h1>
    
    Email <input type="email" name="email" required>
    Password <input type="password" name="password" required>
    <button onclick="controllaCredenziali()">Login</button>

    <p>Non hai un account? <a href="registra.php">Registrati</a></p>

<?php 
    require_once("../includes/footer.php"); 
?>

<script>
    async function controllaCredenziali() {
        let email = document.querySelector('input[name="email"]').value;
        let password = document.querySelector('input[name="password"]').value;
        // Save email and password in session storage
        sessionStorage.setItem('email', email);
        sessionStorage.setItem('password', password);

        try {
            let url = "../ajax/gestoreLogin.php";
            response = await fetch(url);

            if (!response.ok) {
                throw new Error("Errore HTTP: " + response.status);
            }
            let txt = await response.text(); // NON USARE JSON
            console.log(txt);
            let data = JSON.parse(txt);
            console.log(data);
            window.location.href = "home.php";
            
        } catch (error) {
            console.error('Errore durante il login:', error);
            alert('Si è verificato un errore. Riprova più tardi.');
        }
    }
</script>