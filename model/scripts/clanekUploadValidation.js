/**
 * Created by pepe on 4. 12. 2016.
 */
/*validace registracniho formulare*/


function tryToUploadClanek() {

    var form = document.forms["clanekUploadForm"];
    var formData = new FormData(form);  //vytvorim data pro odeslani


    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "uploadClanek.php", false);  //synchronne odeslu data formulare a dostanu odpoved resp
    xhttp.send(formData);
    var resp = xhttp.responseText;


    if(resp == "ok") {
        return true;
    }
    else {
        alert(resp);
        return false;
    }

}

function deleteClanek(id) {

    alert("delete> " + id);

}
function editClanek(id) {

    alert("edit> " + id);

}
function openPdfClanek(id) {

    alert("open> " + id);

}
