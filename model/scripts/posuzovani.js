/**
 * Created by pepe on 9. 12. 2016.
 */


function preparePosuzovani(idRec, nazevCl) {
    var form = document.forms["posForm"];
    var nazev = form["nazev"];
    var originalita = form["originalita"];
    var tema = form["tema"];
    var kvalita = form["kvalita"];
    var doporuceni = form["doporuceni"];
    var info = form["info"];
    var idRecenze = form["idRecenze"];

    nazev.value = nazevCl;        //nastavim do formulare hodnoty
    originalita.value = 1;
    tema.value = 1;
    kvalita.value = 1;
    doporuceni.value = 1;
    info.value = "";
    idRecenze.value = idRec;
}


function odevzdejRecenzi() {
    var form = document.forms["posForm"];
    var formData = new FormData(form);  //vytvorim data pro odeslani

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "posuzovani.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
    xhttp.send(formData);
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Recenze úspěšně odevzdána.");
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}