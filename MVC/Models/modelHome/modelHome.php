<?php

/**
 * class modelHome
 */

class modelHome {
    private $dbh;
    private array $dataHome;
    private modelBenutzer $Benutzer;
    private modelCollection_Benutzer $Collection_Benutzer;

    public function __construct($dbh) {
        $this->dbh = $dbh;
        $this->dataHome = [];
        #echo "<br>modelHome::dbh "; var_dump($this->dbh);
    }

    public function execute($action, $dataHome) {
        $this->dataHome = $dataHome;
        #echo "<br>modelHome->execute action = $action"; echo "  dataHome = "; print_r($dataHome);
        switch ($action) {
            case 'anmelden':
                #echo "<br>modelHome->execute Action = $action";
                #echo "<br>\tmodelHome::anmelden";
                #return $this->dataHome;
                break;

            case "checkAnmeldung":
                #echo "<br>modelHome->execute Action = $action";
                #echo "<br>\tmodelHome::checkAnmeldung";
                #include_once "../MVC/Models/modelBenutzer/modelCollection_Benutzer.php";
                if (is_file("../MVC/Models/modelBenutzer/modelCollection_Benutzer.php")) {
                    include_once "../MVC/Models/modelBenutzer/modelCollection_Benutzer.php";
                } else {
                    echo "<br>Die Datei '../MVC/Models/modelBenutzer/modelCollection_Benutzer.php' wurde nicht gefunden.";
                    echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                    session_abort();
                    die('<p>Programmabbruch!:</p>');   
                }
        #echo "<br>\tmodelHome::Collection_Benutzer instanzieren";
                $this->Collection_Benutzer = new modelCollection_Benutzer($this->dbh);
                #echo "<br>\tmodelHome::Collection_Benutzer loadBenutzer()";
                $this->Collection_Benutzer->loadBenutzer();
                #echo "<br>\tmodelHome::Collection_Benutzer Anzahl = "; echo $this->Collection_Benutzer->countBenutzer();
                #echo "<br>\tmodelHome::Collection_Benutzer getBenutzerData()";
                $this->dataHome = $this->Collection_Benutzer->getBenutzerData();
                #echo "<br>modelHome::dataHome[] = "; var_dump($this->dataHome);
                break;

            case "anzeigenMenue":
                #echo "<br>modelHome->execute Action = $action";
                #echo "<br>modelHome::anzeigenMenue _SESSION = "; print_r($_SESSION);
                # Menue-Struktur => Array ( [eigene_Angebote] => Array ( [0] => anzeigen [1] => ändern [2] => neu [3] => kopieren ) [alle_Angebote] => Array ( [0] => anzeigen [1] => csv-Export [2] => html-Export ) [Angebotsjahre] => Array ( [0] => anzeigen [1] => bearbeiten [2] => neu ) )
                if(isset($_SESSION['Menue']) && !empty($_SESSION['Menue'])) {
                    #echo "<br>modelHome->execute anzeigenMenue: _SESSION[Menue] existiert und ist nicht leer <br>"; print_r($_SESSION['Menue']);
                    $this->dataHome = $_SESSION['Menue'];
                } else {
                    #echo "<br>mmodelHome->execute anzeigenMenue: _SESSION[Menue] existiert nicht oder ist leer";
                    #echo "<br>\tmodelHome::anzeigenMenue";
                    include_once "../MVC/Models/modelBenutzer/modelCollection_Benutzer.php";
                    #echo "<br>\tmodelCollection_Benutzer instanzieren";
                    $this->Collection_Benutzer = new modelCollection_Benutzer($this->dbh);
                    #echo "<br>\tmodelCollection_Benutzer->loadBenutzerByPS(_SESSION['PSBenutzer'])";
                    $this->Collection_Benutzer->loadBenutzerByPS($_SESSION['PSBenutzer']);
                    $this->Benutzer = $this->Collection_Benutzer->getBenutzerDataByPS($_SESSION['PSBenutzer']);
                    #echo "<br>modelHome this->Benutzer = "; print_r($this->Benutzer);
                    $this->dataHome = $this->Benutzer->getMenue(); ###################################################
                    $_SESSION['Menue'] = $this->dataHome;
                    #echo "<br>modelHome::getMenue() = "; var_dump($this->dataHome);
                    #echo "<br>modelHome::_SESSION = "; var_dump($_SESSION);
                }
#echo "<br>dataHome = "; print_r($this->dataHome);
#echo "<br>_SESSION[Menue] = "; print_r($_SESSION['Menue']);
                break;
        }
        return $this->dataHome;
    }


    public function getBenutzerData($dbh) {
        $sql = "SELECT * FROM benutzer WHERE aktiv=0";
        if(is_object($result = $this->dbh->query($sql))) {
            #echo "<br>Benutzer-Daten vorhanden";
            while($obj = $result->fetch_object()) {
                $this->dataHome["$obj->PSBenutzer"]["PSBenutzer"] = $obj->PSBenutzer;
                $this->dataHome["$obj->PSBenutzer"]["Vorname"] = $obj->Vorname;
                $this->dataHome["$obj->PSBenutzer"]["Nachname"] = $obj->Nachname;
                $this->dataHome["$obj->PSBenutzer"]["eMail"] = $obj->eMail;
                $this->dataHome["$obj->PSBenutzer"]["Mitgliedsnummer"] = $obj->Mitgliedsnummer;
                $this->dataHome["$obj->PSBenutzer"]["PersonasID"] = $obj->PersonasID;
                $this->dataHome["$obj->PSBenutzer"]["DAVPasswort"] = $obj->DAVPasswort;

                #echo "<br>dataHome[$obj->PSBenutzer] = "; var_dump($this->dataHome);
            }
        }

        return $this->dataHome;
    }

 }