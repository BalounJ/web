<?php
/** Nastaveni
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 14:52
 */

global $db_server, $db_name, $db_user, $db_pass;
global $pages, $controllers, $objects;

// databaze
    $db_server = "127.0.0.1";
    $db_name = "balounj_web_db";
    $db_user = "root";
    $db_pass = "";


// pages - stranky webu (ostatni nebudou dostupne)
// controllers - kontroler dane stranky
// objects - nazev objektu v kontroleru
    $pages[0] = "uvod"; //default
    $controllers[0] = "controller/con-uvod.class.php";
    $objects[0] = "ConUvod";



?>