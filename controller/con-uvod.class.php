<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 15:49
 */

class ConUvod {

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

        $data["titulek"] = "Úvod";

        include("view/view-uvod.class.php");
        // predam data sablone a ziskam jejich vizualizaci
        $html = ViewUvod::getTemplate($data);
        // vratim vysledny vzhled webu
        return $html;
    }

}

?>