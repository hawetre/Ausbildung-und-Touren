<?php

    class modelBenutzer {
        private int $PSBenutzer;
        private string $Vorname;
        private string $Nachname;
        private string $eMail;
        private string $Mitgliedsnummer;
        private string $PersonasID;
        private int $aktiv;
        private string $DAVPasswort;
        private array $modelRechte;
        private $dbh;
        private modelCollection_Rechte $Collection_Rechte;

        public function __construct($PSBenutzer, $Vorname, $Nachname, $eMail, $Mitgliedsnummer, $PersonasID, $aktiv, $DAVPasswort, $dbh) {
            $this->PSBenutzer = $PSBenutzer;
            $this->Vorname = $Vorname;
            $this->Nachname = $Nachname;
            $this->eMail = $eMail;
            $this->Mitgliedsnummer = $Mitgliedsnummer;
            $this->PersonasID = $PersonasID;
            $this->aktiv = $aktiv;
            $this->DAVPasswort = $DAVPasswort;
            $this->modelRechte = array();
            $this->dbh = $dbh;
            #echo "<br>modelBenutzer::_construct = PSBenutzer $this->PSBenutzer";
            # Rechte laden
            if (is_file("../MVC/Models/modelRechte/modelCollection_Rechte.php")) {
                include_once "../MVC/Models/modelRechte/modelCollection_Rechte.php";
            } else {
                echo "<br>Die Datei '../MVC/Models/modelRechte/modelCollection_Rechte.php' wurde nicht gefunden.";
                echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                session_abort();
                die('<p>Programmabbruch!:</p>');   
            }
            $this->Collection_Rechte = new modelCollection_Rechte($this->dbh, $PSBenutzer);
            $this->Collection_Rechte->loadRechte($this->PSBenutzer);
        }

        public function setPSBenutzer($PSBenutzer) {
            $this->PSBenutzer = $PSBenutzer;
        }

        public function setVorname($Vorname) {
            $this->Vorname = $Vorname;
        }

        public function setNachname($Nachname) {
            $this->Nachname = $Nachname;
        }

        public function seteMail($eMail) {
            $this->eMail = $eMail;
        }

        public function setMitgliedsnummer($Mitgliedsnummer) {
            $this->Mitgliedsnummer = $Mitgliedsnummer;
        }

        public function setPersonasID($PersonasID) {
            $this->PersonasID = $PersonasID;
        }

        public function setAktiv($aktiv) {
            $this->aktiv = $aktiv;
        }

        public function setDAVPasswort($DAVPasswort) {
            $this->DAVPasswort = $DAVPasswort;
        }

        public function getPSBenutzer() {
            return $this->PSBenutzer;
        }

        public function getVorname() {
            return $this->Vorname;
        }

        public function getNachname() {
            return $this->Nachname;
        }

        public function geteMail() {
            return $this->eMail;
        }

        public function getMitgliedsnummer() {
            return $this->Mitgliedsnummer;
        }

        public function getPersonasID() {
            return $this->PersonasID;
        }

        public function getAktiv() {
            return $this->aktiv;
        }

        public function getDAVPasswort() {
            return $this->DAVPasswort;
        }

        public function getMenue() {
            $dataHome = [];
            $dataRechte = $this->Collection_Rechte->getCollection_Rechte();
            #echo "<br><br>modelBenutzer->getMenue - this->Collection_Rechte = "; print_r($this->Collection_Rechte);
            #echo "<br><br>modelBenutzer - count(this->dataRechte = "; count($dataRechte);
            foreach($dataRechte as $Key => $arrayRechte) {
                if(!isset($dataHome[$Key])) {
                    $dataHome[$Key] = array();
                }
                #echo "<br>Key = $Key - arrayRechte = "; print_r($arrayRechte);
                #if(is_array($arrayRechte)) { echo "<br><br>modelBenutzer->getMenue  -  arrayRechte ist ein Array"; print_r($arrayRechte);}
                foreach($arrayRechte as $id => $Recht) {
                    #echo "<br><br>modelBenutzer->getMenue  - Recht = "; print_r($Recht);
#                    array_push($dataHome[$Key], array('Menueanzeige' => $Recht->getMenueanzeige, 'Funktionsanzeige' => $Recht->getFunktionsanzeige()));
                    array_push($dataHome[$Key], array('Controler' => $Recht->getControler(), 'Action' => $Recht->getAction(), 'Funktion' => $Recht->getFunktion(), 'Funktionsanzeige' => $Recht->getFunktionsanzeige()));
                }
                #echo "<br><br>modelBenutzer->getMenue - Key = $Key  -  Hauptfunbktion = $Recht->Hauptfunktion  -  Funktion = $Recht->Funktion";
            }
            return $dataHome;
        }

        public function loadRechte($dbh, $PSBenutzer) {
            $sql = 'SELECT H.PSHM,H.Hauptfunktion,H.Menueanzeige,H.Controler,U.PSHM,U.PSUM,U.Funktion,U.Action,U.Funktionsanzeige FROM hauptmenue AS H, untermenue AS U ';
            $sql .= 'LEFT JOIN  benutzer_rechte AS BR ON BR.PSBenutzer='.$PSBenutzer.' AND BR.PSHM=U.PSHM AND BR.PSUM=U.PSUM ';
            $sql .= 'WHERE H.aktiv=0 AND U.aktiv=0 AND H.PSHM=U.PSHM';    // Menue für Benutzer ermiotteln
            if(is_object(($result = $this->dbh->query($sql)))) {

                while($obj = $result->fetch_object()) {
                    if(!isset($this->modelRechte["$obj->Hauptfunktion"])) {
                        $this->modelRechte["$obj->Hauptfunktion"] = array();
                    }
                    array_push($this->modelRechte[$obj->Hauptfunktion], new modelRecht($PSBenutzer, $obj->Hauptfunktion, $obj->Menueanzeige, $obj->Controler, $obj->Funktion, $obj->Action, $obj->Funktionsanzeige));
                }
            }

        }

    }