<?php
/** Rozcestnik
 * Created by PhpStorm.
 * User: pepe
 * Date: 25. 11. 2016
 * Time: 17:40
 */


/////// NASTAVENI ///////////
// vynucený výpis všech chyb serveru
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
/////////////////////////////
// nacteni nastaveni
include("settings.inc.php");

/////// sprava prihlaseni uzivatele /////////
include("model/prihlaseni.class.php"); // patricna trida
$prihlaseni = new Prihlaseni;
/////// info o prihlaseni pro controller a nasledne sablonu DRY /////////
$prihlInfo = array();
//////////////////////////////////

// akce uzivatele login logout register
if (isset($_POST["action"])) {
    if($_POST["action"]=="login" and !$prihlaseni->jePrihlasen()){
        //$prihlaseni->odhlasUzivatele();
        $prihlStav = $prihlaseni->prihlasUzivatele($_POST["login"], $_POST["pass"]);
        if ($prihlStav === "blokovan") {
            $prihlInfo["danger"] = "Uživatel je blokován.";
        }
        else {
            if ($prihlStav) {
                //uspesne prihlasen
                $prihlInfo["success"] = "Přihlášení jako " . $_POST["login"] . " bylo úspěšné.";
            } else {
                //neuspech pri prihlasovani
                $prihlInfo["danger"] = "Login a heslo nejsou správné, zkuste to prosím znovu.";
            }
        }
    }

    if($_POST["action"]=="register" and !$prihlaseni->jePrihlasen()){   //($login, $heslo, $jmeno, $prijmeni, $email)
        $regSucccess = $prihlaseni->registraceUzivatele($_POST["login"], $_POST["pass"], $_POST["name"], $_POST["surname"], $_POST["email"]);
        if($regSucccess === "ok") {
            //uspesna registrace
            $prihlInfo["success"] = "Registrace jako " . $_POST["login"] . " byla úspěšná.";
        }
        else {
            //neuspech pri registraci
            if($regSucccess === "obsazeno") {
                $prihlInfo["danger"] = "Login " . $_POST["login"] . " je obsazen, zvolte prosím jiný.";
            }
            else {
                $prihlInfo["danger"] = "Registrace se nezdařila, zkuste to prosím později.";
            }
        }
    }

    if($_POST["action"]=="logout" and $prihlaseni->jePrihlasen()){
        if($prihlaseni->odhlasUzivatele()) {
            //uspesne odhlasen
            $prihlInfo["success"] = "Odhlášení bylo úspěšné.";
        }
        else {
            //neuspech pri odhlasovani
            $prihlInfo["danger"] = "Odhlášení se nezdařilo.";
        }
    }
}
//predani hodnoty o prihlaseni kontroleru
$prihlInfo["prihlasen"] = $prihlaseni->jePrihlasen();
if($prihlInfo["prihlasen"]) {
    $pr = $prihlaseni->prihlasenyInfo();
    $prihlInfo["login"] = $pr["login"];
    $prihlInfo["prava"] = $pr["prava"];
    $prihlInfo["blokovan"] = $pr["blokovan"];
}






// klasicke URL
if(isset($_GET["page"])){
    if(in_array($_GET["page"], $pages)){
        // je v seznamu
        $input = $_GET["page"];
    } else {
        // neni v seznamu
        $input = null;
    }
} else {
    // nastavim default page
    $input = $pages[0];
}
/*
// hezke URL - jen mozny nastin - nutno dopracovat
$url=strip_tags($_SERVER['REQUEST_URI']);
$urlAr=explode("/",$url);
//echo $url;
$q = @$_REQUEST["q"];
echo "tady mam požadovanou stránku dle hezkých url: $url a $g";
*/
if(isset($input)){
    $prihlInfo["page"] = $input;
    // vstup je spravny
    // ziskam index stranky
    $index = array_search($input, $pages);
    // ziskam soubor kontroleru
    $conFile = $controllers[$index];
    // includuji
    include($conFile);
    // vytvorim odpovidajici kontroler
    $con = new $objects[$index];
    // ziskam vysledek kontroleru
    $result = $con->getResult($prihlInfo);
    // zobrazim
    echo $result;
} else {
    // vstup neni spravny - pouze vypisu mini HTML
    echo "<html><head><meta charset='utf-8'></head><body>stránka není dostupná</body></html>";
}



?>