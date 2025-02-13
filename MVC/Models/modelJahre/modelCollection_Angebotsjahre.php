<?php

class modelCollection_Angebotsjahre {
    private $dbh;
    private array $Collection_Angebotsjahre;
    # Collection_Angebotsjahre[PSJahr] = Array mit Objekten modelAngebotsjahr

    public function __construct($dbh) {
        $this->dbh = $dbh;
        # include php-Datei der Klasse modelAngebotsjahr.php
        if (is_file("../MVC/Models/modelJahre/modelAngebotsjahr.php")) {
            include_once "../MVC/Models/modelJahre/modelAngebotsjahr.php";
        } else {
            echo "<br>Die Datei '../MVC/Models/modelJahre/modelAngebotsjahr.php' wurde nicht gefunden.";
            echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
            session_abort();
            die('<p>Programmabbruch!:</p>');
        }
    }

    public function loadAngebotsjahre() {
        $sql = "SELECT * FROM `angebotsjahre` WHERE aktiv=0 ORDER BY PSJahr DESC";
        if(is_object(($result = $this->dbh->query($sql)))) {
            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Angebotsjahre["$obj->PSJahr"])) {
                    $this->Collection_Angebotsjahre["$obj->PSJahr"] = array();
                }
                array_push($this->Collection_Angebotsjahre["$obj->PSJahr"], new modelAngebotsjahr($this->dbh, $obj->PSJahr, $obj->eintragen, $obj->Beginn, $obj->Ende, $obj->aktiv));
            }
        }
    }

    public function getAngebotsjahre() {   # !!!!! Angebotsjahre - !!nicht!! Angebote
        $dataAngebotsjaahre = [];
        # $dataAngebotsjahree[PSJahr] = Array ...[eintragen], ...[Beginn], ...[Ende], ...[aktiv]
        # nicht vorhanden []
        foreach($this->Collection_Angebotsjahre as $Angebotsjahr => $Jahr) {
            $dataAngebotsjahre[$Angebotsjahr] = array('PSJahr' => $Jahr[0]->getPSJahr(),
                                                  'eintragen' => $Jahr[0]->getEintragen(),
                                                  'Beginn' => $Jahr[0]->getBeginn(),
                                                  'Ende' => $Jahr[0]->getEnde(),
                                                  'aktiv' => $Jahr[0]->getAktiv(),
                                                  'Anzahl' => "");
        }
#echo "<br>dataAngebotsjahre = "; print_r($dataAngebotsjahre); echo "<br>";
        return $dataAngebotsjahre;
    }

    public function getEintragenJahr() {
        $returnJahr = -1;
        foreach($this->Collection_Angebotsjahre as $Angebotsjahr => $Jahr) {
            if($Jahr[0]->getEintragen() == 0 && $Jahr[0]->getAktiv() == 0) {
                $returnJahr = $Jahr[0]->getPSJahr();
            }
        }
#echo "<br>#### modelCollection_Angebotsjahre->getEintragenJahr - Jahr = "; print_r($returnJahr);
        return $returnJahr;
    }
}