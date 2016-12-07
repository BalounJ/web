<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 5. 12. 2016
 * Time: 19:56
 */

function printPDF($type, $data, $nazev) {
    header("Content-Type:" . $type);
            //               attachment
    header('Content-Disposition: inline; filename="'.$nazev.'"');

    echo $data;
}

//id a akce je treba pro info o clanku smazani nebo otevreni pdf
if(isset($_POST["action"]) && isset($_POST["id"])) {

    //pro pripravu formulare odeslani informaci o clanku s id
    if($_POST["action"] == "prepare") {
        include("model/prihlaseni.class.php");
        $prihlaseni = new Prihlaseni;
        if ($prihlaseni->jePrihlasen()) {  // editovat muze jen prihlaseny uzivatel
            $db = new Database();
            $clanek = $db->getClanek($_POST["id"]);
            $usr = $prihlaseni->prihlasenyInfo();
            if ($clanek != null and $clanek["UCTY_login"] === $usr["login"]) { //je autorem muze editovat vratim data
                $send = array();
                $send["nazev"] = $clanek["nazev"];
                $send["autori"] = $clanek["autori"];
                $send["abstract"] = $clanek["abstract"];
                echo json_encode($send);
            } else {
                echo "nelze";
            }
        }
        die();  //uleva php interpretu
    }

    //pro smazani clanku
    if($_POST["action"] == "delete") {
        include("model/prihlaseni.class.php");
        $prihlaseni = new Prihlaseni;
        if ($prihlaseni->jePrihlasen()) {  // mazat muze jen prihlaseny uzivatel
            $db = new Database();
            $clanek = $db->getClanek($_POST["id"]);
            $usr = $prihlaseni->prihlasenyInfo();
            if ($clanek != null and $clanek["UCTY_login"] === $usr["login"]) { //je autorem smazat data
                echo $db->deleteClanek($_POST["id"]);
            } else {
                echo "Článek nebyl nenalezen.";
            }
        }
        die();  //uleva php interpretu
    }


    //pro smazani clanku
    if($_POST["action"] == "showPDF") {
        echo "joup";
        include("model/prihlaseni.class.php");
        $prihlaseni = new Prihlaseni;
        $db = new Database();
        $clanek = $db->getClanek($_POST["id"]);
        if ($clanek != null) {
            if ($clanek["stav"] === "schváleno") {   //mohou videt vsichni
                printPDF($clanek["koncovka"], $clanek["pdf"], $clanek["nazev_pdf"]);
                die();
            } else {  //muze videt pouze autor clanku nebo recenzenti a admin
                if ($clanek["UCTY_login"] === $usr["login"] or $usr["prava"] === "recenzent" or $usr["prava"] === "admin") {
                    printPDF($clanek["koncovka"], $clanek["pdf"], $clanek["nazev_pdf"]);
                    die();
                }
                else {
                    echo "Článek nebyl nenalezen.";
                }
            }
        }
        else {
            echo "Článek nebyl nenalezen.";
        }
        die();  //uleva php interpretu
    }
}



if(!isset($_POST["nazev"])) {    //kontrola povinnych
    return;
    die();
}
if(!isset($_POST["autori"])) {
    return;
    die();
}

//nahrani noveho clanku
if(isset($_POST["action"]) && $_POST["action"] == "upload" && $_FILES["pdf"]["size"] > 0) {   //pokud je co nahrat
    include("model/prihlaseni.class.php");
    $prihlaseni = new Prihlaseni;
    if($prihlaseni->jePrihlasen()) {  // pridavat muze jen prihlaseny uzivatel
        $file = $_FILES["pdf"];

        if($file["type"] == "application/pdf"){ //kotrola zda je pdf ["size"] na velikost
         //   include("model/mod-databaze.class.php");  includovano prihlasenim
            $db = new Database();
            $usr = $prihlaseni->prihlasenyInfo();

            $currentdir = getcwd();
            $target = $currentdir .'/uploads/' . basename($file["name"]);
            move_uploaded_file($file["tmp_name"], $target);

            $ret = $db->addClanek($_POST["nazev"], $_POST["autori"], $_POST["abstract"], $file["type"], $file["name"], $target, $usr["login"]);

            unlink($target);

            echo $ret;
        }
        else{
            echo "Soubor není .pdf, odmítnuto.";
        }
    }
    die();  //uleva php interpretu
}

//editace clanku
if(isset($_POST["action"]) && $_POST["action"] == "edit") {
    include("model/prihlaseni.class.php");
    $prihlaseni = new Prihlaseni;
    if ($prihlaseni->jePrihlasen()) {  // editovat muze jen prihlaseny uzivatel
        $db = new Database();
        $clanek = $db->getClanek($_POST["id"]);
        $usr = $prihlaseni->prihlasenyInfo();
        if ($clanek != null and $clanek["UCTY_login"] === $usr["login"]) { //je autorem muze editovat
            if($_FILES["pdf"]["size"] > 0) {    //editace i souboru
                if($_FILES["pdf"]["type"] == "application/pdf") { //kotrola zda je pdf


                    echo $db->editClanek($_POST["id"], true, $_POST["nazev"], $_POST["autori"], $_POST["abstract"], $_FILES["pdf"]["type"], $_FILES["pdf"]["name"], $_FILES["pdf"]["tmp_name"]);
                }
                else{
                    echo "Soubor není .pdf, odmítnuto.";
                }
            }
            else {      //edit bez souboru
                echo $db->editClanek($_POST["id"], false, $_POST["nazev"], $_POST["autori"], $_POST["abstract"], null, null, null);
            }
        }
        else {
            echo "Článek nebyl nenalezen.";
        }
    }
}


?>