<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 15:49
 */

class ConPosuzovani {

    public function __construct() {

    }

    /**
     *  Vrati obsah stranky
     *  @return string Obsah stranky
     */
    public function getResult($prihlInfo) {

        if($prihlInfo["prihlasen"] != true or $prihlInfo["prava"] != "recenzent" or $prihlInfo["blokovan"] != "n") {
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

        $data["titulek"] = "Moje recenze";

        include_once("model/mod-databaze.class.php");
        $db = new Database();
        $recArr = $db->getZadaneRecenze($prihlInfo["login"]);
        $recToData = array();

        $i = 0;
        foreach ($recArr as $rec) {                //vytvorim pole rec. s hodnotami o clancich
            $clanek = $db->getClanek($rec["PRISPEVKY_id_prispevku"]);

            $rec["nazev"] = $clanek["nazev"];
            $rec["autori"] = $clanek["autori"];
            $rec["abstract"] = $clanek["abstract"];

            $recToData[$i] = $rec;
            $i++;
        }

        $data["zadaneRecenze"] = $recToData;

        include("view/view-posuzovani.class.php");
        // predam data sablone a ziskam jejich vizualizaci
        $html = ViewPosuzovani::getTemplate($data);
        // vratim vysledny vzhled webu
        return $html;
    }

}

?>