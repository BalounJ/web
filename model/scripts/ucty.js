
//zmeni prava
function zmenitPrava(login) {
    var sel = document.getElementById("prava_"+login);
    var prava = sel.value;

    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ucty.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login="+login+"&prava="+prava+"&action=zmenitPrava");
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Práva změněna.");
        location.reload();
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}

function odblokovat(login) {
    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ucty.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login="+login+"&action=odblokovat");
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Uživatel odblokován.");
        location.reload();
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}
function blokovat(login) {
    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ucty.php", false);  //synchronne odeslu data formulare a dostanu odpoved od php scriptu ze srvru
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login="+login+"&action=blokovat");
    var resp = xhttp.responseText;

    if(resp == "ok") {
        alert("Uživatel zablokován.");
        location.reload();
        return true;
    }
    else {
        alert(resp);
        return false;
    }
}
