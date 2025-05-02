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
                <div style="display: flex; align-items: center;">
                    <img src="${ingrediente.urlImmagine}" alt="${ingrediente.nome}" style="width: 50px; height: 50px;">
                    <div style="flex-grow: 1; margin-left: 15px;">
                        <strong>${ingrediente.nome}</strong><br>
                    </div>
                    <button onclick="aggiungiAllaLista('${ingrediente.nome}')">Aggiungi alla lista della spesa</button>
                    <button onclick="consumaIngrediente('${ingrediente.nome}')">Consuma questo ingrediente</button>
                </div>
            `;
            risultati.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la ricerca degli ingredienti.");
    }
}

// Funzione per reindirizzare alla pagina con il nome del prodotto
async function aggiungiAllaLista(nomeProdotto) {
    //passo all'ajax il nome del prodotto che verrà aggiunto alla lista della spesa
    const url = `aggiugniAllaLista.php?nome=${encodeURIComponent(nomeProdotto)}`;
    
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
function consumaIngrediente(nomeProdotto) {

    const quantita = prompt(`Inserisci la quantità consumata in grammi:`);
    
    // Controlla se l'utente ha inserito un valore
    if (quantita == null || quantita.trim() == "") {
        alert("Quantità non inserita. Operazione annullata.");
        return;
    }

    // Controlla che il valore sia un numero e maggiore di 0
    //isNaN verifica se il valore è o meno un numero
    // Number(quantita) converte il valore in un numero
    if (isNaN(quantita) || Number(quantita) <= 0) {
        alert("Inserisci un numero valido maggiore di 0.");
        return;
    }

    const url = `paginaDestinazione.php?&nome=${encodeURIComponent(nomeProdotto)}`;
    window.location.href = url;
}