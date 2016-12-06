<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 5. 12. 2016
 * Time: 19:56
 */

if(!isset($_POST["nazev"])) {    //kontrola povinnych
    return;
    die();
}
if(!isset($_POST["autori"])) {
    return;
    die();
}

if(isset($_POST["action"]) && $_POST["action"] == "upload" && $_FILES["pdf"]["size"] > 0) {   //pokud je co nahrat
    include("model/prihlaseni.class.php");
    $prihlaseni = new Prihlaseni;
    if($prihlaseni->jePrihlasen()) {  // pridavat muze jen prihlaseny uzivatel
        $file = $_FILES["pdf"];

        if($file["type"] == "application/pdf"){ //kotrola zda je pdf ["size"] na velikost
         //   include("model/mod-databaze.class.php");  includovano prihlasenim
            $db = new Database();
            $usr = $prihlaseni->prihlasenyInfo();

          /*  echo $file["error"] . "<br>";
            echo $_POST["nazev"] . "<br>";
            echo $_POST["autori"] . "<br>";
            echo $_POST["abstract"] . "<br>";
            echo $file["tmp_name"] . "<br>";
            echo $file["name"] . "<br>";
            echo $file["size"] . "<br>";
            echo $usr["login"] . "<br>";
*/

            $currentdir = getcwd();
            $target = $currentdir .'/uploads/' . basename($file["name"]);
           // echo $target . "<br>";
            move_uploaded_file($file["tmp_name"], $target);

            echo $db->addClanek($_POST["nazev"], $_POST["autori"], $_POST["abstract"], $file["type"], $file["name"], $target, $usr["login"]);

            //unlink($target);
        }
        else{
            echo "fuck";
        }
    }
}


?>