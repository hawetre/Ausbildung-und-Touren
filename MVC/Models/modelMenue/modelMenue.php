<?php

    class modelMenue {

        private $dbh;
        private array $Menue;

        public function __construct($dbh) {
            $this->dbh = $dbh;
            $this->Menue = [];
            #echo "<br>modelMenue::";
        }

        public function loadMenue() {
            #echo "<br>Menue::_SESSION[PSBenutzer = "; echo $_SESSION['PSBenutzer'];
            $sql = 'SELECT H.PSHM,H.Hauptfunktion,U.PSHM,U.PSUM,U.Funktion FROM hauptmenue AS H, untermenue AS U ';
            $sql .= 'RIGHT JOIN  benutzer_rechte AS BR ON BR.PSBenutzer='.$_SESSION['PSBenutzer'].' AND BR.PSHM=U.PSHM AND BR.PSUM=U.PSUM ';
            $sql .= 'WHERE H.aktiv=0 AND U.aktiv=0 AND H.PSHM=U.PSHM';    // Menue für Benutzer ermiotteln
            #echo "<br>\tMenue::sql "; echo $sql;
            if(is_object(($result = $this->dbh->query($sql)))) {

                while($obj = $result->fetch_object()) {
                    #echo "<br>Models->Menue::fetch_object = "; var_dump($obj);
                    if(!isset($this->Menue["$obj->Hauptfunktion"])) {
                        $this->Menue["$obj->Hauptfunktion"] = array();
                    }
                    array_push($this->Menue["$obj->Hauptfunktion"], $obj->Funktion);
                }

            }
            #echo "<br>Menue::loadMenue für Benutzer" . $_SESSION['PSBenutzer']; var_dump($this->Menue);
    
        }

        public function getMenue() {
            return $this->Menue;
        }

    }