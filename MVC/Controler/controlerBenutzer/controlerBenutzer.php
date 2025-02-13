<?php

    class controlerBenutzer {
        private $dbh;
        private viewAngebotsjahre $viewAngebotsjahre;
        private modelCollection_Benutzer $modelCollection_Benutzer;
        private array $dataCollection_Benutzer;
        private string $webpage;
    
        public function __construct($dbh) {
            $this->dbh = $dbh;
            $this->dataCollection_Benutzer = array();
            $this->webpage = "";
            include_once '../Core/Funktionen.php';
            if (is_file("../MVC/Models/modelBenutzer/modelCollection_Benutzer.php")) {
                include_once "../MVC/Models/modelBenutzer/modelCollection_Benutzer.php";
            } else {
                echo "<br>Die Datei '../MVC/Models/modelJahre/modelCollection_Benutzer.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            $this->modelCollection_Benutzer = new modelCollection_Benutzer($this->dbh);
            $this->modelCollection_Benutzer->loadBenutzer();
#            $this->dataAngebotsjahre = $this->modelCollection_Benutzer->getAnbieter();
#            $_SESSION['dataAngebotsjahre'] = $this->dataAngebotsjahre;
        }

        public function execute($route) {
            if(!isset($_SESSION['PSBenutzer'])) {
                #$_GET['Home'] = 'anmelden';
                header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
                exit;        
            }
            if(!checkRights('Benutzer', $route['Action'])) {
                if($_SERVER['HTTP_HOST'] == "localhost") {
                    $location = "Location: index.php?Home=anzeigenMenue";
                } else {
                    $location = "Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anzeigenMenue";
                }
                header($location); /* Browser umleiten */
                exit;        
            }

            $action = $route['Action'];
            if(!isset($_SESSION['PSBenutzer'])) {
                #$_GET['Home'] = 'anmelden';
                header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
                exit;        
            }

            if (is_file("../MVC/Models/modelBenutzer/modelCollection_Benutzer.php")) {
                include_once "../MVC/Models/modelBenutzer/modelCollection_Benutzer.php";
            } else {
                echo "<br>Die Datei '../MVC/Models//modelBenutzer/modelCollection_Benutzer.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            #$this->modelCollection_Angebote = new modelCollection_Angebote($this->dbh);
            #$this->dataAngebotsjahre = $this->modelCollection_Angebotsjahre->getAngebotsjahre();
            #foreach($this->dataAngebotsjahre as $Jahr => $Attribute) {
            #    $this->dataAngebotsjahre[$Jahr]['Anzahl'] = $this->modelCollection_Angebote->vorhandenAngeboteVonJahr($Jahr);
            #}
            if (is_file("../MVC/Views/viewBenutzer/viewBenutzer.php")) {
                include_once "../MVC/Views/viewBenutzer/viewBenutzer.php";
            } else {
                echo "<br>Die Datei '../MVC/Views/viewAngebote/viewAngebotsjahre.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }

            //Action auswerten
            switch ($action) {
                case 'anzeigen':

                break;

                case 'Anbieter':
                    $this->modelCollection_Benutzer->loadBenutzer();
                    $this->dataCollection_Benutzer = $this->modelCollection_Benutzer->getAnbieter();
                    $this->viewBenutzer = new viewBenutzer();
                    $this->viewBenutzer->execute($action, $this->dataCollection_Benutzer, 0);

                break;
            }

        }

    }