<?php

# Collection_Angebote = array();
# Index PSJahr, Wert = Array mit modelAngebot-Objekten

class modelCollection_Angebote {
    private $dbh;
    private array $Collection_Angebote;

    public function __construct($dbh) {
        $this->dbh = $dbh;
        $this->Collection_Angebote = [];
        # include class modelAngebot
        if (is_file("../MVC/Models/modelAngebote/modelAngebot.php")) {
            include_once "../MVC/Models/modelAngebote/modelAngebot.php";
        } else {
            echo "<br>Die Datei '../MVC/Models/modelAngebote/modelAngebot.php' wurde nicht gefunden.";
            echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
            session_abort();
            die('<p>Programmabbruch!:</p>');
        }

    }

    public function loadCollection_Angebote() {
        $sql = "SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON AJ.PSJahr=A.PSJahr WHERE AJ.aktiv=0 AND A.PSJahr=AJ.PSJahr ORDER BY A.PSJahr, CONCAT(MID(A.Termin,4,2),'.',LEFT(A.Termin,2))";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebote["$obj->PSJahr"])) {
                    $this->Collection_Angebote["$obj->PSJahr"] = array();
                }
                #array_push($this->Collection_Angebote["$obj->PSJahr"], new modelAngebot($this->dbh, $obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
                array_push($this->Collection_Angebote["$obj->PSJahr"], new modelAngebot($obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
            }
        }
    }

    public function getCollectionAngebote() {
        return $this->Collection_Angebote;
    }

    public function loadJahrAngebote($Jahr) {
        $sql = "SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON AJ.PSJahr=A.PSJahr WHERE AJ.aktiv=0 AND A.PSJahr=$Jahr AND A.PSJahr=AJ.PSJahr ORDER BY A.PSJahr, CONCAT(MID(A.Termin,4,2),'.',LEFT(A.Termin,2))";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebote["$obj->PSJahr"])) {
                    $this->Collection_Angebote["$obj->PSJahr"] = array();
                }
                array_push($this->Collection_Angebote["$obj->PSJahr"], new modelAngebot($obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
            }
        }
#echo "<br>modelCollection_Angebote->loadJahrAngebote = "; print_r($this->Collection_Angebote);
    }

    public function loadJahrEigeneAngebote($Jahr) {
        $sql = "SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON AJ.PSJahr=A.PSJahr WHERE AJ.aktiv=0 AND A.PSJahr=$Jahr AND A.PSJahr=AJ.PSJahr AND A.PSBenutzer=".$_SESSION['PSBenutzer']." ORDER BY A.PSJahr, CONCAT(MID(A.Termin,4,2),'.',LEFT(A.Termin,2))";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebote["$obj->PSJahr"])) {
                    $this->Collection_Angebote["$obj->PSJahr"] = array();
                }
                array_push($this->Collection_Angebote["$obj->PSJahr"], new modelAngebot($obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
            }
        }        
    }

    public function loadJahrEigeneAngeboteForce($Jahr) {
        $sql = "SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON AJ.PSJahr=A.PSJahr WHERE A.PSJahr=$Jahr AND A.PSJahr=AJ.PSJahr AND A.PSBenutzer=".$_SESSION['PSBenutzer']." ORDER BY A.PSJahr, CONCAT(MID(A.Termin,4,2),'.',LEFT(A.Termin,2))";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebote["$obj->PSJahr"])) {
                    $this->Collection_Angebote["$obj->PSJahr"] = array();
                }
                array_push($this->Collection_Angebote["$obj->PSJahr"], new modelAngebot($obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
            }
        }        
    }

    public function loadJahrEigensAngebot($Jahr, $PSAngebot) {
        $sql = "SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON AJ.PSJahr=A.PSJahr WHERE AJ.aktiv=0 AND A.PSJahr=$Jahr AND A.PSJahr=AJ.PSJahr AND A.PSAngebot=$PSAngebot AND A.PSBenutzer=".$_SESSION['PSBenutzer']." ORDER BY A.PSJahr, CONCAT(MID(A.Termin,4,2),'.',LEFT(A.Termin,2))";
#echo "<br>sql = $sql";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebote["$obj->PSJahr"])) {
                    $this->Collection_Angebote["$obj->PSJahr"] = array();
                }
                array_push($this->Collection_Angebote["$obj->PSJahr"], new modelAngebot($obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
            }
        }        
    }

    public function loadAlleEigenenAngebote() {
        $sql = "SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON A.PSJahr=AJ.PSJahr WHERE AJ.aktiv=0 AND A.PSBenutzer=2 ORDER BY A.PSAngebot DESC";
#echo "<br>sql = $sql";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebote["$obj->PSAngebot"])) {
                    $this->Collection_Angebote["$obj->PSAngebot"] = array();
                }
                array_push($this->Collection_Angebote["$obj->PSAngebot"], new modelAngebot($obj->PSJahr, $obj->PSAngebot, $obj->PSBenutzer, $obj->Titel, $obj->Untertitel, $obj->Ort, $obj->Termin, $obj->Zeit, $obj->Teilnehmerzahl, $obj->Kosten_Mitglied, $obj->Kosten, $obj->Sternchen, $obj->Anmeldeschluss, $obj->Beschreibung, $obj->Web_Info, $obj->mehrereEinheiten));
            }   
        }
#print_r($this->Collection_Angebote); echo "<br>";        
    }

    public function getAngeboteVonJahr($PSJahr) {
        $_SESSION['PSJahr'] = $PSJahr;
        # Benutzerdaten bereitstellen
        include_once "../MVC/Models/modelBenutzer/modelCollection_Benutzer.php";
        $Collection_Benutzer = new modelCollection_Benutzer($this->dbh);
        $Collection_Benutzer->loadBenutzer();
        # Array für Daten initialisieren
        $dataAngebotsjahre = array();
        # $dataAngebotsjahree[PSJahr] = Array([PSAngebot] = Array ...[eintragen], ...[Beginn], ...[Ende], ...[aktiv]))
        # nicht vorhanden []
        $i = 0;
#echo "<br>Anzahl Angebote für Jahr = "; echo count($this->Collection_Angebote);
        foreach($this->Collection_Angebote as $Jahr => $Angebote) {
            foreach($Angebote as $Index => $Angebot) {
            #if($Jahr == $PSJahr) {
#echo "<br>foreach(Angebote - Angebot = "; print_r($Angebot);
                $Benutzer = $Collection_Benutzer->getBenutzerDataByPS($Angebot->getPSBenutzer());
#echo "<br>Benutzer = "; print_r($Benutzer);
                $Name = $Benutzer->getVorname()." ".$Benutzer->getNachname();
                $eMail = $Benutzer->geteMail();
#echo "<br>Name = $Name - eMail = $eMail";
                $PSAngebot = $Angebot->getPSAngebot();
                $dataAngebotsjahre["$i"] = array("$PSJahr"=>array('Benutzer'=>$Name,
                                                                            'eMail'=>$eMail,
                                                                            'PSJahr'=>$PSJahr,
                                                                            'Titel'=>$Angebot->getTitel(),
                                                                            'Untertitel'=>$Angebot->getUntertitel(),
                                                                            'Ort'=>$Angebot->getOrt(),
                                                                            'Termin'=>$Angebot->getTermin(),
                                                                            'Zeit'=>$Angebot->getZeit(),
                                                                            'Teilnehmerzahl'=>$Angebot->getTeilnehmerzahl(),
                                                                            'Kosten_Mitglied'=>$Angebot->getKosten_Mitglied(),
                                                                            'Kosten'=>$Angebot->getKosten(),
                                                                            'Sternchen'=>$Angebot->getSternchen(),
                                                                            'Anmeldeschluss'=>$Angebot->getAnmeldeschluss(),
                                                                            'Beschreibung'=>$Angebot->getBeschreibung(),
                                                                            'Web_Info'=>$Angebot->getWebInfo(),
                                                                            'mehrereEinheiten'=>$Angebot->getMehrereEinheiten()));
            #}
            $i++;
        }
        }
        return $dataAngebotsjahre;
    }

    public function getEigeneAngeboteVonJahr($PSJahr) {
        $_SESSION['PSJahr'] = $PSJahr;
        $dataAngebotsjahre = array();
        # $dataAngebotsjahree[PSJahr] = Array([PSAngebot] = Array ...[eintragen], ...[Beginn], ...[Ende], ...[aktiv]))
        # nicht vorhanden []
        $i = 0;
        if(!is_null($this->Collection_Angebote)) {
            foreach($this->Collection_Angebote as $Jahr => $Angebote) {
                foreach($Angebote as $Index => $Angebot) {
                    $Name = $_SESSION['Vorname']." ".$_SESSION['Nachname'];
                    $eMail = $_SESSION['eMail'];
                    $PSAngebot = $Angebot->getPSAngebot();
                    $dataAngebotsjahre["$i"] = array("$PSJahr"=>array('PSBenutzer'=>$Angebot->getPSBenutzer(),
                                                                                'PSAngebot'=>$Angebot->getPSAngebot(),
                                                                                'Benutzer'=>$Name,
                                                                                'eMail'=>$eMail,
                                                                                'PSJahr'=>$PSJahr,
                                                                                'Titel'=>$Angebot->getTitel(),
                                                                                'Untertitel'=>$Angebot->getUntertitel(),
                                                                                'Ort'=>$Angebot->getOrt(),
                                                                                'Termin'=>$Angebot->getTermin(),
                                                                                'Zeit'=>$Angebot->getZeit(),
                                                                                'Teilnehmerzahl'=>$Angebot->getTeilnehmerzahl(),
                                                                                'Kosten_Mitglied'=>$Angebot->getKosten_Mitglied(),
                                                                                'Kosten'=>$Angebot->getKosten(),
                                                                                'Sternchen'=>$Angebot->getSternchen(),
                                                                                'Anmeldeschluss'=>$Angebot->getAnmeldeschluss(),
                                                                                'Beschreibung'=>$Angebot->getBeschreibung(),
                                                                                'Web_Info'=>$Angebot->getWebInfo(),
                                                                                'mehrereEinheiten'=>$Angebot->getMehrereEinheiten()));
                #}
                    $i++;
                }
            }
        } else {
            $dataAngebotsjahre = [];
        }
        return $dataAngebotsjahre;
    }

    public function getEigenesAngebotVonJahr($PSJahr, $PSAngebot) {
        $_SESSION['PSJahr'] = $PSJahr;
        $dataAngebotsjahre = array();
        # $dataAngebotsjahree[PSJahr] = Array([PSAngebot] = Array ...[eintragen], ...[Beginn], ...[Ende], ...[aktiv]))
        # nicht vorhanden []
        $i = 0;
        if(!is_null($this->Collection_Angebote)) {
            foreach($this->Collection_Angebote as $Jahr => $Angebote) {
                if($Jahr == $PSJahr) {
#echo "<br>modelCollection_Angebote->getEigenesAngebotVonJahr - Jahr = $Jahr - Angebot = "; var_dump($Angebote);
                    foreach($Angebote as $Index => $Angebot) {
                        if($PSAngebot == $Angebot->getPSAngebot()) {
                            $Name = $_SESSION['Vorname']." ".$_SESSION['Nachname'];
                            $eMail = $_SESSION['eMail'];
                            $PSAngebot = $Angebot->getPSAngebot();
                            $dataAngebotsjahre["$i"] = array("$PSJahr"=>array('PSBenutzer'=>$Angebot->getPSBenutzer(),
                                                                                        'PSAngebot'=>$Angebot->getPSAngebot(),
                                                                                        'Benutzer'=>$Name,
                                                                                        'eMail'=>$eMail,
                                                                                        'PSJahr'=>$PSJahr,
                                                                                        'Titel'=>$Angebot->getTitel(),
                                                                                        'Untertitel'=>$Angebot->getUntertitel(),
                                                                                        'Ort'=>$Angebot->getOrt(),
                                                                                        'Termin'=>$Angebot->getTermin(),
                                                                                        'Zeit'=>$Angebot->getZeit(),
                                                                                        'Teilnehmerzahl'=>$Angebot->getTeilnehmerzahl(),
                                                                                        'Kosten_Mitglied'=>$Angebot->getKosten_Mitglied(),
                                                                                        'Kosten'=>$Angebot->getKosten(),
                                                                                        'Sternchen'=>$Angebot->getSternchen(),
                                                                                        'Anmeldeschluss'=>$Angebot->getAnmeldeschluss(),
                                                                                        'Beschreibung'=>$Angebot->getBeschreibung(),
                                                                                        'Web_Info'=>$Angebot->getWebInfo(),
                                                                                        'mehrereEinheiten'=>$Angebot->getMehrereEinheiten()));
                            $i++;
                        }
                    }
                }
            }
        } else {
            $dataAngebotsjahre = [];
        }
        return $dataAngebotsjahre;

    }


    public function vorhandenAngeboteVonJahr($PSJahr) {
        $sql = "SELECT count(PSAngebot) AS AnzahlAngebote FROM `angebote` WHERE `PSJahr`=$PSJahr";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                $AnzahlAngebote = $obj->AnzahlAngebote;
                break;
        }
        return $AnzahlAngebote;
        }
    }

    public function getCollection_Angebote() {
        return $this->Collection_Angebote;
    }

    public function getNeustesJahr() {
        $sql = "SELECT MAX(PSJahr) AS neustesJahr FROM `angebotsjahre` WHERE `eintragen`= 0";
        if(is_object(($result = $this->dbh->query($sql)))) {
            $obj = $result->fetch_object();
            return $obj->neustesJahr;
        }
    }

}

# SELECT COUNT(`PSJahr`), PSJahr FROM `angebote` WHERE 1 GROUP BY `PSJahr`

# SELECT * FROM angebote AS A LEFT JOIN angebotsjahre AS AJ ON AJ.PSJahr=A.PSJahr WHERE AJ.aktiv=0 AND A.PSJahr=AJ.PSJahr ORDER BY A.PSJahr, CONCAT(MID(A.Termin,4,2),'.',LEFT(A.Termin,2))
