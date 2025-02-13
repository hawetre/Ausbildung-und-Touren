<?php
    /**
     * Classe viewBenutzer für die Views
     */
    define("OPTION", "<option {{ selected }}>{{ Angebotsjahr }}</option>");
    define("HAUPTFUNKTION", "<h3>{{ Hauptfunktion }}</h3>");
    define("MENUESTART", "<ul>");
    define("FUNKTION", "<li><h3>{{ Funktion }}</h3></li>");
    define("MENUEENDE", "</ul>"); 

    define("TR", '<tr valign="middle" bgcolor="{{ Color }}">');
    define("TD", '<td valign="middle">{{ Vorname }}</td><td valign="middle">{{ Nachname }}</td><td valign="middle">{{ eMail }}</td></td>');
    define('ColorHellgruen', '#ccffcc');
    define('ColorHellgelb', '#ffffcc');


    class viewBenutzer {
        private Template $template;
        private array $dataBenutzer;
        private string $anzeigenAngebotsBenutzerTemplate;
        private string $menue;
        private string $webpage;

        public function __construct() {
            $this->webpage = "";
            $this->menue = "";
            $this->anzeigenAngebotsBenutzerTemplate = "";
            $this->dataBenutzer = [];
            $pathfile = "../MVC/Views/Templates/Template.php";
            include_once $pathfile;
            $this->template = new Template();
        }

        public function execute($action, $dataBenutzer, $error) {
            $this->dataBenutzer = $dataBenutzer;
            switch ($action) {
                case 'anzeigen':

                break;

                case 'Anbieter':
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->anzeigenAngebotsBenutzerTemplate = $this->template->getAnzeigenAngebotsBenutzerTemplate();
#echo "<br>#### Template = $this->anzeigenAngebotsBenutzerTemplate";
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";

                    $Tabelle = "";
                    $Color = ColorHellgruen;
                    if(!empty($dataBenutzer)) {
                        foreach($dataBenutzer as $PSBenutzer => $Benutzer) {
                            if($Color == ColorHellgruen) {
                                $Color = ColorHellgelb;
                            } else {
                                $Color = ColorHellgruen;
                            }
                            $tmp = TR;
                            $tmp = preg_replace('/{{ Color }}/', $Color, $tmp, 1);
                            $tmp .= TD;
                            $tmp = preg_replace('/{{ Vorname }}/', $Benutzer['Vorname'], $tmp, 1);
                            $tmp = preg_replace('/{{ Nachname }}/', $Benutzer['Nachname'], $tmp, 1);
                            $tmp = preg_replace('/{{ eMail }}/', $Benutzer['eMail'], $tmp, 1);
                            $tmp .= "</tr>";
                            $Tabelle .= $tmp;
                        }
                    } else {
                        $Tabelle = '<br><p align="center"><b>Keine Benutzer vorhanden!</b></p>';
                    }
                    #$Content .= $Tabelle;
#echo "<br>#### Tabelle = $Tabelle";
                    #$tmp = preg_replace('/{{ Vorname }}/', $Benutzer['Vorname'], $tmp, 1);
                    $this->anzeigenAngebotsBenutzerTemplate = $this->template->render($this->anzeigenAngebotsBenutzerTemplate, "/{{ AngebotsBenutzer }}/", $Tabelle, 1);
                    $Content .= $this->anzeigenAngebotsBenutzerTemplate;
                    $this->getHtmlMenue();
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;

                break;
            }
        }
            
        private function getHtmlMenue() {
            if(isset($_SESSION['Menue']) && !empty($_SESSION['Menue'])) {
                #echo "<br>modelHome->execute anzeigenMenue: _SESSION[Menue] existiert und ist nicht leer <br>"; print_r($_SESSION['Menue']);
                foreach($_SESSION['Menue'] as $key => $functions) {
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
                }
            }
            $this->menue .= '<br><form action="'.$_SERVER['PHP_SELF'].'?Home=abmelden>"';
            $this->menue .= '<p><input type="button" name="Verweis" value="abmelden" style="font-size:125%"';
            $this->menue .= 'onClick="self.location.href='."'".$_SERVER['PHP_SELF']."?Home=abmelden'".'"';
            $this->menue .= '</p></form>';
        }

    }