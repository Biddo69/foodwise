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
            throw new Error("Non sono riuscito a fare la fetch!");
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
                <div style="display: flex; align-items: center;">
                    <img src="${ingrediente.urlImmagine}" alt="${ingrediente.nome}" style="width: 50px; height: 50px;">
                    <div style="flex-grow: 1; margin-left: 15px;">
                        <strong>${ingrediente.nome}</strong><br>
                    </div>
                    <button onclick="aggiungiAllaLista('${ingrediente.nome}')">Aggiungi alla lista della spesa</button>
                </div>
            `;
            risultati.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la ricerca degli ingredienti.");
    }
}