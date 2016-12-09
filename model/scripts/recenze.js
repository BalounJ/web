
//pripravi formular pro pridani recenze
function prepareForRec(idClanku) {
    var form = document.forms["recenzeAddForm"];
    var nazev = form["nazev"];
    var autori = form["autori"];
    var abstract = form["abstract"];
    var recenzent = form["recenzent"];
    var idPrispevku = form["idPrispevku"];

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "recenze.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("idPrispevku="+idClanku+"&action=prepareForRec");
    var resp = JSON.parse(xhttp.responseText);

    nazev.value = resp["nazev"];        //nastavim do formulare ziskane hodnoty
    autori.value = resp["autori"];
    abstract.value = resp["abstract"];
    idPrispevku.value = idClanku;

    var rec = resp["recenzenti"];

    for(var i = 0; i < rec.length; i++) {
        var opt = document.createElement('option');
        opt.innerHTML = rec[i]["jmeno"] + " " + rec[i]["prijmeni"];
        opt.value = rec[i]["login"];
        recenzent.appendChild(opt);
    }

}

//prida recenzi a vrati info
function tryToAddRec() {
    var form = document.forms["recenzeAddForm"];
    var formData = new FormData(form);  //vytvorim data pro odeslani

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "recenze.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
    xhttp.send(formData);
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Recenze přidělena úspěšně.");
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}

//smaze rec. a vrati info
function deleteRec(idRec) {
    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "recenze.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("idRecenze="+idRec+"&action=deleteRec");
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Recenze byla úspěšně smazána.");
        location.reload();
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}

//prijme clanek
function prijmout(idClanku) {
    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "clanekOper.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+idClanku+"&action=prijmout");
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Článek byl přijat.");
        location.reload();
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}
//odmitne clanek
function odmitnout(idClanku) {
    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "clanekOper.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+idClanku+"&action=odmitnout");
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Článek byl odmítnut.");
        location.reload();
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}

