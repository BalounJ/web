<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 9. 12. 2016
 * Time: 15:22
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


if(isset($_POST["action"]) && isset($_POST["idPrispevku"])) {    //povinne parametry
    if ($_POST["action"] == "prepareForRec") { //priprava pro pridani recenze
        $db = new Database();
        $clanek = $db->getClanek($_POST["idPrispevku"]);

        if ($clanek != null) {
            $send = array();
            $send["nazev"] = $clanek["nazev"];
            $send["autori"] = $clanek["autori"];
            $send["abstract"] = $clanek["abstract"];

            $recSend = array();
            $recArr = $db->getRecenzenti();
            $i = 0;
            foreach ($recArr as $rec) {
                $recSend[$i]["login"] = $rec["login"];
                $recSend[$i]["jmeno"] = $rec["jmeno"];
                $recSend[$i]["prijmeni"] = $rec["prijmeni"];
                $i++;
            }
            $send["recenzenti"] = $recSend; //odeslu info o clanku a pole recenzentu s potrebnymi udaji
            echo json_encode($send);
        } else {
            echo "Článek nenalezen.";
        }
        die();  //uleva php interpretu
    }

    if (isset($_POST["recenzent"]) && $_POST["action"] == "addRec") { //prideleni recenze
        $db = new Database();
        echo $db->pridelRecenzi($_POST["idPrispevku"], $_POST["recenzent"]);
        die();  //uleva php interpretu
    }
}

if (isset($_POST["action"]) && isset($_POST["idRecenze"]) && $_POST["action"] == "deleteRec") { //smazani recenze
    $db = new Database();
    echo $db->smazRecenzi($_POST["idRecenze"]);
    die();  //uleva php interpretu
}
?>