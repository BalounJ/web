<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 9. 12. 2016
 * Time: 21:38
 */

include("model/prihlaseni.class.php");
$prihlaseni = new Prihlaseni;
if (!$prihlaseni->jePrihlasen()) {  //pokud neni prihlasen
    die();
}
$usr = $prihlaseni->prihlasenyInfo();
if ($usr["prava"] !== "admin" and $usr["prava"] !== "recenzent") {    //neni admin nebo recenzent
    die();
}
if ($usr["blokovan"] !== "n") {    //je blokovan
    die();
}


if(isset($_POST["action"]) && $_POST["action"] == "posouzeni" && isset($_POST["idRecenze"]) && isset($_POST["originalita"]) && isset($_POST["tema"]) && isset($_POST["kvalita"]) && isset($_POST["doporuceni"])) {
    $db = new Database();
    echo $db->odevzdejRecenzi($_POST["idRecenze"], $_POST["originalita"], $_POST["tema"], $_POST["kvalita"], $_POST["doporuceni"], $_POST["info"]);
}




?>