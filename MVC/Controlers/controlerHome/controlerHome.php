<?php
    // $homepage = file_get_contents('http://www.example.com/');
    /**
     * 
     * Action selektieren
     * 
     * indexTemplate in Variable laden
     * Anmeldeformular in Variable laden
     * 
     * DAVPasswort = 
     * 
     */

    class controlerHome {
        private $dbh;
        private viewHome $viewHome;
        private modelHome $modelHome;
        private array $dataHome;
        private string $webpage;

        public function __construct($dbh) {
            $this->dbh = $dbh;
            $this->dataHome = array();
            $this->webpage = "";
            include_once '../Core/Funktionen.php';
        }

        public function execute($route) {
            $action = $route['Action'];
            #echo "<br>controlerHome startet! Action = $action";
            #echo "<br>aktuelles Verzeichnis: = "; print_r(getcwd());

            //Action auswerten
            $error = 0;
            switch ($action) {
                case 'anmelden':
                    anmelden:
                    #echo "<br>controlerHome->execute Action = $action";
                    #echo "<br>controlerHome::anmelden";
                    //Anmeldeformular ausgeben
                    if (is_file("../MVC/Views/viewHome/viewHome.php")) {
                        include_once "../MVC/Views/viewHome/viewHome.php";
                    } else {
                        echo "<br>Die Datei '../MVC/Views/viewHome/viewHome.php' wurde nicht gefunden.";
                        echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                        session_abort();
                        die('<p>Programmabbruch!:</p>');
                    }
                    $this->viewHome = new viewHome();
                    $this->viewHome->execute($action, $this->dataHome, 0);

                    break;

                case "checkAnmeldung":
                    #echo "<br>controlerHome::checkAnmeldung";
                    //Syntaxprüfung der Einagbe
                    #echo "<br>controlerHome->execute Action = $action";
                    $error = 0;
                    if(preg_match('/.*@dav-hagen.de/', $_POST['Mailadresse'], $output_array) !== 1) {
                        $error += 1;    //1 = Mailadresse falsch
                    }
                    if(preg_match('/\d\d\d\/00\/\d\d\d\d\d\d/', $_POST['Mitgliedsnummer'], $output_array) !== 1) {
                        $error += 2;    //2 = Mitgliedsnummer falsch
                    }
                    if(strlen($_POST['DAVPasswort']) < 15) {
                        $error += 4;    //4 = Passwort zu kurz / falsch
                    }
                    if($error > 0) {
                        # echo "<br>controlerHome::Eingabewerte mit Syntaxfehlern - error = $error";
                        # Eingabwerte wieder mit Formular ausgeben
                        if (is_file("../MVC/Views/viewHome/viewHome.php")) {
                            include_once "../MVC/Views/viewHome/viewHome.php";
                        } else {
                            echo "<br>Die Datei '../MVC/Views/viewHome/viewHome.php' wurde nicht gefunden.";
                            echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                            session_abort();
                            die('<p>Programmabbruch!:</p>');   
                        }
                        if(isset($_SESSION['loginerroers'])) {
                            $_SESSION['loginerroers']++;
                            if($_SESSION['loginerroers'] > 5 && $_SESSION['loginerroers'] <= 10) {
                                set_time_limit(0); echo "<br>#### Zu viele Anmelde-Fehlversuche! Sie mussten 30 Sekunden warten!"; flush();
                                sleep(30);
                            } elseif($_SESSION['loginerroers'] > 10) {
                                set_time_limit(0); echo "<br>#### Zu viele Anmelde-Fehlversuche! 3 Minuten warten!"; flush();
                                sleep($_SESSION['loginerroers'] * $_SESSION['loginerroers']);
                            }
                        }
#echo "<br>#### _SESSION['loginerroers'] existiert und hat den Wert "; echo $_SESSION['loginerroers'];
                        $this->viewHome = new viewHome();
                        $this->viewHome->execute($action, $this->dataHome, $error);
    
                    } else {
                        #echo "<br>controlerHome::Eingabewerte ohne Syntaxfehler";
                        if (is_file("../MVC/Models/modelHome/modelHome.php")) {
                            include_once "../MVC/Models/modelHome/modelHome.php";
                        } else {
                            echo "<br>Die Datei '../MVC/Models/modelHome/modelHome.php' wurde nicht gefunden.";
                            echo "<br>Damit lässt sich die Web-Anwendung nicht weiter ausführen!";
                            session_abort();
                            die('<p>Programmabbruch!:</p>');   
                        }
                        #echo "<br>controlerHome::modelHome instanzieren";
                        $this->modelHome = new modelHome($this->dbh);
                        $this->dataHome = $this->modelHome->execute($action, $this->dataHome);
                        #echo "<br>dataHome = "; var_dump($this->dataHome);
                        # Daten aus DB.benutzer ermitteln und mit Eingaben vergleichen
                        if(!empty($this->dataHome)) {       //this->dataHome ist nicht leer
                            #print "<br>Ergebnisse vorhanden";
                            $error = 7;
                            foreach ($this->dataHome as $key => $value) {
                                #echo "<br>value = "; var_dump($value);
#echo "<br>eMail = "; echo $value["eMail"]; echo "  -  strtolower(eMail) = "; echo strtolower($value["eMail"]);
#echo "<br>Mailadresse = "; echo $_POST['Mailadresse']; echo "  -  strtolower(Mailadresse) = "; echo strtolower($_POST['Mailadresse']);
                                if(strcasecmp($value["eMail"], $_POST['Mailadresse']) === 0) {
                                #if(preg_match('/'.strtolower($value["eMail"]) .'/', strtolower($_POST['Mailadresse']), $output_array) == 0) {
#echo "<br>Vergleich Mailadressen: strcasecmp = "; echo strcasecmp($value["eMail"], $_POST['Mailadresse']);
                                    #echo "<br>Mailadresse ist ok!";
                                    $error -= 1;
                                    if(password_verify($_POST['Mitgliedsnummer'], $value["Mitgliedsnummer"])) {
                                        #echo "<br>Mitgliedsnummer ist ok!";
                                        $error -= 2;
                                        if(password_verify($_POST['DAVPasswort'], $value["DAVPasswort"])) {
                                            #echo "<br>DAVPasswort ist ok!";
                                            $error -= 4;
                                            $_SESSION['PSBenutzer'] = $value["PSBenutzer"];
                                            $_SESSION['Vorname'] = $value["Vorname"];
                                            $_SESSION['Nachname'] = $value["Nachname"];
                                            $_SESSION['eMail'] = $value["eMail"];
                                            $_SESSION['PersonasID'] = $value["PersonasID"];
                                            #echo "<br>Benutzer in SESSION gesichert: "; var_dump($_SESSION);
                                            #echo "<br>Anmeldung erfogreich! Menü kann ausgegeben werden.";
                                            break;
                                        }                                       
                                    }
                                }
                            }
                        }
#echo "<br>error = $error";
                        neuanmelden:
                        if($error > 0) {
                            print "KEIN zutreffenden Benutzer gefunden!";
                            # Anmeldeformular neu ausgeben
                            $action = 'anmelden';
                            include_once "../MVC/Views/viewHome/viewHome.php";
                            $this->viewHome = new viewHome();
                            #$this->viewHome->setTagsErrorMessages($error, $this->dataHome);
                            $this->viewHome->execute($action, $this->dataHome, $error);
                        }
                    }

                    if($error == 0) {
                        $_SESSION['loginerroers'] = 0;
                        goto anzeigenMenue;
                    }
                    break;

                case "anzeigenMenue":
                    anzeigenMenue:
                    $action = 'anzeigenMenue';
                    if(!isset($_SESSION['PSBenutzer'])) {
                        #$_GET['Home'] = 'anmelden';
                        header("Location: https://ausbildungundtouren.dav-hagen.de/index.php?Home=anmelden"); /* Browser umleiten */
                        exit;        
                    }

                    $this->dataHome = [];
                    #echo "<br>controlerHome->execute Action = $action";
                    # Daten aus DB.hauptmenue-untermenue ermitteln und mit Eingaben vergleichen
                    include_once "../MVC/Models/modelHome/modelHome.php";
                    $this->modelHome = new modelHome($this->dbh);
                    //Menü ausgeben
                    $this->dataHome = $this->modelHome->execute($action, $this->dataHome);
                    #echo "<br>controlerHome::Menü wird ausgegeben.";
                    #echo "<br>controlerHome::action = "; echo $action;
                    include_once "../MVC/Views/viewHome/viewHome.php";
                    $this->viewHome = new viewHome();
                    $this->viewHome->execute($action, $this->dataHome, $error);

                    break;

                case "abmelden":
                    #$_SESSION['Menue'] = [];
                    unset ($_SESSION ['Menue']);
                    unset ($_SESSION ['PSBenutzer']);
                    unset ($_SESSION ['Vorname']);
                    unset ($_SESSION ['Nachname']);
                    unset ($_SESSION ['eMail']);
                    unset ($_SESSION ['PersonasID']);
                    #session_abort();
                    $action = "anmelden";
                    goto anmelden;
                    break;
            }
        }

    }