<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 15:49
 */

class ConAdminRec {

    public function __construct() {

    }

    /**
     *  Vrati obsah stranky
     *  @return string Obsah stranky
     */
    public function getResult($prihlInfo) {
        if($prihlInfo["prihlasen"] != true or $prihlInfo["prava"] != "admin" or $prihlInfo["blokovan"] != "n") {
            // uzivatel nema opravneni zobrazit stranku
            header('Location: index.php');
            die();
        }

        $data = $prihlInfo;
      /*  $data["prihlasen"] = true;    prijde z indexu
        $data["login"] = "login";
        $data["prava"] = "autor";
        $data["danger"] = "";
        $data["success"] = "";
      $data["page"] = "uvod";
      */

        $data["titulek"] = "Správa recenzí";

        include_once("model/mod-databaze.class.php");
        $db = new Database();
       // $data["schvalene"] = $db->getSchvaleneClanky();   neni treba
        $clVR = $db->getVRizeniClanky();
        $clankyVRizSRec = array();
        $i = 0;
        foreach ($clVR as $clanek) {                //vytvorim pole clanku se svymi recenzemi
            $recCl = $db->getRecenzeClanku($clanek["id_prispevku"]);
            sizeof($recCl);

            $clanek["pocetRecenzi"] = sizeof($recCl);

            $clanek["recenze"] = $recCl;

            $clankyVRizSRec[$i] = $clanek;
            $i++;
        }

        $data["vRizeni"] = $clankyVRizSRec;

       // $data["odmitnute"] = $db->getOdmitnuteClanky();   neni treba


        include("view/view-adminRec.class.php");
        // predam data sablone a ziskam jejich vizualizaci
        $html = ViewAdminRec::getTemplate($data);
        // vratim vysledny vzhled webu
        return $html;
    }

}

?>