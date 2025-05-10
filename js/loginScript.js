async function verificaCredenziali() {
  let divMessaggio = document.getElementById("messaggio");
  divMessaggio.innerHTML = ""; 

  let email = document.querySelector('input[name="email"]').value;
  let password = document.querySelector('input[name="password"]').value;

  //mando le credeziali alla pagina AJAX
  let url = "../ajax/gestoreLogin.php?email=" + email + "&password=" + password;
  let response = await fetch(url);

  if (!response.ok){
      throw new Error("non sono riuscito a fare la fetch!");
  }

  let txt = await response.text();
  console.log(txt);
  //la converto in JSON
  let datiRicevuti = JSON.parse(txt);
  console.log(datiRicevuti);

  if (datiRicevuti["status"]=="ERR")
    divMessaggio.innerHTML = `<p class='errore'>${datiRicevuti["msg"]}</p>`; // Mostra l'errore all'utente
  
  else if (datiRicevuti["status"]=="OK"){
    window.location.href = "ingredienti.php";
  }
}