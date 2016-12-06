<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 15:49
 */

class ConClanky {

    public function __construct() {

    }

    /**
     *  Vrati obsah stranky
     *  @return string Obsah stranky
     */
    public function getResult($prihlInfo) {

        $data = $prihlInfo;
      /*  $data["prihlasen"] = true;    prijde z indexu
        $data["login"] = "login";
        $data["prava"] = "autor";
        $data["danger"] = "";
        $data["success"] = "";
      $data["page"] = "uvod";
      */

        $data["titulek"] = "Články";

        include_once("model/mod-databaze.class.php");
        $db = new Database();

        $data["schvaleneClanky"] = $db->getSchvaleneClanky();

            include("view/view-clanky.class.php");
        // predam data sablone a ziskam jejich vizualizaci
        $html = ViewClanky::getTemplate($data);
        // vratim vysledny vzhled webu
        return $html;
    }

}

?>