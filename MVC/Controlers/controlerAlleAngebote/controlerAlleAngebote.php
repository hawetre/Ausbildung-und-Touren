<?php

    class controlerAlleAngebote {
        private $dbh;
        private viewAlleAngebote $viewAlleAngebote;
        private modelCollection_Angebotsjahre $modelCollection_Angebotsjahre;
        private modelCollection_Angebote $modelCollection_Angebote;
        private array $dataCollection_Angebote;
        private array $Angebotsjahre;
        private string $webpage;
    
        public function __construct($dbh) {
            $this->dbh = $dbh;
            $this->dataCollection_Angebote = array();
            $this->webpage = "";
            include_once '../Core/Funktionen.php';
            if (is_file("../MVC/Models/modelJahre/modelCollection_Angebotsjahre.php")) {
                include_once "../MVC/Models/modelJahre/modelCollection_Angebotsjahre.php";
            } else {
                echo "<br>Die Datei '../MVC/Models/modelJahre/modelCollection_Angebotsjahre.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            $this->modelCollection_Angebotsjahre = new modelCollection_Angebotsjahre($this->dbh);
            $this->modelCollection_Angebotsjahre->loadAngebotsjahre();
        }

        public function execute($route) {
        #if(!isset($_SESSION['PSBenutzer'])) {
        #    #$_GET['Home'] = 'anmelden';
        #    header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
        #    exit;        
        #}
        if(!isset($_SESSION['PSBenutzer'])) {
            #$_GET['Home'] = 'anmelden';
            header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
            exit;        
        }
        if(!checkRights('AlleAngebote', $route['Action'])) {
            if($_SERVER['HTTP_HOST'] == "localhost") {
                $location = "Location: index.php?Home=anzeigenMenue";
            } else {
                $location = "Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anzeigenMenue";
            }
            header($location); /* Browser umleiten */
            exit;        
        }

        $action = $route['Action'];

            if (is_file("../MVC/Models/modelAngebote/modelCollection_Angebote.php")) {
                include_once "../MVC/Models/modelAngebote/modelCollection_Angebote.php";
            } else {
                echo "<br>Die Datei '../MVC/Models//modelAngebote.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            $this->modelCollection_Angebote = new modelCollection_Angebote($this->dbh);
            $dataAngebotsjahre = $this->modelCollection_Angebotsjahre->getAngebotsjahre();
            $this->Angebotsjahre = array();
            foreach($dataAngebotsjahre as $key => $arrayJahr) {
                if($this->modelCollection_Angebote->vorhandenAngeboteVonJahr($key) > 0) {
                    array_push($this->Angebotsjahre, $key);
                }
            }
            $_SESSION['Angebotsjahre'] = $this->Angebotsjahre;
            if (is_file("../MVC/Views/viewAlleAngebote/viewAlleAngebote.php")) {
                include_once "../MVC/Views/viewAlleAngebote/viewAlleAngebote.php";
            } else {
                echo "<br>Die Datei '../MVC/Views/viewAngebote/viewAlleAngebote.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            //Action auswerten
            switch ($action) {
                case 'anzeigen':
                    # Jahre prüfen, ob Angebote vorhanden sind - modelCollection_Jahre
                    # Daten für Select-Box für Jahre mit modelCollection_Jahre erstellen
                    $Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrAngebote($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getAngeboteVonJahr($Jahr);
                    $this->viewAlleAngebote = new viewAlleAngebote();
                    $this->viewAlleAngebote->execute($action, $this->dataCollection_Angebote, 0);

                    break;

                case 'csvExport':
                    # Jahre prüfen, ob Angebote vorhanden sind - modelCollection_Jahre
                    $Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrAngebote($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getAngeboteVonJahr($Jahr);
                    $this->viewAlleAngebote = new viewAlleAngebote();
                    $this->viewAlleAngebote->execute($action, $this->dataCollection_Angebote, 0);
                    # Select-Box für Jahre mit modelCollection_Jahre füllen
                    # Wenn Jahr eintragen=0 ist, dann die Angebote dieses Jahres in Kurzform anzeigen
                    # Wenn alle Jahre eintragen=-1, dann die Angebote der höchsten Jahreszahl in Kurzform anzeigen
                    # modelCollection_Angebote
                    # teilanzeigenAngeboteTemplate

                    break;

                case 'htmlExport':
#echo "<br>#### controlerAlleAngebote-htmlExport";
                    # Jahre prüfen, ob Angebote vorhanden sind - modelCollection_Jahre
                    $Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrAngebote($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getAngeboteVonJahr($Jahr);
                    $this->viewAlleAngebote = new viewAlleAngebote();
                    $this->viewAlleAngebote->execute($action, $this->dataCollection_Angebote, 0);
                    # Select-Box für Jahre mit modelCollection_Jahre füllen
                    # Wenn Jahr eintragen=0 ist, dann die Angebote dieses Jahres in Kurzform anzeigen
                    # Wenn alle Jahre eintragen=-1, dann die Angebote der höchsten Jahreszahl in Kurzform anzeigen
                    # modelCollection_Angebote
                    # teilanzeigenAngeboteTemplate

                    break;
            }
         }

        private function getsetAngebotsjahr() {
            if(isset($_POST["Angebotsjahr"])) {
                $Jahr = $_POST["Angebotsjahr"];
            } else {
                $Jahr = $this->Angebotsjahre[0];
                $_POST["Angebotsjahr"] = $Jahr;
            }
            $_SESSION['Angebotsjahr'] = $Jahr;
            return $Jahr;
        }
    }
