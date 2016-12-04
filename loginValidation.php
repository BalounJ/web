<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 4. 12. 2016
 * Time: 12:26
 */

if(isset($_GET["login"]) && $_GET["login"] != "") {
    include("model/mod-databaze.class.php");
    $db = new Database();

    if($db->userExists($_GET["login"])) {
        echo "obsazen";
    }
    else{
        echo "ok";
    }
}
else {
    echo "<html><head><meta charset='utf-8'></head><body>stránka není dostupná</body></html>";
}
?>