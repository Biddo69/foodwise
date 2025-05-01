<?php

    session_start();
    require_once("../includes/conn.php");
    require_once("../includes/header.php");
    if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != true) {
        header("Location: login.php?messaggio=Devi effettuare il login per accedere a questa pagina.");
        exit;
    }
?>

    <h1>Ricerca un ingrediente</h1>
    <input type="text" id="parametro" placeholder="Es. burro">
    <input type="image" src="../img/cerca.png" width="20" height="20" onclick="cercaIngredienti()">
    <!-- lista di massimo 5 risultati dei parametri di ricerca -->
    <ul id="risultati"></ul>

  <script>

    async function cercaIngredienti() {
    let parametro = document.getElementById('parametro').value.trim();
    if (!parametro) {
        alert("Inserisci un ingrediente da cercare.");
        return;
    }

    let url = "../ajax/trovaIngredienti.php?parametro=" + encodeURIComponent(parametro);

    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("non sono riuscito a fare la fetch!");
        }

        let datiRicevuti = await response.json();
        console.log(datiRicevuti); // Debug: verifica i dati ricevuti

        // Controlla se il backend ha restituito un errore
        if (datiRicevuti.error) {
            alert(datiRicevuti.error); // Mostra l'errore all'utente
            return;
        }

        let risultati = document.getElementById('risultati');
        risultati.innerHTML = ""; // Svuota la lista precedente

        datiRicevuti.forEach(ingrediente => {
            let li = document.createElement('li');
            li.innerHTML = `
                <div>
                    <img src="${ingrediente.urlImmagine}" alt="${ingrediente.nome}" style="width: 50px; height: 50px; margin-right: 10px;">
                    <div>
                        <strong>${ingrediente.nome}</strong><br>
                    </div>
                </div>
            `;
            risultati.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la ricerca degli ingredienti.");
    }
}

  </script>

<?php
    require_once("../includes/footer.php");
?>