async function cercaIngredienti() {
    let parametro = document.getElementById('parametro').value.trim();
    let divMessaggio = document.getElementById("messaggio")
    divMessaggio.innerHTML = "";
    
    if (!parametro) {
        divMessaggio.innerHTML = "<p class='errore'>Inserisci un ingrediente da cercare</p>";
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
                <img src="${ingrediente.urlImmagine}" alt="${ingrediente.nome}" class="lista-img">
                <div class="lista-info">
                    <strong>${ingrediente.nome}</strong>
                </div>
                <button class="lista-btn" onclick="aggiungiAllaLista('${ingrediente.nome}')">Aggiungi alla lista della spesa</button>
            `;
            risultati.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        divMessaggio.innerHTML = "<p class='errore'>Si è verificato un errore durante la ricerca degli ingredienti.</p>";
    }
}