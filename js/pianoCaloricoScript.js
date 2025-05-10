async function visualizzaPianoCalorico() {
    const url = "../ajax/visualizzaPianoCalorico.php";
    let divMessaggio = document.getElementById("messaggio");
    divMessaggio.innerHTML = ""; 
    try {
        // Effettua la chiamata AJAX
        const response = await fetch(url);

        // Controlla se la risposta è valida
        if (!response.ok) {
            throw new Error("Errore durante la richiesta al server.");
        }

        // Decodifica i dati JSON restituiti
        const dati = await response.json();

        // Controlla se la risposta contiene un errore
        if (!dati.success) {
            divMessaggio.innerHTML = `<p class='errore'>${dati.message}</p>`; // Mostra l'errore all'utente
            return;
        }

        // Recupera il contenitore per visualizzare i dati
        const contenitore = document.getElementById("pianoCalorico");
        contenitore.innerHTML = ""; // Svuota il contenitore

        // Itera sui dati e crea gli elementi HTML
        dati.data.forEach(piano => {
            const div = document.createElement("div");
            div.classList.add("lista-item"); // Applica la classe CSS per lo stile

            // Aggiungi il contenuto HTML con le classi appropriate
            div.innerHTML = `
                <div class="lista-info">
                    <h3>Data: ${piano.data}</h3>
                    <p><strong>Calorie:</strong> ${piano.calorie} / ${dati.bmr} Kcal</p>
                    <p><strong>Proteine:</strong> ${piano.proteine}g</p>
                    <p><strong>Carboidrati:</strong> ${piano.carboidrati}g</p>
                    <p><strong>Grassi:</strong> ${piano.grassi}g</p>
                    <p><strong>Zuccheri:</strong> ${piano.zuccheri}g</p>
                    <p><strong>Sodio:</strong> ${piano.sodio}g</p>
                </div>
            `;

            contenitore.appendChild(div);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante il recupero del piano calorico.");
    }
}
