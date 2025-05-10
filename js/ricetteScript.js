async function cercaRicette() {
    let parametro = document.getElementById('parametro').value.trim();
    if (!parametro) {
        document.getElementById("preferiti").innerHTML = "<li class='messaggio'>Inserisci una ricetta.</li>";  
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
                    <img src="${ricetta.urlImmagine}" alt="${ricetta.nome}" style="width: 90px; height: 90px;">
                    <div style="flex-grow: 1; margin-left: 15px;">
                        <strong>${ricetta.nome}</strong><br>
                    </div>
                    <button onclick="window.location.href='ricetteDettagli.php?nome=${encodeURIComponent(ricetta.nome)}'">Visualizza dettagli</button>
                    <button onclick="aggiungiAiPreferiti('${ricetta.nome}')">Aggiungi ai preferiti</button>
                    <button onclick="consumaRicetta('${ricetta.nome}')">Consuma ricetta</button>
                </div>
            `;
            risultati.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la ricerca della ricetta.");
    }
}

async function dettagliRicetta(nomeRicetta) {
    let url = `../ajax/ottieniDettagliRicetta.php?nome=${encodeURIComponent(nomeRicetta)}`;
    
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        // Leggi la risposta JSON dal server
        let datiRicevuti = await response.json();

        // Controlla se l'operazione è andata a buon fine
        if (datiRicevuti.success) {
            let ricetta = datiRicevuti.results[0]; // Assumendo che ci sia almeno una ricetta nei risultati

            if (!ricetta) {
                alert("Nessuna ricetta trovata.");
                return;
            }

            // Filtra i nutrienti che vuoi visualizzare
            const nutrientiDaMostrare = ["Calories", "Protein", "Fat", "Carbohydrates", "Sugar", "Sodium"]; // Nomi dei nutrienti da visualizzare
            const nutrientiFiltrati = ricetta.nutrients.filter(nutriente => 
                nutrientiDaMostrare.includes(nutriente.title)
            );

            // Controlla se gli ingredienti sono presenti
            // Controlla se gli ingredienti sono presenti
            if (!ricetta.ingredients || ricetta.ingredients.length === 0) {
                console.warn("Nessun ingrediente trovato per questa ricetta.");
                ricetta.ingredients = ["Ingredienti non disponibili"];
            }

            // Popola la pagina con i dettagli della ricetta
            let dettagliContainer = document.getElementById('dettagliRicetta');
            dettagliContainer.innerHTML = `
                <h2>${ricetta.title}</h2>
                <img src="${ricetta.image}" alt="${ricetta.title}" style="width: 100%; max-width: 400px;">
                <p><strong>Tempo di preparazione:</strong> ${ricetta.readyInMinutes} minuti</p>
                <p><strong>Porzioni:</strong> ${ricetta.servings}</p>
                <p><strong>Riassunto:</strong> ${ricetta.summary}</p>
                <h3>Ingredienti:</h3>
                <ul id="ingredienti-list"></ul>
                <h3>Valori Nutrizionali:</h3>
                <ul id="nutrienti-list"></ul>
                <p><strong>Fonte:</strong> <a href="${ricetta.sourceUrl}" target="_blank">Vai alla ricetta originale</a></p>
            `;

            // Aggiungi gli ingredienti alla lista
            let ingredientiList = document.getElementById('ingredienti-list');
            ricetta.ingredients.forEach(ingrediente => {
                let li = document.createElement('li');
                li.innerHTML = `
                    <div style="flex-grow: 1; margin-left: 15px;">
                        <strong>${ingrediente.nome}</strong><br>
                    </div>
                    <button onclick="aggiungiAllaLista('${ingrediente.nome}')">Aggiungi alla lista</button>
                `;
                ingredientiList.appendChild(li);
            });
        } else {
            alert(`Errore: ${datiRicevuti.error || "Impossibile recuperare i dettagli della ricetta."}`);
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante il recupero dei dettagli della ricetta.");
    }
}

async function aggiungiAiPreferiti(nomeRicetta) {
    let url = `../ajax/aggiungiPreferiti.php?nome=${encodeURIComponent(nomeRicetta)}`;
    
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        let datiRicevuti = await response.json();

        if (datiRicevuti.success) {
            alert(datiRicevuti.message); // Mostra il messaggio di successo
        } else {
            alert(`Errore: ${datiRicevuti.message}`); // Mostra il messaggio di errore
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante l'aggiunta ai preferiti.");
    }
}

async function visualizzaPreferiti() {
    
    let url = "../ajax/visualizzaPreferiti.php";

    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Errore durante la richiesta al server.");
        }

        let datiRicevuti = await response.json();

        if (datiRicevuti.length === 0) {
            document.getElementById("preferiti").innerHTML = "<li class='messaggio'>Nessuna ricetta preferita trovata.</li>";
            return;
        }

        let listaPreferiti = document.getElementById("preferiti");
        listaPreferiti.innerHTML = ""; // Svuota la lista precedente

        datiRicevuti.forEach(ricetta => {
            let li = document.createElement("li");
            li.classList.add("lista-item"); // Aggiungi una classe per lo stile

            li.innerHTML = `
                <div class="lista-contenitore">
                    <div class="lista-dettagli">
                        <img src="${ricetta.immagine}" alt="${ricetta.nome}" class="lista-img">
                        <div class="lista-info">
                            <strong>${ricetta.nome}</strong><br>
                            <small>Porzioni: ${ricetta.porzioni || "N/A"}</small><br>
                            <small>Tempo: ${ricetta.tempoPreparazione || "N/A"} min</small>
                        </div>
                    </div>
                    <div class="lista-azioni">
                        <button class="lista-btn" onclick="window.location.href='ricetteDettagli.php?nome=${encodeURIComponent(ricetta.nome)}'">Dettagli</button>
                        <button class="lista-btn rimuovi-btn" onclick="rimuoviDaiPreferiti('${ricetta.nome}')">Rimuovi</button>
                    </div>
                </div>
            `;
            listaPreferiti.appendChild(li);
        });
    } catch (error) {
        console.error("Errore:", error);
        document.getElementById("preferiti").innerHTML = "<li class='messaggio'>Errore durante il caricamento delle ricette preferite.</li>";
    }
}

async function rimuoviDaiPreferiti(nomeRicetta) {
    let url =`../ajax/rimuoviPreferiti.php?nome=${encodeURIComponent(nomeRicetta)}`;

    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Errore durante la richiesta al server.");
        }

        let datiRicevuti = await response.json();

        if (datiRicevuti.success) {
            alert(datiRicevuti.message);
            visualizzaPreferiti(); // Aggiorna la lista dopo la rimozione
        } else {
            alert(datiRicevuti.error || "Errore durante la rimozione.");
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la rimozione della ricetta.");
    }
}

async function consumaRicetta(nomeRicetta) {
    let porzioni = prompt("Quante porzioni hai consumato?", "1");
    
    if (!porzioni || isNaN(porzioni) || porzioni <= 0) {
        alert("Inserisci un numero valido di porzioni.");
        return;
    }

    let url = `../ajax/consumaRicetta.php?nome=${encodeURIComponent(nomeRicetta)}&porzioni=${encodeURIComponent(porzioni)}`;
    
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error("Non sono riuscito a fare la fetch!");
        }

        let datiRicevuti = await response.json();

        if (datiRicevuti.success) {
            alert(datiRicevuti.message); // Mostra il messaggio di successo
        } else {
            alert(`Errore: ${datiRicevuti.message}`); // Mostra il messaggio di errore
        }
    } catch (error) {
        console.error("Errore:", error);
        alert("Si è verificato un errore durante la registrazione del consumo della ricetta.");
    }
}