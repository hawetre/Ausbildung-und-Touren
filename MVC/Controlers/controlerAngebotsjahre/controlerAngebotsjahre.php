<?php

    class controlerAngebotsjahre {
        private $dbh;
        private viewAngebotsjahre $viewAngebotsjahre;
        private modelCollection_Angebotsjahre $modelCollection_Angebotsjahre;
        private modelCollection_Angebote $modelCollection_Angebote;
        private array $dataCollection_Angebote;
        private array $Angebotsjahre;
        private $dataAngebotsjahre;
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
            $this->dataAngebotsjahre = $this->modelCollection_Angebotsjahre->getAngebotsjahre();
            $_SESSION['dataAngebotsjahre'] = $this->dataAngebotsjahre;
        }

        public function execute($route) {
#echo "controlerAngebotsjahre->execute - _SESSION = "; print_r($_SESSION); echo "<br>";
            if(!isset($_SESSION['PSBenutzer'])) {
                #$_GET['Home'] = 'anmelden';
                header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
                exit;        
            }
            if(!checkRights('Angebotsjahre', $route['Action'])) {
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
            #$this->dataAngebotsjahre = $this->modelCollection_Angebotsjahre->getAngebotsjahre();
            foreach($this->dataAngebotsjahre as $Jahr => $Attribute) {
                $this->dataAngebotsjahre[$Jahr]['Anzahl'] = $this->modelCollection_Angebote->vorhandenAngeboteVonJahr($Jahr);
            }
            if (is_file("../MVC/Views/viewAngebotsjahre/viewAngebotsjahre.php")) {
                include_once "../MVC/Views/viewAngebotsjahre/viewAngebotsjahre.php";
            } else {
                echo "<br>Die Datei '../MVC/Views/viewAngebote/viewAngebotsjahre.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            //Action auswerten
            switch ($action) {
                case 'anzeigen':
                    # Jahre prüfen, ob Angebote vorhanden sind - modelCollection_Jahre
                    # Daten für Select-Box für Jahre mit modelCollection_Jahre erstellen
                    #$Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadCollection_Angebote();
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getCollectionAngebote();
                    $this->viewAngebotsjahre = new viewAngebotsjahre();
                    $this->viewAngebotsjahre->execute($action, $this->dataAngebotsjahre, 0);

                    break;

                case 'bearbeiten':
                    #$Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrEigeneAngebote($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getEigeneAngeboteVonJahr($Jahr);
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataCollection_Angebote, 0);

                    break;

                case 'neu':

                    break;

                case 'kopieren':

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