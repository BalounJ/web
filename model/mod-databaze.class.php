<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 29. 11. 2016
 * Time: 18:47
 */

include_once("settings.inc.php");

class Database {

    private $db; // PDO objekt databaze

    public function __construct(){
        global $db_server, $db_name, $db_user, $db_pass;
        // informace se berou ze settings
        $conn = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $conn;
    }

    /**
     *  Vytvori v databazi noveho uzivatele.
     *
     *  @return boolean         zda byl pridan
     */
    public function addUser($login, $heslo, $jmeno, $prijmeni, $email){
        if($this->userExists($login)){
            return "obsazeno";
        }
        try {
            $sth = $this->db->prepare("INSERT INTO UCTY(login, heslo, jmeno, prijmeni, email)
                VALUES (:login,:heslo,:jmeno,:prijmeni,:email)");
            $sth->bindParam(':login', $login);
            $sth->bindParam(':heslo', $heslo);
            $sth->bindParam(':jmeno', $jmeno);
            $sth->bindParam(':prijmeni', $prijmeni);
            $sth->bindParam(':email', $email);
            $sth->execute();

            if($this->userExists($login)){
                return "ok";
            }
            else {
                return "chyba";
            }
        } catch (Exception $e) {
            return "chyba"; //chyba v pozadavku
        }
    }

    /**
     *  Vraci vsechny informace o uzivateli.
     *  @param string $login    Login uzivatele.
     *  @return array           Vysledek dotazu na uzivatele.
     */
    public function userInfo($login){
        $sth = $this->db->prepare("SELECT * FROM UCTY
               WHERE login LIKE :login");
        $sth->bindParam(':login', $login);
        $sth->execute();
        $row = $sth->fetch();
        return $row;
    }


    /**
     *  Overi, zda dany login existuje.
     *  @param string $login  Login uzivatele.
     *  @return boolean         Existuje login?
     */
    public function userExists($login){
        $usr = $this->userInfo($login);
        if($usr == null) { // uzivatel neni v DB
            return false;
        }
        else {
            return true;
        }
    }

    /**
     *  Overi, zda dany uzivatel ma dane heslo.
     *  @param string $login  Login uzivatele.
     *  @param string $pass     Heslo uzivatele.
     *  @return boolean         Odpovida heslo a login?
     */
    public function isLoginPasswordCorrect($login, $pass){
        $usr = $this->userInfo($login);
        if($usr==null){ // uzivatel neni v DB
            return false;
        }
        return $usr["heslo"] == $pass; // je heslo stejne?
    }


    /**
     *  Vytvori v databazi novy clanek.
     *
     *  @return boolean         zda byl pridan
     */
    public function addClanek($nazev, $autori, $abstract, $koncovka, $nazev_pdf, $filePath, $login){
        try {

            $sql = "INSERT INTO PRISPEVKY(nazev, autori, abstract, koncovka, nazev_pdf, UCTY_login) VALUES (:nazev, :autori, :abstract, :koncovka, :nazev_pdf, :login)";
            $sth = $this->db->prepare($sql);

            $sth->bindParam(':nazev', $nazev);
            $sth->bindParam(':autori', $autori);
            $sth->bindParam(':abstract', $abstract);
            $sth->bindParam(':koncovka', $koncovka);
            $sth->bindParam(':nazev_pdf', $nazev_pdf);
            //$sth->bindParam(':pdf', $fs, PDO::PARAM_LOB);
            $sth->bindParam(':login', $login);
            //$this->db->beginTransaction();
            return $sth->execute();




           /* //return $nazev . "<br>" . $autori . "<br>" . $abstract . "<br>" . $koncovka . "<br>" . $nazev_pdf . "<br>" . $filePath . "<br>" . $login;

            $fs = fopen($filePath, "rb");

            $sql = "INSERT INTO PRISPEVKY(nazev, autori, abstract, koncovka, nazev_pdf, pdf, UCTY_login) VALUES (:nazev, :autori, :abstract, :koncovka, :nazev_pdf, :pdf, :login)";
            $sth = $this->db->prepare($sql);

            $sth->bindParam(':nazev', $nazev);
            $sth->bindParam(':autori', $autori);
            $sth->bindParam(':abstract', $abstract);
            $sth->bindParam(':koncovka', $koncovka);
            $sth->bindParam(':nazev_pdf', $nazev_pdf);
            $sth->bindParam(':pdf', $fs, PDO::PARAM_LOB);
            $sth->bindParam(':login', $login);
            //$this->db->beginTransaction();
            $ret = $sth->execute();
            //$this->db->commit();

        //    fclose($fs);

            return $ret;*/

        } catch (Exception $e) {
            return "chyba"; //chyba v pozadavku
        }
    }

    /**
     *  Vraci vsechny schvalene clanky v db.
     *  @return array           Vysledek dotazu na clanky.
     */
    public function getSchvaleneClanky(){
        $sth = $this->db->prepare("SELECT * FROM PRISPEVKY
               WHERE stav LIKE 'schváleno'");
        $sth->execute();
        $data = $sth->fetchAll();
        return $data;
    }

    /**
     *  Vraci vsechny clanky v db daneho uzivatele.
     *  @param string $login    Login uzivatele.
     *  @return array           Vysledek dotazu na clanky uzivatele.
     */
    public function getClanky($login){
        $sth = $this->db->prepare("SELECT * FROM PRISPEVKY
               WHERE UCTY_login LIKE :login");
        $sth->bindParam(':login', $login);
        $sth->execute();
        $data = $sth->fetchAll();
        return $data;
    }

    /**
     *  Vraci vsechny clanky v db daneho uzivatele.
     *  @param string $login    Login uzivatele.
     *  @return array           Vysledek dotazu na clanky uzivatele.
     */
    public function getId($login){
        $sth = $this->db->prepare("SELECT * FROM PRISPEVKY
               WHERE UCTY_login LIKE :login");
        $sth->bindParam(':login', $login);
        $sth->execute();
        $data = $sth->fetchAll();
        return $data;
    }

}



?>