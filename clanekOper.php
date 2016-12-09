<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 5. 12. 2016
 * Time: 19:56
 */


include("model/prihlaseni.class.php");
$prihlaseni = new Prihlaseni;
if ($prihlaseni->jePrihlasen()) {
    $usr = $prihlaseni->prihlasenyInfo();
    if ($usr["blokovan"] !== "n") {    //je blokovan nema pristup
        die();
    }
}
$db = new Database();


function printPDF($type, $data, $nazev) {
    header("Content-Type:" . $type);
            //               attachment
    header('Content-Disposition: inline; filename="'.$nazev.'"');

    echo $data;
}

//pro zobrazeni pdf clanku
if(isset($_POST["action"]) && isset($_POST["id"]) && $_POST["action"] == "showPDF") {
    $clanek = $db->getClanek($_POST["id"]);
    if ($clanek != null) {
        if ($clanek["stav"] === "schváleno") {   //mohou videt vsichni
            printPDF($clanek["koncovka"], $clanek["pdf"], $clanek["nazev_pdf"]);
            die();
        } else {  //muze videt pouze autor clanku nebo recenzenti a admin
            if ($prihlaseni->jePrihlasen()) {
                $usr = $prihlaseni->prihlasenyInfo();
                if ($clanek["UCTY_login"] === $usr["login"] or $usr["prava"] === "recenzent" or $usr["prava"] === "admin") {
                    printPDF($clanek["koncovka"], $clanek["pdf"], $clanek["nazev_pdf"]);
                    die();
                } else {
                    echo "Článek nebyl nenalezen.";
                }
            }
        }
    }
    else {
        echo "Článek nebyl nenalezen.";
    }
    die();  //uleva php interpretu
}







/*-*---------- dale muze jen prihlaseny uziv.----------------------------*/
if (!$prihlaseni->jePrihlasen()) {  //pokud neni prihlasen
    die();
}
$usr = $prihlaseni->prihlasenyInfo();

//id a akce je treba
if(isset($_POST["action"]) && isset($_POST["id"])) {
    //pro pripravu formulare odeslani informaci o clanku s id
    if($_POST["action"] == "prepare") {
        $clanek = $db->getClanek($_POST["id"]);
        if ($clanek != null and $clanek["UCTY_login"] === $usr["login"]) { //je autorem muze editovat vratim data
            $send = array();
            $send["nazev"] = $clanek["nazev"];
            $send["autori"] = $clanek["autori"];
            $send["abstract"] = $clanek["abstract"];
            echo json_encode($send);
        } else {
            echo "nelze";
        }
        die();  //uleva php interpretu
    }

    //pro smazani clanku
    if($_POST["action"] == "delete") {
        $clanek = $db->getClanek($_POST["id"]);
        if ($clanek != null and $clanek["UCTY_login"] === $usr["login"]) { //je autorem smazat data
            echo $db->deleteClanek($_POST["id"]);
        } else {
            echo "Článek nebyl nenalezen.";
        }
        die();  //uleva php interpretu
    }

    if($_POST["action"] == "odmitnout") {       //odmitnuti prispevku
        if ($usr["prava"] === "admin") {// smazat recenzi muze jen admin
            echo $db->odmitnoutClanek($_POST["id"]);
        }
        die();  //uleva php interpretu
    }

    if($_POST["action"] == "prijmout") {        //prijmuti prispevku
        if ($usr["prava"] === "admin") {// smazat recenzi muze jen admin
            echo $db->prijmoutClanek($_POST["id"]);
        }
        die();  //uleva php interpretu
    }
}


if (isset($_POST["nazev"]) && isset($_POST["autori"])) {

//nahrani noveho clanku
    if (isset($_POST["action"]) && $_POST["action"] == "upload" && $_FILES["pdf"]["size"] > 0) {   //pokud je co nahrat
        $file = $_FILES["pdf"];
        if ($file["type"] == "application/pdf") { //kotrola zda je pdf    //["size"] na velikost
          /*  $currentdir = getcwd();
            $target = $currentdir . '/uploads/' . basename($file["name"]);
            move_uploaded_file($file["tmp_name"], $target);*/
            $ret = $db->addClanek($_POST["nazev"], $_POST["autori"], $_POST["abstract"], $file["type"], $file["name"], $file["tmp_name"], $usr["login"]);
           /* unlink($target);*/
            echo $ret;
        } else {
            echo "Soubor není .pdf, odmítnuto.";
        }
        die();  //uleva php interpretu
    }

//editace clanku
    if (isset($_POST["action"]) && $_POST["action"] == "edit") {
        $clanek = $db->getClanek($_POST["id"]);
        if ($clanek != null and $clanek["UCTY_login"] === $usr["login"]) { //je autorem muze editovat
            if ($_FILES["pdf"]["size"] > 0) {    //editace i souboru
                if ($_FILES["pdf"]["type"] == "application/pdf") { //kotrola zda je pdf
                    echo $db->editClanek($_POST["id"], true, $_POST["nazev"], $_POST["autori"], $_POST["abstract"], $_FILES["pdf"]["type"], $_FILES["pdf"]["name"], $_FILES["pdf"]["tmp_name"]);
                } else {
                    echo "Soubor není .pdf, odmítnuto.";
                }
            } else {      //edit bez souboru
                echo $db->editClanek($_POST["id"], false, $_POST["nazev"], $_POST["autori"], $_POST["abstract"], null, null, null);
            }
        } else {
            echo "Článek nebyl nenalezen.";
        }
    }
}

?>