
async function generaLista() {
    let url = "../ajax/trovaIngredientiInDB.php";
    let divMessaggio = document.getElementById("messaggio");
    divMessaggio.innerHTML = ""; // Svuota la lista precedente
    
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se il backend ha restituito un errore
        if (datiRicevuti.error) {
            divMessaggio.innerHTML = `<p class='errore'>Errore: ${datiRicevuti.error}</p>`;
            return;
        }

        // Se non ci sono ingredienti, mostra un messaggio
        if (datiRicevuti.length == 0) {
            divMessaggio.innerHTML = "<p class='errore'>Non hai ingredienti nella lista della spesa.</p>";
            return;
        }

        // Popola la lista della spesa
        let listaContainer = document.getElementById('listaSpesa');
        listaContainer.innerHTML = ""; // Svuota la lista precedente

        datiRicevuti.forEach(ingrediente => {
            let li = document.createElement('li');
            li.classList.add('lista-item'); // Aggiungi una classe per lo stile

            li.innerHTML = `
                <img src="${ingrediente.urlImmagine}" alt="${ingrediente.nome}" class="lista-img">
                <div class="lista-info">
                    <strong>${ingrediente.nome}</strong>
                </div>
                <button class="lista-btn" onclick="rimuoviDallaLista('${ingrediente.nome}')">Rimuovi</button>
            `;
            listaContainer.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante il recupero della lista della spesa.");
    }
}


async function aggiungiAllaLista(nomeIngrediente) {
    let url = `../ajax/aggiungiLista.php?nome=${encodeURIComponent(nomeIngrediente)}`;
    let divMessaggio = document.getElementById("messaggio");
    divMessaggio.innerHTML = ""; // Svuota la lista precedente

    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se l'operazione è andata a buon fine
        if (datiRicevuti.success) {
            divMessaggio.innerHTML = `<p class='successo'>${datiRicevuti.message}</p>`;
        } else {
            divMessaggio.innerHTML = `<p class='errore'>Errore: ${datiRicevuti.message}</p>`;
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante l'aggiunta alla lista della spesa.");
    }
}

// Funzione per rimuovere un ingrediente dalla lista della spesa
async function rimuoviDallaLista(nomeIngrediente) {
    let url = `../ajax/rimuoviLista.php?nome=${encodeURIComponent(nomeIngrediente)}`;
    let divMessaggio = document.getElementById("messaggio");
    divMessaggio.innerHTML = ""; // Svuota la lista precedente
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se l'operazione è andata a buon fine
        if (datiRicevuti.success) {
            divMessaggio.innerHTML = `<p class='successo'>${datiRicevuti.message}</p>`;
            generaLista(); // Aggiorna la lista dopo la rimozione
        } else {
            divMessaggio.innerHTML = `<p class='errore'>Errore: ${datiRicevuti.message}</p>`;
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la rimozione dell'ingrediente.");
    }
}