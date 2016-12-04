<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 3. 12. 2016
 * Time: 21:08
 */
class Prihlaseni{

    public $uzivatel;
    private $db;

    public function __construct(){
        // spusti session pro spravu prihlaseni uzivatele
        session_start();
        // importuje funkce pro práci s databází
        include_once("model/mod-databaze.class.php");
        $this->db = new Database();
    }

    public function jePrihlasen(){
        return isset($_SESSION["prihlasen"]) ? true : false;
    }

    public function prihlasUzivatele($login, $heslo){
        if($this->db->isLoginPasswordCorrect($login, $heslo)){
            $_SESSION["prihlasen"]=$login; // zahajim session uzivatele
            return true;
        } else {
            return false;//uzivatel s heslem nenalezen neprihlasuji
        }
    }

    public function prihlasenyInfo(){
        if(isset($_SESSION["prihlasen"])) {
            return $this->db->userInfo($_SESSION["prihlasen"]);
        }
        else {
            return null;
        }
    }

    public function odhlasUzivatele(){
        session_unset(); // smazu session
        return true;
    }

    public function registraceUzivatele($login, $heslo, $jmeno, $prijmeni, $email){
        $regSucccess = $this->db->addUser($login, $heslo, $jmeno, $prijmeni, $email);
        if($regSucccess === "ok") {
            $this->prihlasUzivatele($login, $heslo);
        }
        return $regSucccess;
    }
}

?>