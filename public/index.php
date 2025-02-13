<?php

session_start();

switch (session_status()) {
    case 0:                     // PHP_SESSION_DISABLED, wenn Sessions deaktiviert sind.
        echo "<br>SESSION ist deaktiviert und kann nicht genutzt werden.";
        echo "<br>Damit fehlt eine elementare Voraussetzung für die Web-Anwendung!";
        exit;
    case 1:                     // PHP_SESSION_NONE, wenn Sessions aktiviert sind, aber keine existiert.
        echo "<br>SESSION ist aktiviert aber nicht existent.";
        echo "<br>Damit fehlt eine elementare Voraussetzung für die Web-Anwendung!";
        session_abort();
        exit;
    case 2:                     // PHP_SESSION_ACTIVE, wenn Sessions aktiviert sind und eine existiert.
#echo "<br>SESSION ist aktiviert und existent!";
        if (is_file('../Core/Router.php')) {
            include_once '../Core/Router.php';
            if (is_file('../Core/Funktionen.php')) {
                include_once '../Core/Funktionen.php';
            } else {
                echo "<br>Die Funktionen-Datei wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                exit;
            }
        } else {
            echo "<br>Die Datei ../Core/Router.php wurde nicht gefunden.";
            echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
            session_abort();
            exit;
        }
            
        if(!isset($_SESSION['loginerroers'])) {
            $_SESSION['loginerroers'] = 0;
        }
        if($_SERVER['HTTP_HOST'] == "localhost") {
            $host_name = 'localhost';
            $database = 'dbs11102410';
            $user_name = 'root';
            $password = '';
        } else {
            $host_name = 'db5013234994.hosting-data.io';
            $database = 'dbs11102410';
            $user_name = 'dbu5549742';
            $password = 'PB9+&ywua4HB_Q.c!150';
        }
        $dbh = new mysqli($host_name, $user_name, $password, $database);
        if ($dbh->connect_error) {
            die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
        } /*else {
            echo '<br>Verbindung zum MySQL Server erfolgreich aufgebaut.';
        }*/

        $router = new Router($dbh); // Router-Objekt erstellen
        $i = $router->countRoutes();
        if(empty($_GET) || is_null($_GET)) {
#echo "<br>_GET is empty or null";
            $_GET['Home'] = 'anmelden';
        }
#echo "<br>_GET = "; print_r($_GET);
        // Controler und Action prüfen
        if(count(array_keys($_GET)) == 1) {
            $route = $router->selectControler();
            if(is_null($route)) {
                $route['Controler'] = "Home";
                $route['Action'] = "abmelden"; 
            }
        } else {
            echo "<br>Keine oder zu viele Parameter übergeben. Programm kann nicht fortgesetzt werden!";
            session_abort();
            exit;
        }
#echo "<br>route = "; print_r($route);
        #if(!checkRights($route['Controler'], $route['Action'])) {
        #    header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anzeigenMenue"); /* Browser umleiten */
        #    exit;        
        #} else {
            $_SESSION['Controler'] = $route['Controler'];
            $_SESSION['Action'] = $route['Action'];
            $controler = "controler" . $route['Controler'];
            if (is_file("../MVC/Controlers/$controler/$controler.php")) {
                include_once "../MVC/Controlers/$controler/$controler.php";
            } else {
                echo "<br>Die Datei" . "../MVC/Controlers/$controler/$controler.php" . " wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                exit;
            }
    #echo "<br>index.php - route = "; print_r($route);
            $$controler = new $controler($dbh);
            $$controler->execute($route);
        #}
        break;


    default:
        echo "<br>SESSION hat einen unbekannten Status.";
        echo "<br>Damit fehlt eine elementare Voraussetzung für die Web-Anwendung!";
        exit;
}

#echo "<br>Ende der Ausführung";
