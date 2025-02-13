<?php

    class controlerEigeneAngebote {
        private $dbh;
        private viewEigeneAngebote $viewEigeneAngebote;
        private modelCollection_Angebotsjahre $modelCollection_Angebotsjahre;
        private modelCollection_Angebote $modelCollection_Angebote;
        private modelAngebot $modelAngebot;
        private array $dataCollection_Angebote;
        private array $Angebotsjahre;
        private array $dataangebot;
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
            if(!isset($_SESSION['PSBenutzer'])) {
                #$_GET['Home'] = 'anmelden';
                header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
                exit;        
            }
#echo "<br>Controler=Eigeneangebote - Action="; echo $route['Action'];
            if(!checkRights('EigeneAngebote', $route['Action'])) {
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
            $_SESSION['dataAngebotsjahre'] = $dataAngebotsjahre;
            $this->Angebotsjahre = array();
            foreach($dataAngebotsjahre as $key => $arrayJahr) {
                if($this->modelCollection_Angebote->vorhandenAngeboteVonJahr($key) > 0) {
                    array_push($this->Angebotsjahre, $key);
                }
            }
            $_SESSION['Angebotsjahre'] = $this->Angebotsjahre;
#echo "<br>#### controlerEigeneAngebote _SESSION[Angebotsjahre] = "; print_r($_SESSION['Angebotsjahre']);
            if (is_file("../MVC/Views/viewEigeneAngebote/viewEigeneAngebote.php")) {
                include_once "../MVC/Views/viewEigeneAngebote/viewEigeneAngebote.php";
            } else {
                echo "<br>Die Datei '../MVC/Views/viewAngebote/viewEigeneAngebote.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');
            }
            //Action auswerten
            switch ($action) {
                case 'anzeigen':
#echo "<br>++++ controlerEigeneAngebote=anzeigen ++++";
                    # Jahre prüfen, ob Angebote vorhanden sind - modelCollection_Jahre
                    # Daten für Select-Box für Jahre mit modelCollection_Jahre erstellen
                    $Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrEigeneAngebote($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getEigeneAngeboteVonJahr($Jahr);
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataCollection_Angebote, 0);


                    break;

                case 'bearbeiten':
#echo "<br>++++ controlerEigeneAngebote=bearbeiten ++++";
                    $Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrEigeneAngebote($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getEigeneAngeboteVonJahr($Jahr);
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataCollection_Angebote, 0);

                    break;

                case 'aendern':
#echo "<br>++++ controlerEigeneAngebote=aendern ++++";
                    $Jahr = $this->getsetAngebotsjahr();
                    $_SESSION['EigenesAngebot'] = array('PSJahr' => $_POST['PSJahr'], 'PSBenutzer' => $_POST['PSBenutzer'], 'PSAngebot' => $_POST['PSAngebot']);
                    $this->modelCollection_Angebote->loadJahrEigensAngebot($Jahr, $_POST['PSAngebot']);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getEigenesAngebotVonJahr($Jahr, $_POST['PSAngebot']);
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataCollection_Angebote, 0);

                    break;

                case 'aendern_speichern':
#echo "<br>++++ controlerEigeneAngebote=aendern_speichern ++++";
                    if (is_file("../MVC/Models/modelAngebote/modelAngebot.php")) {
                        include_once "../MVC/Models/modelAngebote/modelAngebot.php";
                    } else {
                        echo "<br>Die Datei '../MVC/Models//modelAngebot.php' wurde nicht gefunden.";
                        echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                        session_abort();
                        die('<p>Programmabbruch!:</p>');
                    }
                    $this->modelAngebot = new modelAngebot(0, 0, 0, "", "", "", "", "", "", "", "", "", "", "", "", -1);
                    $this->modelAngebot->setPostData();
                    if($this->modelAngebot->geaendertPostSessionData() > 0) {
                        $this->dataAngebot = $this->modelAngebot->getDataAngebot();
                        $returnValue = $this->modelAngebot->checkDataValues();
                        $this->dataAngebot['EigenesAngebot']['checkDataValue'] = $returnValue['bitStructAngebot'];
                        if($returnValue['countErrors'] == 0) {
                            $this->modelAngebot->updateData($this->dbh);
                            #$this->dataAngebot['EigenesAngebot']['aendern_speichern'] = "Die geänderten Daten wurden gespeichert!";
                            $action = 'aendern_gespeichert';
                        }
                        } else {
                            $this->dataAngebot['EigenesAngebot'] = $_SESSION['EigenesAngebot'];
                            $returnValue = $this->modelAngebot->checkDataValues();
                            $this->dataAngebot['EigenesAngebot']['checkDataValue'] = $returnValue['bitStructAngebot'];
                            #$this->dataAngebot['EigenesAngebot']['aendern_speichern'] = "Es wurden keine Daten geändert!";
                            #$action = 'aendern';
                        }
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataAngebot, 0);

                    break;

                case 'neu':
#echo "<br>++++ controlerEigeneAngebote=neu ++++";
                    if (is_file("../MVC/Models/modelJahre/modelCollection_Angebotsjahre.php")) {
                        include_once "../MVC/Models/modelJahre/modelCollection_Angebotsjahre.php";
                    }
                    if (is_file("../MVC/Models/modelAngebote/modelAngebot.php")) {
                        include_once "../MVC/Models/modelAngebote/modelAngebot.php";
                    }
#echo "<br>"; print_r($this->modelCollection_Angebote); echo "<br>";                    
                    $this->AngebotsJahre = new modelCollection_Angebotsjahre($this->dbh);
                    $this->AngebotsJahre->loadAngebotsjahre();
                    if(($Jahr = $this->AngebotsJahre->getEintragenJahr()) < 0) {
                        $error = -1;
                        $this->dataAngebot = array();
                        $this->viewEigeneAngebote = new viewEigeneAngebote();
                        $this->viewEigeneAngebote->execute($action, $this->dataAngebot, $error);
                    } else {
                        $_SESSION['Angebotsjahr'] = $Jahr;
                        $error = 0;
#echo "<br>#### controlerEigenesAngebot-neu - Jahr = "; print_r($Jahr);
                        $this->modelAngebot = new modelAngebot($Jahr, 0, $_SESSION['PSBenutzer'], "", "", "", "", "", "", "", "", "", "", "", "", -1);
                        $_SESSION['NeuesAngebot'] = serialize($this->modelAngebot);
                        $this->viewEigeneAngebote = new viewEigeneAngebote();
                        $this->dataAngebot = $this->modelAngebot->getDataAngebot();
                        $this->viewEigeneAngebote->execute($action, $this->dataAngebot, $error);
                    }

                    break;

                case 'neu_speichern':
#echo "<br>++++ controlerEigeneAngebote=neu_speichern ++++";
                    if (is_file("../MVC/Models/modelAngebote/modelAngebot.php")) {
                        include_once "../MVC/Models/modelAngebote/modelAngebot.php";
                    } else {
                        echo "<br>Die Datei '../MVC/Models//modelAngebot.php' wurde nicht gefunden.";
                        echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                        session_abort();
                        die('<p>Programmabbruch!:</p>');
                    }
                    if(isset($_SESSION['NeuesAngebot'])) {
                        $this->modelAngebot = unserialize($_SESSION['NeuesAngebot']);
                    } else {
                        $this->modelAngebot = new modelAngebot(0, 0, 0, "", "", "", "", "", "", "", "", "", "", "", "", -1);
                    }
                    $this->modelAngebot->setPostData();
                    $this->dataAngebot = $this->modelAngebot->getDataAngebot();
                    $returnValue = $this->modelAngebot->checkDataValues();
                    $this->dataAngebot['EigenesAngebot']['checkDataValue'] = $returnValue;
                    if($returnValue['countErrors'] == 0) {
#echo "<br>Aufruf modelAngebot->saveData()<br>";
                        $this->modelAngebot->saveData($this->dbh);
                        $this->dataAngebot['EigenesAngebot']['aendern_speichern'] = "Die geänderten Daten wurden gespeichert!";
                        $action = 'neu_gespeichert';
                    } else {
                        #$this->dataAngebot['EigenesAngebot'] = $_SESSION['EigenesAngebot'];
                        $this->dataAngebot['EigenesAngebot']['aendern_speichern'] = "Es wurden keine Daten eingegeben!";
                        $action = 'neu_speichern';
                    }
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataAngebot, 0);

                break;


                case 'kopieren':
#echo "<br>#### controlerEigeneAngebote=kopieren ####";
                    $Jahr = $this->getsetAngebotsjahr();
                    $this->modelCollection_Angebote->loadJahrEigeneAngeboteForce($Jahr);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getEigeneAngeboteVonJahr($Jahr);
#echo "<br>dataCollection_Angebote = "; print_r($this->dataCollection_Angebote); echo "<br>";
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataCollection_Angebote, 0);

                break;

                case 'neu_kopie':
#echo "<br>++++ controlerEigeneAngebote=neu_kopie ++++";
                    $Jahr = $this->getsetAngebotsjahr();
                    $_SESSION['EigenesAngebot'] = array('PSJahr' => $_POST['PSJahr'], 'PSBenutzer' => $_POST['PSBenutzer'], 'PSAngebot' => $_POST['PSAngebot']);
                    $this->modelCollection_Angebote->loadJahrEigensAngebot($Jahr, $_POST['PSAngebot']);
                    $this->dataCollection_Angebote = $this->modelCollection_Angebote->getEigenesAngebotVonJahr($Jahr, $_POST['PSAngebot']);
                    $_SESSION['neustesJahr'] = $this->modelCollection_Angebote->getNeustesJahr();
                    $this->viewEigeneAngebote = new viewEigeneAngebote();
                    $this->viewEigeneAngebote->execute($action, $this->dataCollection_Angebote, 0);

                    break;
                                        
            }
         }

        private function getsetAngebotsjahr() {
#echo "<br>#### Angebotsjahre = "; print_r($this->Angebotsjahre);
#echo "<br>#### _POST = "; print_r($_POST);
#echo "<br>#### _SESSION = "; print_r($_SESSION);
            if(isset($_POST["PSJahr"])) {
                $Jahr = $_POST["PSJahr"];
                #$_POST["Angebotsjahr"] = $Jahr;
#echo "<br>#### getsetAngebot _POST[PSJahr] isset!";
            } elseif(isset($_POST["Angebotsjahr"])) {
                $Jahr = $_POST["Angebotsjahr"];
                #$_POST["PSJahr"] = $Jahr;
#echo "<br>#### getsetAngebot _POST[Angebotsjahr] isset!";
            } else {
                $Jahr = $_SESSION['Angebotsjahre'][0];
            #    $_POST["PSJahr"] = $Jahr;
#echo "<br>#### getsetAngebot else!";
            }
            #$_SESSION['Angebotsjahr'] = $Jahr;
#echo "<br>#### getsetAngebotsjahr = $Jahr";
            return $Jahr;
        }
    }