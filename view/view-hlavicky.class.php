<?php
/**
 * Created by PhpStorm.
 * User: pepe
 * Date: 26. 11. 2016
 * Time: 15:58
 */

class ViewHlavicky {


    /**
     *  Vrati hlavicku stranky.
     *  @param string $title Nazev stranky.
     *  @return string Hlavicka stranky.
     */
    public static function getHTMLHeader($title){
        return "<!DOCTYPE html>
<html lang=\"cs\">

<head>
    <meta charset=\"UTF-8\">
    <title>Konference PLUTO 2015</title>

    <!-- css bootstrapu -->
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
    <!-- jQuery -->
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js\"></script>
    <!-- JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <!-- dodatečný styl -->
    <link rel=\"stylesheet\" href=\"view/styl.css\">

</head>

<body>
    <div class=\"container\">

        <!-- Modal pop up formular pro prihlaseni -->
        <div class=\"modal fade\" id=\"loginWindow\" role=\"dialog\">
            <div class=\"modal-dialog modal-lg\">
                <form class=\"modal-content animate\" action=\"index.php\" method=\"post\">
                    <div class=\"modal-body\">
                        <div class=\"form-group\">
                            <label><b>Login</b></label>
                            <input type=\"text\" placeholder=\"Zadejte váš login\" name=\"login\" required>
                        </div>
                        <div class=\"form-group\">
                            <label><b>Heslo</b></label>
                            <input type=\"password\" placeholder=\"Zadejte heslo\" name=\"pass\" required>
                        </div>
                        <button class=\"btn btn-success\" type=submit\">Přihlásit se</button>
                        <button class=\"btn btn-danger\" type=\"button\" data-dismiss=\"modal\">Zrušit</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal up formular pro registraci -->
        <div class=\"modal fade\" id=\"registerWindow\" role=\"dialog\">
            <div class=\"modal-dialog modal-lg\">
                <form class=\"modal-content animate\" action=\"action_page.php\" method=\"post\">
                    <div class=\"modal-body\">
                        <div class=\"form-group\">
                            <label><b>Login</b></label>
                            <input type=\"text\" placeholder=\"Zadejte váš login\" name=\"login\" required>
                        </div>
                        <div class=\"form-group\">
                            <label><b>Heslo</b></label>
                            <input type=\"password\" placeholder=\"Zadejte heslo\" name=\"pass\" required>
                        </div>
                        <div class=\"form-group\">
                            <label><b>Jméno</b></label>
                            <input type=\"text\" placeholder=\"Zadejte jméno\" name=\"name\" required>
                        </div>
                        <div class=\"form-group\">
                            <label><b>Příjmení</b></label>
                            <input type=\"text\" placeholder=\"Zadejte příjmení\" name=\"surname\" required>
                        </div>
                        <div class=\"form-group\">
                            <label><b>Login</b></label>
                            <input type=\"email\" placeholder=\"Zadejte váš email\" name=\"email\" required>
                        </div>
                        <button class=\"btn btn-success\" type=submit\">Zaregistrovat se</button>
                        <button class=\"btn btn-danger\" type=\"button\" data-dismiss=\"modal\">Zrušit</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Zahlavi -->
        <header class=\"jumbotron\">
            <h1>Ukázka Bootstrap</h1> aneb, jak si usnadnit vývoj front-endu..
        </header>




        <!-- menu -->
        <nav class=\"navbar navbar-inverse\">
            <div class=\"container-fluid\">
                <div class=\"navbar-header\">
                    <a class=\"navbar-brand\" href=\"#\">WebSiteName</a>
                </div>
                <ul class=\"nav navbar-nav\">
                    <li class=\"active\"><a href=\"index.php\">index</a></li>
                    <li><a href=\"#\">Page 1</a></li>
                    <li><a href=\"htmlExample.html\">htmlExample</a></li>
                </ul>
                <ul class=\"nav navbar-nav navbar-right\">
                    <li><a href=\"\" data-toggle=\"modal\" data-target=\"#registerWindow\"><span class=\"glyphicon glyphicon-user\"></span> Registrace</a></li>
                    <li><a href=\"\" data-toggle=\"modal\" data-target=\"#loginWindow\"><span class=\"glyphicon glyphicon-log-in\"></span> Přihlášení</a></li>
                </ul>
            </div>
        </nav>";
    }

    /**
     *  Vrati paticku stranky.
     *  @return string Paticka stranky.
     */
    public static function getHTMLFooter(){
        return  " <!-- Zapati -->
        <footer class=\"row\">
            <div class=\"col-sm-12\">
                zapati 
            </div>
        </footer>
    </div>

</body>

</html>";
    }

}

?>