async function verificaCredenziali() {
    let email = document.querySelector('input[name="email"]').value;
    let password = document.querySelector('input[name="password"]').value;

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
      alert(datiRicevuti["msg"]);
    
    else if (datiRicevuti["status"]=="OK"){
      alert("Accesso avvenuto con successo!");
      window.location.href = "ingredienti.php";
    }

}