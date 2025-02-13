<?php

class modelCollection_Benutzer {
    private array $Collection_Benutzer;
    private $dbh;
    private $dataHome;

    public function __construct($dbh) {
        $this->Collection_Benutzer = array();
        $this->dbh = $dbh;
        $this->dataHome = array();
        #echo "<br>Collection_Benutzer::dbh "; var_dump($this->dbh);
        include_once "../MVC/Models/modelBenutzer/modelBenutzer.php";
    }

    public function addBenutzer($PSBenutzer, modelBenutzer $Benutzer,) {
        $Benutzer->loadRechte($this->dbh, $PSBenutzer);
        #echo "<br>modelCollection_Benutzer->addBenutzer: $PSBenutzer -> "; var_dump($Benutzer);
        if(!isset($this->Collection_Benutzer["$PSBenutzer"])) {
            $this->Collection_Benutzer["$PSBenutzer"] = array();
        }
        array_push($this->Collection_Benutzer["$PSBenutzer"], $Benutzer);
    }

    public function getAllBenutzer() {
        # return alle Benutzer in eiem Array
    }

    public function getBenutzer($PSBenutzer) {
        #return Benutzer als Objekt
        return $this->Collection_Benutzer["$PSBenutzer"];
    } 

    public function countBenutzer() {
        return count($this->Collection_Benutzer);
    }

    public function loadBenutzer() {
        #echo "<br>Collection_Benutzer::loadBenutzer";
        # Benutzer aus DB laden
        $sql = "SELECT * FROM benutzer WHERE aktiv=0 ORDER BY Nachname, Vorname";
        if(is_object($result = $this->dbh->query($sql))) {
            #echo "<br>Collection_Benutzer::Benutzer-Daten vorhanden";
            while($obj = $result->fetch_object()) {
                #echo "<br>modelCollection_Benutzer::loadBenutzer = "; echo "$obj->PSBenutzer , $obj->Vorname , $obj->Nachname";
                $this->addBenutzer($obj->PSBenutzer, new modelBenutzer($obj->PSBenutzer, $obj->Vorname, $obj->Nachname, $obj->eMail, $obj->Mitgliedsnummer, $obj->PersonasID, $obj->aktiv, $obj->DAVPasswort, $this->dbh));
            }
        }

    }

    public function loadBenutzerByPS($PSBenutzer) {
        #echo "<br>Collection_Benutzer::loadBenutzer";
        # Benutzer aus DB laden
        $sql = "SELECT * FROM benutzer WHERE aktiv=0 AND PSBenutzer=$PSBenutzer";
        if(is_object($result = $this->dbh->query($sql))) {
            #echo "<br>Collection_Benutzer::Benutzer-Daten vorhanden";
            while($obj = $result->fetch_object()) {
                #echo "<br>modelCollection_Benutzer::loadBenutzer = "; echo "$obj->PSBenutzer , $obj->Vorname , $obj->Nachname";
                $this->addBenutzer($obj->PSBenutzer, new modelBenutzer($obj->PSBenutzer, $obj->Vorname, $obj->Nachname, $obj->eMail, $obj->Mitgliedsnummer, $obj->PersonasID, $obj->aktiv, $obj->DAVPasswort, $this->dbh));
            }
        }
        #echo "<br>modelCollection_Benutzer->loadBenutzerByPS = "; print_r($this->Collection_Benutzer);

    }

    #public function loadRechte

    public function getBenutzerData() {
            foreach($this->Collection_Benutzer as $Key => $Benutzer) {
                /*if(is_object($Benutzer[0])) {
                    echo "<br>Benutzer[0] ist ein Objekt";
                } else {
                    echo "<br>Benutzer[0] ist KEIN Objekt";
                }*/
                #echo "<br>Key = $Key - Benutzer[0] = "; print_r($Benutzer[0]);
                $PSB = $Benutzer[0]->getPSBenutzer();
                if(!isset($this->dataHome["$PSB"])) {
                    $this->dataHome["$PSB"] = array();
                }
                #echo "<br>modelCollection_Benutzer->getBenutzerData = "; var_dump($Benutzer);
                $this->dataHome["$PSB"]['PSBenutzer'] = $PSB;
                $this->dataHome["$PSB"]['Vorname'] = $Benutzer[0]->getVorname();
                $this->dataHome["$PSB"]['Nachname'] = $Benutzer[0]->getNachname();
                $this->dataHome["$PSB"]['eMail'] = $Benutzer[0]->geteMail();
                $this->dataHome["$PSB"]['Mitgliedsnummer'] = $Benutzer[0]->getMitgliedsnummer();
                $this->dataHome["$PSB"]['PersonasID'] = $Benutzer[0]->getPersonasID();
                $this->dataHome["$PSB"]['DAVPasswort'] = $Benutzer[0]->getDAVPasswort();

            }
#echo "<br>Collection_Benutzer::dataHome[] = "; print_r($this->dataHome);

            return $this->dataHome;
   
    }

    public function getBenutzerDataByPS($PSBenutzer) {
        foreach($this->Collection_Benutzer as $Key => $Benutzer) {
            if(is_object($Benutzer[0]) && $Benutzer[0]->getPSBenutzer()==$PSBenutzer) {
                #echo "<br>modelCollection_Benutzer->getBenutzerDataByPS Benutzer[0] ist ein Objekt";
                #echo "<br>Key = $Key - Benutzer[0] = "; print_r($Benutzer[0]);
                return $Benutzer[0];
                break;
            } /*else {
                #echo "<br>Benutzer[0] ist KEIN Objekt";
                return NULL;
            } */

        }
        return NULL;
    }
        #echo "<br>Collection_Benutzer::dataHome[] = "; var_dump($this->dataHome);

        public function getAnbieter() {
            $Anbieter = array();
            foreach($this->Collection_Benutzer as $PSBenutzer => $Benutzer) {
                if($Benutzer[0]->getAktiv() == 0 && $Benutzer[0]->getPersonasID() > 0) {
                    $Anbieter[$PSBenutzer] = array("Vorname" => $Benutzer[0]->getVorname(), "Nachname" => $Benutzer[0]->getNachname(), "eMail" => $Benutzer[0]->geteMail());
                }
        }
#echo "<br>#### #### modelCollection_Benutzer->getAnbieter - Anbieter = "; print_r($Anbieter);

            return $Anbieter;
        }
}
