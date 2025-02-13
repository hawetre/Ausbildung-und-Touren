<?php

class modelAngebot {

        private $dbh;
        private string $PSJahr;
        private string $PSAngebot;
        private string $PSBenutzer;
        private string $Titel;
        private string $Untertitel;
        private string $Ort;
        private string $Termin;
        private string $Zeit;
        private string $Teilnehmerzahl;
        private string $Kosten_Mitglied;
        private string $Kosten;
        private string $Sternchen = "(n.M.)";
        private string $Anmeldeschluss;
        private string $Beschreibung;
        private string $Web_Info;
        #private string $Dateiname = "";
        private $mehrereEinheiten;

        public function __construct($PSJahr, $PSAngebot, $PSBenutzer, $Titel, $Untertitel, $Ort, $Termin, $Zeit, $Teilnehmerzahl, $Kosten_Mitglied, $Kosten, $Sternchen, $Anmeldeschluss, $Beschreibung, $Web_Info, $mehrereEinheiten) {
#echo "<br>modelAngebot->__construct<br>";
                #$this->dbh = $dbh;
                $this->PSJahr = htmlentities($PSJahr);
                $this->PSAngebot = htmlentities($PSAngebot);
                $this->PSBenutzer = htmlentities($PSBenutzer);
                $this->Titel = htmlentities($Titel);
                $this->Untertitel = htmlentities($Untertitel);
                $this->Ort = htmlentities($Ort);
                $this->Termin = htmlentities($Termin);
                $this->Zeit = htmlentities($Zeit);
                $this->Teilnehmerzahl = htmlentities($Teilnehmerzahl);
                $this->Kosten_Mitglied = htmlentities($Kosten_Mitglied);
                $this->Kosten = htmlentities($Kosten);
                $this->Sternchen = htmlentities($Sternchen);
                $this->Anmeldeschluss = htmlentities($Anmeldeschluss);
                $this->Beschreibung = htmlentities($Beschreibung);
                $this->Web_Info = htmlentities($Web_Info);
                $this->mehrereEinheiten = $mehrereEinheiten;
        }

        # getter-Methoden
        public function getPSJahr() {
                return $this->PSJahr;
        }

        public function getPSAngebot() {
                return $this->PSAngebot;
        }

        public function getPSBenutzer() {
                return $this->PSBenutzer;
        }

        public function getTitel() {
                return $this->Titel;
        }

        public function getUntertitel() {
                return $this->Untertitel;
        }

        public function getOrt() {
                return $this->Ort;
        }

        public function getTermin() {
                return $this->Termin;
        }

        public function getZeit() {
                return $this->Zeit;
        }

        public function getTeilnehmerzahl() {
                return $this->Teilnehmerzahl;
        }

        public function getKosten_Mitglied() {
                return $this->Kosten_Mitglied;
        }

        public function getKosten() {
                return $this->Kosten;
        }

        public function getSternchen() {
                return $this->Sternchen;
        }

        public function getAnmeldeschluss() {
                return $this->Anmeldeschluss;
        }

        public function getBeschreibung() {
                return $this->Beschreibung;
        }

        public function getWebInfo() {
                return $this->Web_Info;
        }

        public function getmehrereEinheiten() {
                return $this->mehrereEinheiten;
        }

        public function getDataAngebot() {
                $dataAngebot = array();
                $dataAngebot['EigenesAngebot']['PSJahr'] = $this->getPSJahr();
                $dataAngebot['EigenesAngebot']['PSAngebot'] = $this->getPSAngebot();
                $dataAngebot['EigenesAngebot']['PSBenutzer'] = $this->getPSBenutzer();
                $dataAngebot['EigenesAngebot']['Titel'] = $this->getTitel();
                $dataAngebot['EigenesAngebot']['Untertitel'] = $this->getUntertitel();
                $dataAngebot['EigenesAngebot']['Ort'] = $this->getOrt();
                $dataAngebot['EigenesAngebot']['Termin'] = $this->getTermin();
                $dataAngebot['EigenesAngebot']['Zeit'] = $this->getZeit();
                $dataAngebot['EigenesAngebot']['Teilnehmerzahl'] = $this->getTeilnehmerzahl();
                $dataAngebot['EigenesAngebot']['Kosten_Mitglied'] = $this->getKosten_Mitglied();
                $dataAngebot['EigenesAngebot']['Kosten'] = $this->getKosten();
                $dataAngebot['EigenesAngebot']['Anmeldeschluss'] = $this->getAnmeldeschluss();
                $dataAngebot['EigenesAngebot']['Beschreibung'] = $this->getBeschreibung();
                $dataAngebot['EigenesAngebot']['Web_Info'] = $this->getWebInfo();

                return $dataAngebot;
        }

        # setter-Methoden
        public function setDBH($dbh) {
                $this->dbh = $DBH;
        }

        public function setPSJahr($PSJahr) {
                $this->PSJahr = $PSJahr;
        }

        public function setPSAngebot($PSAngebot) {
                $this->PSAngebot = $PSAngebot;
        }

        public function setPSBenutzer($PSBenutzer) {
                $this->PSBenutzer = $PSBenutzer;
        }

        public function setTitel($Titel) {
                $this->Titel = $Titel;
        }

        public function setUntertitel($Untertitel) {
                $this->Untertitel = $Untertitel;
        }

        public function setOrt($Ort) {
                $this->Ort = $Ort;
        }

        public function setTermin($Termin) {
                $this->Termin = $Termin;
        }

        public function setZeit($Zeit) {
                $this->Zeit = $Zeit;
        }

        public function setTeilnehmerzahl($Teilnehmerzahl) {
                $this->Teilnehmerzahl = $Teilnehmerzahl;
        }

        public function setKosten_Mitglied($Kosten_Mitglied) {
                $this->Kosten_Mitglied = $Kosten_Mitglied;
        }

        public function setKosten($Kosten) {
                $this->Kosten = $Kosten;
        }

        public function setSternchen() {
                $this->Sternchen = "(n.M.)";
        }

        public function setAnmeldeschluss($Anmeldeschluss) {
                $this->Anmeldeschluss = $Anmeldeschluss;
        }

        public function setBeschreibung($Beschreibung) {
                $this->Beschreibung = $Beschreibung;
        }

        public function setWebInfo($WebInfo) {
                $this->Web_Info = $WebInfo;
        }

        public function setMehrereEinheiten($mehrereEinheiten) {
                $this->mehrereEinheiten = $mehrereEinheiten;
        }

        public function setPostData() {
                $this->setPSJahr($_POST['PSJahr']);
                $this->setPSAngebot($_POST['PSAngebot']);
                $this->setPSBenutzer($_POST['PSBenutzer']);
                $this->setTitel(trim($_POST['Titel']));
                $this->setUntertitel($_POST['Untertitel']);
                $this->setOrt(trim($_POST['Ort']));
                $this->setTermin(trim($_POST['Termin']));
                $this->setZeit(trim($_POST['Zeit']));
                $this->setTeilnehmerzahl(trim($_POST['Teilnehmerzahl']));
                $this->setKosten_Mitglied(trim($_POST['Kosten_Mitglied']));
                $this->setKosten(trim($_POST['Kosten']));
                $this->setAnmeldeschluss(trim($_POST['Anmeldeschluss']));
                $this->setBeschreibung(trim($_POST['Beschreibung']));
                $this->setWebInfo(trim($_POST['Web-Info']));
//Upload Datei verarbeiten
/*                $upload_folder = '../Files'; //Das Upload-Verzeichnis
                $filename = pathinfo($_FILES['Datei']['name'], PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($_FILES['Datei']['name'], PATHINFO_EXTENSION));
                 
                 
                //Überprüfung der Dateiendung
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                if(!in_array($extension, $allowed_extensions)) {
                   die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
                }
                 
                //Überprüfung der Dateigröße
                $max_size = 500*1024; //500 KB
                if($_FILES['Datei']['size'] > $max_size) {
                   echo "<br>Bitte keine Dateien größer 500kb hochladen<br>";
                   goto NEXT;
                }
                 
                //Überprüfung dass das Bild keine Fehler enthält
                if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
                   $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
                   $detected_type = exif_imagetype($_FILES['Datei']['tmp_name']);
                   if(!in_array($detected_type, $allowed_types)) {
                      die("Nur der Upload von Bilddateien ist gestattet");
                   }
                }
                $new_path = $upload_folder.$filename.'.'.microtime(bool $as_float = false).'.'.$extension;
                move_uploaded_file($_FILES['Datei']['tmp_name'], $new_path);
                echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
                

// Ende Upload Datei
*/
                NEXT:
                if(!isset($_SESSION['EigenesAngebot'])) {
                        $_SESSION['EigenesAngebot'] = array('PSJahr' => $_POST['PSJahr']);
                        $_SESSION['EigenesAngebot']['PSAngebot'] = $_POST['PSAngebot'];
                        $_SESSION['EigenesAngebot']['PSBenutzer'] = $_POST['PSBenutzer'];
                        $_SESSION['EigenesAngebot']['Titel'] = $_POST['Titel'];
                        $_SESSION['EigenesAngebot']['Untertitel'] = $_POST['Untertitel'];
                        $_SESSION['EigenesAngebot']['Ort'] = $_POST['Ort'];
                        $_SESSION['EigenesAngebot']['Termin'] = $_POST['Termin'];
                        $_SESSION['EigenesAngebot']['Zeit'] = $_POST['Zeit'];
                        $_SESSION['EigenesAngebot']['Teilnehmerzahl'] = $_POST['Teilnehmerzahl'];
                        $_SESSION['EigenesAngebot']['Kosten_Mitglied'] = $_POST['Kosten_Mitglied'];
                        $_SESSION['EigenesAngebot']['Kosten'] = $_POST['Kosten'];
                        $_SESSION['EigenesAngebot']['Anmeldeschluss'] = $_POST['Anmeldeschluss'];
                        $_SESSION['EigenesAngebot']['Beschreibung'] = $_POST['Beschreibung'];
                        $_SESSION['EigenesAngebot']['Web-Info'] = $_POST['Web-Info'];
                }
        }

        public function geaendertPostSessionData() {
                $geändert = 0;
#echo "<br>####modelAngebot->geaendertPostSession - _SESSION[EigenesAngebot] = "; print_r($_SESSION);
                if($_SESSION['EigenesAngebot']['Titel'] != $this->getTitel()) { 
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Untertitel'] != $this->getUntertitel()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Ort'] != $this->getOrt()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Termin'] != $this->getTermin()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Zeit'] != $this->getZeit()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Teilnehmerzahl'] != $this->getTeilnehmerzahl()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Kosten_Mitglied'] != $this->getKosten_Mitglied()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Kosten'] != $this->getKosten()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Anmeldeschluss'] != $this->getAnmeldeschluss()) {
                    $geändert++;
                }
                if($_SESSION['EigenesAngebot']['Beschreibung'] != $this->getBeschreibung()) {
                    $geändert++; 
                }
                if($_SESSION['EigenesAngebot']['Web_Info'] != $this->getWebInfo()) {
                    $geändert++;
                }
#echo "br>modelAngebot->geaendertPostSessionData = $geändert Änderungen";
                return $geändert;
        }

        public function checkDataValues() {
                if (is_file("../Core/bitStructAngebot.php")) {
                        include_once "../Core/bitStructAngebot.php";
                }
                $bitStructAngebot = new bitStructAngebot();
                               
                $result = array();
                if(preg_match_all('/[a-zA-Z.,!?-]+/', $this->Titel, $result) == 0 || strlen($this->Titel) > 64 ) {
                        $bitStructAngebot->falseTitel();
                }
                if(preg_match_all('/[a-zA-Z.,!?-]+/', $this->Ort, $result) == 0 || strlen($this->Ort) > 64 ) {
                        $bitStructAngebot->falseOrt();
                }
                if(preg_match('/^[0123][0-9].[01][0-9].[23][5-9]$/', $this->Termin, $result) == 0 &&
                   preg_match('/^[0123][0-9].[01][0-9]. - [0123][0-9].[01][0-9].[23][5-9]$/', $this->Termin, $result) == 0) {
                                $bitStructAngebot->falseTermin();
                }
                if(preg_match('/[0-9]{1,2}\s(h|Uhr)/', $this->Zeit, $result) == 0 &&
                   strcmp($this->Zeit, "nach_Absprache") != 0  && 
                   strcmp(trim($this->Zeit), "Tagesetappen") != 0 && 
                   strcmp(trim($this->Zeit), "Tagestouren") != 0 ) {
                        $bitStructAngebot->falseZeit();
                }
                if(preg_match('/([1-9]-[0-9]|99|[2-9])/', $this->Teilnehmerzahl, $result) == 0 || strlen($this->Teilnehmerzahl) > 6) {
                        $bitStructAngebot->falseTeilnehmerzahl();
                }
                if(preg_match('/^[0-9]+$/', $this->Kosten_Mitglied, $result) == 0 || strlen($this->Kosten_Mitglied) > 4 ) {
                        $bitStructAngebot->falseKosten_Mitglied();
                        if(strpos($this->Kosten_Mitglied, "entfällt") === false) {
                                $bitStructAngebot->falseKosten_Mitglied();
                        }
                }
                if(preg_match('/^[0-9]{0,4}$/', $this->Kosten, $result) == 0) {
                        $bitStructAngebot->falseKosten();
                        if(strlen($this->Kosten) > 0) {
                                $bitStructAngebot->falseKosten();
                        }
                }
                if(preg_match_all('/[0-3][0-9].[0-1][0-9].[2-3][0-9]/', $this->Anmeldeschluss, $result) == 0 || strlen($this->Anmeldeschluss) > 8 ) {
                        $bitStructAngebot->falseAnmeldeschluss();
                }
                if(strlen($this->Beschreibung) > 840 || strlen($this->Beschreibung) == 0) {
                        $bitStructAngebot->falseBeschreibung();
                }
                if(is_null($this->Web_Info) or strlen($this->Web_Info) > 1024) {
                        $bitStructAngebot->falseWeb_Info();
                }

                return array('bitStructAngebot' => serialize($bitStructAngebot), 'countErrors' => $bitStructAngebot->countErrors());
        }

        public function updateData($dbh) {
                $this->dbh = $dbh;

                $sql = "UPDATE angebote ";
                $sql .= "SET Titel = '".$this->Titel."',";
                $sql .= "Untertitel = '".$this->Untertitel."',";
                $sql .= "Ort = '".$this->Ort."',";
                $sql .= "Termin = '".$this->Termin."',";
                $sql .= "Zeit = '".$this->Zeit."',";
                $sql .= "Teilnehmerzahl = '".$this->Teilnehmerzahl."',";
                $sql .= "Kosten_Mitglied = '".$this->Kosten_Mitglied."',";
                $sql .= "Kosten = '".$this->Kosten."',";
                $sql .= "Anmeldeschluss = '".$this->Anmeldeschluss."',";
                $sql .= "Beschreibung = '".$this->Beschreibung."',";
                $sql .= "Web_Info = '".$this->Web_Info."' ";
                $sql .= "WHERE PSJahr=".$this->PSJahr." AND PSAngebot=".$this->PSAngebot." AND PSBenutzer=".$this->PSBenutzer;
#echo "<br>#### updateData-SQL = $sql";
                $result = $this->dbh->query($sql);
#echo "<br>#### result = "; print_r($result);
                if($result !== false && is_object($result)) {
                        return true;
                } else {
                        return false;
                }

        }

        public function saveData($dbh) {
                #INSERT INTO table_name (column1, column2, column3, ...)
                #VALUES (value1, value2, value3, ...);
                $this->dbh = $dbh;

                $sql = "INSERT INTO angebote ";
                $sql .= "(PSJahr, PSBenutzer, Titel, Untertitel, Ort, Termin, Zeit, Teilnehmerzahl, Kosten_Mitglied, Kosten, Anmeldeschluss, Beschreibung, Web_Info)";
                $sql .= " VALUES ('".$this->PSJahr."',";
                $sql .= "'".$this->PSBenutzer."','".$this->Titel."','".$this->Untertitel."','".$this->Ort."','".$this->Termin."',";
                $sql .= "'".$this->Zeit."','".$this->Teilnehmerzahl."','".$this->Kosten_Mitglied."','".$this->Kosten."',";
                $sql .= "'".$this->Anmeldeschluss."','".$this->Beschreibung."','".$this->Web_Info."')";
#echo "<br>#### saveData - sql = $sql";
                $result = $this->dbh->query($sql);
#echo "<br>#### result = "; print_r($result);
                #$sql = "SELECT mysql_insert_id()";
                $this->setPSAngebot($this->dbh->insert_id);
#echo "<br>#### PSAngebot = "; print_r($this->getPSAngebot());
                if($result !== false && is_object($result)) {
                        return true;
                } else {
                        return false;
                }

        }

}