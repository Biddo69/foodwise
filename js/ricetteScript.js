async function cercaRicette() {
    let parametro = document.getElementById('parametro').value.trim();
    if (!parametro) {
        alert("Inserisci una ricetta da cercare.");
        return;
    }

    let url = "../ajax/trovaRicette.php?parametro=" + encodeURIComponent(parametro);

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

        datiRicevuti.forEach(ricetta => {
            let li = document.createElement('li');
            li.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <img src="${ricetta.urlImmagine}" alt="${ricetta.nome}" style="width: 50px; height: 50px;">
                    <div style="flex-grow: 1; margin-left: 15px;">
                        <strong>${ricetta.nome}</strong><br>
                    </div>
                    <button onclick="dettagliRicetta('${ricetta.nome}')">Visualizza dettagli</button>
                    <button onclick="aggiungiAiPreferiti('${ricetta.nome}')">Aggiungi ai preferiti</button>
                </div>
            `;
            risultati.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la ricerca deglla ricetta.");
    }
}

// Funzione per reindirizzare alla pagina con il nome del prodotto
async function dettagliRicetta(nomeRicetta) {

    let url = `../ajax/ottieniDettagliRicetta.php?&nome=${encodeURIComponent(nomeRicetta)}`;
    
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

// Funzione per reindirizzare alla pagina con il nome del prodotto
async function aggiungiAiPreferiti(nomeRicetta) {
    //passo all'ajax il nome del prodotto che verrà aggiunto alla lista della spesa
    let url = `../ajax/aggiungiLista.php?nome=${encodeURIComponent(nomeRicetta)}`;
    
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
