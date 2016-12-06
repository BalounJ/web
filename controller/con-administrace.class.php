<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 15:49
 */

class ConAdministrace {

    public function __construct() {

    }

    /**
     *  Vrati obsah stranky
     *  @return string Obsah stranky
     */
    public function getResult($prihlInfo) {
        if($prihlInfo["prihlasen"] != true or $prihlInfo["prava"] != "admin") {
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

        $data["titulek"] = "Administrace";

        include("view/view-administrace.class.php");
        // predam data sablone a ziskam jejich vizualizaci
        $html = ViewAdministrace::getTemplate($data);
        // vratim vysledny vzhled webu
        return $html;
    }

}

?>