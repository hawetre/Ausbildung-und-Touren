<?php
    /**
     * Classe viewHome für die Views
     */
    define("HAUPTFUNKTION", "<h3>{{ Hauptfunktion }}</h3>");
    define("MENUESTART", "<ul>");
    define("FUNKTION", "<li><h3>{{ Funktion }}</h3></li>");
    define("MENUEENDE", "</ul>");

    class viewHome {
        private Template $template;
        private array $dataHome;
        private string $menue;
        private string $webpage;

        public function __construct() {
            $this->webpage = "";
            $this->menue = "";
            $this->dataHome = [];
            $pathfile = "../MVC/Views/Templates/Template.php";
            include_once $pathfile;
            $this->template = new Template();
        }

        public function execute($action, $dataHome, $error) {
#echo "<br>viewHome->execute - error = $error";
            # echo "<br>action = $action";
            $this->dataHome = $dataHome;
            #echo "<br>define HAUPTFUNKTION: "; echo HAUPTFUNKTION;
            switch ($action) {
                case 'anmelden':
                    $this->webpage = $this->template->getIndexTemplate();
                    $login = $this->template->getLoginFormular();
                    $login = $this->template->render($login, "/{{.*}}/", "", -1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $login, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;
                    break;

            case "checkAnmeldung":
                    #$this->setTagsErrorMessages($error, $this->dataHome);
#echo "<br>checkAnmeldung - this->dataHome = "; print_r($this->dataHome);
                    /*$check = 0;
                    if(is_null($this->dataHome)) {
                        $check++;
                    }/* elseif(empty($this->dataHome)) {
                        $check++;
                    }*/

                    if($error == 0) {
#echo "<br>checkAnmeldung - this->dataHome = "; print_r($this->dataHome);
                        $this->webpage = $this->template->getIndexTemplate();
                        $login = $this->template->getLoginFormular();
                        $login = $this->template->render($login, "/{{.*}}/", "", -1);
                        $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $login, 1);
                        $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                        echo $this->webpage;
                    } else {
                        $this->setTagsErrorMessages($error, $this->dataHome);
                        $this->webpage = $this->template->getIndexTemplate();
                        $login = $this->template->getLoginFormular();
                        foreach($this->dataHome as $tag => $value) {
#echo "<br>tag = "; echo $tag; echo " value = "; echo $value;
                            $login = $this->template->render($login, "/$tag/", $value, -1);
                        }
                        if($_SESSION['loginerroers'] > 3) {
                            $login = $this->template->render($login, "/{{ loginerrors }}/", '<br><font color="red">Anmelde-Fehlversuche!<br>Wartezeit wird nach 5 Anmeldeversuchen aktiv!', -1);
                        }
                        $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $login, 1);
                        $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                        echo $this->webpage;  
                    }
                    break;

                case "anzeigenMenue":
                    $this->webpage = $this->template->getIndexTemplate();
                    #$this->webpage = $this->template->render($this->webpage, "/{{ Javascript-Datei }}/", '<script src="../MVC/Views/Templates/javascriptFunctionsHome.js">'.'</script>', 1); 
#echo $this->webpage;
                    #echo "<br>viewHome::anziegenMenue->dataHome = "; var_dump($this->dataHome);
                    foreach($this->dataHome as $key => $functions) {
                        #echo "<br>viewHome::anzeigenMenue->functions = "; var_dump($functions);
                        if(count($functions) > 0) {
                            $this->menue .= HAUPTFUNKTION;
                            $this->menue = preg_replace("/{{ Hauptfunktion }}/", "$key", $this->menue, 1);
                            $controler = $key;
                            #$controler = str_replace(" ", "", $controler);
                            foreach($functions as $function) {
                                $this->menue .= MENUESTART;
                                $this->menue .= FUNKTION;
                                $uri = $_SERVER['PHP_SELF']."?".$function['Controler']."=".$function['Action'];
                                $href = '<a href='.$uri.'>';
                                $href = $href . $function['Funktionsanzeige'] . '</a>';
                                $this->menue = preg_replace("/{{ Funktion }}/", "$href", $this->menue, 1);
                                $this->menue .= MENUEENDE;
                            }
                        } else {
                            $this->menue .= HAUPTFUNKTION;
                        }                       
                        #echo "<br>Views->viewHome::this->menue "; echo $this->menue;
                    }
                    $this->menue .= '<br><form action="'.$_SERVER['PHP_SELF'].'?Home=abmelden>"';
                    $this->menue .= '<p><input type="button" name="Verweis" value="abmelden" style="font-size:125%"';
                    $this->menue .= 'onClick="self.location.href='."'".$_SERVER['PHP_SELF']."?Home=abmelden'".'"';
                    $this->menue .= '</p></form>';

                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    #echo "<br>viewHome::anzeigenMenue->count("
                    /*foreach($this->dataHome as $Hauptfunktion => $arrayFuntionen) {
                        $this->webpage = $this->template->render($this->webpage, "/{{ Hauptfunktion }}/", $Hauptfunktion, 1);
                    }*/
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", '<h3 align="center">Bitte eine Funktion aus dem linken Menü auswählen.</h3>', 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);

                    echo $this->webpage;
                    break;

            }
        }

        private function getIndexTemplate() {
            return $this->template->getIndexTemplate();
        }

        private function getLoginFormular() {
            return $this->template->getLoginFormular();
        }

        private function getMenueTemplate() {
            return $this->template->getMenueTemplate();
        }

        private function setTagsErrorMessages($error, &$dataHome) {
            $errorMailadresse = '<font color="red" style="font-size:80%">Die Mailadresse ist nicht korrekt!</font>';
            $errorMitgliedsnummer = '<font color="red" style="font-size:80%">Die Mitgliednummer ist nicht korrekt!</font>';
            $errorDAVPassowrt = '<font color="red" style="font-size:80%">Das DAV-Passwort ist nicht korrekt!</font>';
    
            switch ($error) {
                case 0:
                    $dataHome['{{ ERROR_MAILADRESSE }}'] = "";
                    $dataHome['{{ ERROR_MITGLIEDSNUMMER }}'] = "";
                    $dataHome['{{ ERROR_PASSWORT }}'] = "";
                case 1:
                    # Fehlertext für Mailadresse
                    $dataHome['{{ ERROR_MAILADRESSE }}'] = $errorMailadresse;
                    # echo "<br>"; echo $this->dataHome['{{ ERROR_MAILADRESSE }}'];
                    break;
                case 2:
                    # Fehlertext für Mitgliedsnummer
                    $dataHome['{{ ERROR_MITGLIEDSNUMMER }}'] = $errorMitgliedsnummer;
                    break;
                case 4:
                    # Fehlertext für DAV-Passwort
                    $dataHome['{{ ERROR_PASSWORT }}'] = $errorDAVPassowrt;
                    break;
                case 3:
                    # Fehlertext für Mailadresse und Mitgliedsnummer
                    $dataHome['{{ ERROR_MAILADRESSE }}'] = $errorMailadresse;
                    $dataHome['{{ ERROR_MITGLIEDSNUMMER }}'] = $errorMitgliedsnummer;
                    break;
                case 5:
                    # Fehlertext für Mailadresse und DAV-Passwort
                    $dataHome['{{ ERROR_MAILADRESSE }}'] = $errorMailadresse;
                    $dataHome['{{ ERROR_PASSWORT }}'] = $errorDAVPassowrt;
                    # echo "<br>"; echo $this->dataHome['{{ ERROR_MAILADRESSE }}'];
                    break;
                case 6:
                    # Fehlertext für Mitgliedsnummer und DAV-Passwort
                    $dataHome['{{ ERROR_MITGLIEDSNUMMER }}'] = $errorMitgliedsnummer;
                    $dataHome['{{ ERROR_PASSWORT }}'] = $errorDAVPassowrt;
                    break;
                default:
                    # Fehlertext für Mailadresse, Mitgliedsnummer und DAV-Passwort
                    $dataHome['{{ ERROR_MAILADRESSE }}'] = $errorMailadresse;
                    $dataHome['{{ ERROR_MITGLIEDSNUMMER }}'] = $errorMitgliedsnummer;
                    $dataHome['{{ ERROR_PASSWORT }}'] = $errorDAVPassowrt;
                    break;
            }
            $this->dataHome['{{ MAILADRESSE }}'] = $_POST['Mailadresse'];
            $this->dataHome['{{ MITGLIEDSNUMMER }}'] = $_POST['Mitgliedsnummer'];

        }
    
    }