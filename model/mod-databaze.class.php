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
     *  @return          zda byl pridan
     */
    public function addClanek($nazev, $autori, $abstract, $koncovka, $nazev_pdf, $filePath, $login){
        try {
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
            $this->db->beginTransaction();
            $sth->execute();
            $this->db->commit();

            fclose($fs);

            return "ok";

        } catch (Exception $e) {
            return "Chyba při práci s databází."; //. $e->getMessage(); //chyba v pozadavku
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
     *  Vraci clanek v db podle id.
     *  @param int $id    id clanku
     *  @return            Vysledek dotazu na clanky uzivatele.
     */
    public function getClanek($id){
        $sth = $this->db->prepare("SELECT * FROM PRISPEVKY
               WHERE id_prispevku = :id");
        $sth->bindParam(':id', $id);
        $sth->execute();
        $data = $sth->fetch();
        return $data;
    }

    /**
     *  Smaze clanek v db podle id.
     *  @param int $id    id clanku
     *  @return            Vysledek dotazu na smazani clanku.
     */
    public function deleteClanek($id){
        try {
            $sth = $this->db->prepare("DELETE FROM PRISPEVKY
               WHERE id_prispevku = :id");
            $sth->bindParam(':id', $id);
            $sth->execute();

            return "ok";
        }
        catch (Exception $e) {
            return "Chyba při práci s databází."; //. $e->getMessage(); //chyba v pozadavku
        }
    }

    /**
     *  Vytvori v databazi novy clanek.
     *
     *  @return          zda byl editovan
     */
    public function editClanek($id, $file, $nazev, $autori, $abstract, $koncovka, $nazev_pdf, $filePath){
        try {
            if($file) {
                $fs = fopen($filePath, "rb");
                $sql = "UPDATE PRISPEVKY 
                          SET nazev=:nazev,
                            autori=:autori,
                            abstract=:abstract,
                            koncovka=:koncovka,
                            nazev_pdf=:nazev_pdf,
                            pdf=:pdf
                          WHERE 
                            id_prispevku=:id;";
            }
            else {
                $sql = "UPDATE PRISPEVKY 
                          SET nazev=:nazev,
                            autori=:autori,
                            abstract=:abstract
                          WHERE 
                            id_prispevku=:id;";
            }
            $sth = $this->db->prepare($sql);

            $sth->bindParam(':nazev', $nazev);
            $sth->bindParam(':autori', $autori);
            $sth->bindParam(':abstract', $abstract);
            $sth->bindParam(':id', $id);
            if($file) {
                $sth->bindParam(':koncovka', $koncovka);
                $sth->bindParam(':nazev_pdf', $nazev_pdf);
                $sth->bindParam(':pdf', $fs, PDO::PARAM_LOB);
            }
            $this->db->beginTransaction();
            $sth->execute();
            $this->db->commit();
            if($file) {
                fclose($fs);
            }
            return "ok";

        } catch (Exception $e) {
            return "Chyba při práci s databází."; //. $e->getMessage(); //chyba v pozadavku
        }
    }


}



?>