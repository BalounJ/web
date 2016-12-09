<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 9. 12. 2016
 * Time: 18:48
 */

include("model/prihlaseni.class.php");
$prihlaseni = new Prihlaseni;
if (!$prihlaseni->jePrihlasen()) {  //pokud neni prihlasen
    die();
}
$usr = $prihlaseni->prihlasenyInfo();
if ($usr["prava"] !== "admin") {    //neni admin
    die();
}
if ($usr["blokovan"] !== "n") {    //je blokovan
    die();
}

if (isset($_POST["action"]) && isset($_POST["login"])) {
    if (isset($_POST["prava"]) && $_POST["action"] == "zmenitPrava") {  //zmena prav
        $db = new Database();
        echo $db->zmenPrava($_POST["login"], $_POST["prava"]);
        die();  //uleva php interpretu
    }

    if ($_POST["action"] == "odblokovat") {  //odblokovani uctu
        $db = new Database();
        echo $db->odblokujUcet($_POST["login"]);
        die();  //uleva php interpretu
    }

    if ($_POST["action"] == "blokovat") {  //blokovani uctu
        $db = new Database();
        echo $db->blokujUcet($_POST["login"]);
        die();  //uleva php interpretu
    }
}
?>