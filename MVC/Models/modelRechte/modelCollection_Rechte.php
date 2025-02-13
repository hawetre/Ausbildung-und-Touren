<?php

class modelCollection_Rechte {
    private array $Collection_Rechte;
    private $dbh;
    private $PSBenutzer;

    public function __construct($dbh, $PSBenutzer) {
        $this->Collection_Rechte = array();
        $this->dbh = $dbh;
        $this->PSBenutzer = $PSBenutzer;
        #echo "<br>modelCollection_Rechte::_construct this->PSBenutzer $this->PSBenutzer";
        #echo "<br>modelCollection_Benutzer::_construnct = PSBenutzer "; echo $PSBenutzer;
        if (is_file("../MVC/Models/modelRechte/modelRecht.php")) {
            include_once "../MVC/Models/modelRechte/modelRecht.php";
        } else {
            echo "<br>Die Datei '../MVC/Models/modelRechte/modelRecht.php' wurde nicht gefunden.";
            echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
            session_abort();
            die('<p>Programmabbruch!:</p>');   
        }
    }

    public function loadRechte($PSBenutzer) {
        $sql = 'SELECT H.PSHM,H.Hauptfunktion,H.Menueanzeige,H.Controler,U.PSHM,U.PSUM,U.Funktion,U.Action,U.Funktionsanzeige FROM hauptmenue AS H, untermenue AS U ';
        $sql .= 'RIGHT JOIN  benutzer_rechte AS BR ON BR.PSBenutzer='.$this->PSBenutzer.' AND BR.PSHM=U.PSHM AND BR.PSUM=U.PSUM ';
        $sql .= 'WHERE H.aktiv=0 AND U.aktiv=0 AND H.PSHM=U.PSHM';    // Menue für Benutzer ermiotteln
#echo "<br>modelCollection_Rechte::loadRechte->sql = "; echo $sql;
        if(is_object(($result = $this->dbh->query($sql)))) {

            while($obj = $result->fetch_object()) {
                if(!isset($this->Collection_Rechte["$obj->Hauptfunktion"])) {
                    $this->Collection_Rechte["$obj->Hauptfunktion"] = array();
                }
                #echo "<br>modelCollection_Rechte->loadRechte Rechte = "; print_r($obj);
                #echo "<br>"; print_r($obj);
                #echo "<br>obj->Hauptfunktion = "; echo $obj->Hauptfunktion;
                array_push($this->Collection_Rechte["$obj->Hauptfunktion"], new modelRecht($PSBenutzer, $obj->Hauptfunktion,$obj->Menueanzeige, $obj->Controler, $obj->Funktion, $obj->Action, $obj->Funktionsanzeige));
            }
        }
    }

    public function getCollection_Rechte() {
        return $this->Collection_Rechte;
    }

}