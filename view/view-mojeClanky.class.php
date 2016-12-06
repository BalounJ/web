<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 16:49
 */

class ViewMojeClanky {

    /**
     *  Obali data vzhledem stranky a vrati vysledne HTML.
     *  @param $template_params data pro sablonu
     *  @return string Vysledny vzhled.
     */
    public static function getTemplate($template_params){
      //nastaveni twigu
        require_once 'twig-master/lib/Twig/Autoloader.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('view/sablony');
        $twig = new Twig_Environment($loader);

        $uvod = $twig->loadTemplate('mojeClanky.twig');
    //vygenerovani ze sablony
        return $uvod->render($template_params);
    }

}

?>