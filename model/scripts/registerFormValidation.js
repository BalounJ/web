/**
 * Created by pepe on 4. 12. 2016.
 */
/*validace registracniho formulare*/


function checkRegisterForm() {
    registerForm(false);
}

function validateRegisterForm() {
    return registerForm(true);
}

function registerForm(validate) {
    var login = document.forms["registerForm"]["login"];
    var pass1 = document.forms["registerForm"]["pass"];
    var pass2 = document.forms["registerForm"]["pass2"];
    var passGlyph = document.getElementById("passGlyph");

    if (pass1.value != "" && pass1.value == pass2.value) {
        passGlyph.className = "glyphicon glyphicon-ok";
    } else {
        passGlyph.className = "glyphicon glyphicon-remove";

        if (validate) {
            alert("Zadaná hesla se neshodují.");
            return false;
        }
    }

    if (validate) {
        var logEx = loginExists(login.value);
        if (logEx != "ok") {
            alert("Login nelze použít, zvolte jiný.");
            return false;
        }
    }
}

/*funkce pro zjisteni existence loginu v DB AJAX*/
function loginExists(login) {
    if (login == "") {
        return "exists";
    }

    xhttp = new XMLHttpRequest();
    xhttp.open("GET", "loginValidation.php?login="+login, false);
    xhttp.send();
    return xhttp.responseText;
}
