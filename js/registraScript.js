async function verificaCredenziali() {
  let divMessaggio = document.getElementById("messaggio")
  divMessaggio.innerHTML = "";
  
  let username = document.querySelector('input[name="username"]').value;
  let email = document.querySelector('input[name="email"]').value;
  let password = document.querySelector('input[name="password"]').value;
  let dataNascita = document.querySelector('input[name="dataNascita"]').value;
  let peso = document.querySelector('input[name="peso"]').value;
  let altezza = document.querySelector('input[name="altezza"]').value;
  let sesso = document.querySelector('input[name="sesso"]:checked').value;

  let url = "../ajax/gestoreRegistra.php?username=" + username + "&email=" + email + "&password=" + password + "&dataNascita=" + dataNascita + "&peso=" + peso + "&altezza=" + altezza + "&sesso=" + sesso;
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
    divMessaggio.innerHTML = `<p class='errore'>${datiRicevuti["msg"]}</p>`;

  else if (datiRicevuti["status"]=="OK"){
    window.location.href = "index.php";
  }

}