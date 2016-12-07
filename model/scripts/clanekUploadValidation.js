/**
 * Created by pepe on 4. 12. 2016.
 */
/*validace registracniho formulare*/

function tryToUploadClanek() {
    var form = document.forms["clanekUploadForm"];
    var formData = new FormData(form);  //vytvorim data pro odeslani

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "clanekOper.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
    xhttp.send(formData);
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Článek přidán úspěšně.");
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}

function tryToEditClanek() {
    var form = document.forms["clanekEditForm"];
    var formData = new FormData(form);  //vytvorim data pro odeslani

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "clanekOper.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
    xhttp.send(formData);
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Článek editován úspěšně.");
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}

function prepareForEdit(idClanku) {
    var form = document.forms["clanekEditForm"];
    var nazev = form["nazev"];
    var autori = form["autori"];
    var abstract = form["abstract"];
   // var pdf = form["pdf"];
    var id = form["id"];

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "clanekOper.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+idClanku+"&action=prepare");
    var clanek = JSON.parse(xhttp.responseText);

    nazev.value = clanek["nazev"];
    autori.value = clanek["autori"];
    abstract.value = clanek["abstract"];
    id.value = idClanku;
}

function deleteClanek(idClanku) {
    var really = confirm("Opravdu si přejete smazat článek s id "+idClanku+"?");
    if(really) {
        xhttp = new XMLHttpRequest();
        xhttp.open("POST", "clanekOper.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("id="+idClanku+"&action=delete");
        var resp = xhttp.responseText;

        if(resp == "ok") {
            alert("Článek smazán úspěšně.");
            location.reload();
        }
        else {
            alert(resp);
        }
    }
}



