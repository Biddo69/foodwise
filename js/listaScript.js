
async function generaLista() {
    let url = "../ajax/trovaIngredientiInDB.php";

    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se il backend ha restituito un errore
        if (datiRicevuti.error) {
            alert(datiRicevuti.error); // Mostra l'errore all'utente
            return;
        }

        // Se non ci sono ingredienti, mostra un messaggio
        if (datiRicevuti.length === 0) {
            alert("Nessun ingrediente trovato nella lista della spesa.");
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
    
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se l'operazione è andata a buon fine
        if (datiRicevuti.success) {
            alert(datiRicevuti.message); // Mostra il messaggio di successo
        } else {
            alert(`Errore: ${datiRicevuti.message}`); // Mostra il messaggio di errore
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante l'aggiunta alla lista della spesa.");
    }
}

// Funzione per rimuovere un ingrediente dalla lista della spesa
async function rimuoviDallaLista(nomeIngrediente) {
    let url = `../ajax/rimuoviLista.php?nome=${encodeURIComponent(nomeIngrediente)}`;
    
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se l'operazione è andata a buon fine
        if (datiRicevuti.success) {
            alert(datiRicevuti.message); // Mostra il messaggio di successo
            generaLista(); // Aggiorna la lista dopo la rimozione
        } else {
            alert(`Errore: ${datiRicevuti.message}`); // Mostra il messaggio di errore
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la rimozione dell'ingrediente.");
    }
}