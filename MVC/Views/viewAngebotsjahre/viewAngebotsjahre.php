<?php
    /**
     * Classe viewAlleAngebote für die Views
     */

     define("OPTION", "<option {{ selected }}>{{ Angebotsjahr }}</option>");
     define("HAUPTFUNKTION", "<h3>{{ Hauptfunktion }}</h3>");
     define("MENUESTART", "<ul>");
     define("FUNKTION", "<li><h3>{{ Funktion }}</h3></li>");
     define("MENUEENDE", "</ul>"); 

     define("TR", '<tr valign="middle" bgcolor="{{ Color }}">');
     define("TD", '<td valign="middle">{{ Jahr }}</td><td valign="middle">{{ Beginn }}</td><td valign="middle">{{ Ende }}</td><td valign="middle">{{ eintragen }}</td><td valign="middle">{{ aktiv }}</td><td valign="middle">{{ AnzahlAngebote }}</td>');
    #define("TR-", '</tr>');
     define('ColorHellgruen', '#ccffcc');
     define('ColorHellgelb', '#ffffcc');

    class viewAngebotsjahre {
        private Template $template;
        private array $dataAngebotsjahre;
        private string $menue;
        private string $auswaehlenAngebotsjahr;
        private string $anzeigenAngebotsjahreTemplate;
        private string $webpage;

        public function __construct() {
            $this->webpage = "";
            $this->menue = "";
            $this->dataEigeneAngebote = [];
            $pathfile = "../MVC/Views/Templates/Template.php";
            include_once $pathfile;
            $this->template = new Template();
        }

        public function execute($action, $dataAngebotsjahre, $error) {
            $this->dataAngebotsjahre = $dataAngebotsjahre;
            switch ($action) {
                case 'anzeigen':
#echo "<br>viewAngebotsjahre->execute - anzeigen<br>";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->anzeigenAngebotsjahreTemplate = $this->template->getAngebotsjahreTemplate();
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";

                    $Tabelle = "";
                    $Color = ColorHellgruen;
                    if(!empty($dataAngebotsjahre)) {
                        foreach($dataAngebotsjahre as $Index => $Jahr) {
                            if($Color == ColorHellgruen) {
                                $Color = ColorHellgelb;
                            } else {
                                $Color = ColorHellgruen;
                            }
                            $tmp = TR;
                            $tmp = preg_replace('/{{ Color }}/', $Color, $tmp, 1);
                            $tmp .= TD;
                            $tmp = preg_replace('/{{ Jahr }}/', $Index, $tmp, 1);
                            $tmp = preg_replace('/{{ Beginn }}/', $Jahr['Beginn'], $tmp, 1);
                            $tmp = preg_replace('/{{ Ende }}/', $Jahr['Ende'], $tmp, 1);
                            $tmp = preg_replace('/{{ eintragen }}/', $Jahr['eintragen'] == 0 ? "ja" : "nein", $tmp, 1);
                            $tmp = preg_replace('/{{ aktiv }}/', $Jahr['aktiv'] == 0 ? "aktiv" : "inaktiv", $tmp, 1);
                            $tmp = preg_replace('/{{ AnzahlAngebote }}/', $Jahr['Anzahl'], $tmp, 1);
                            $tmp .= "</tr>";
                            $Tabelle .= $tmp;
                        }
                    } else {
                        $Tabelle = '<br><p align="center"><b>Keine Angebotsjahre vorhanden!</b></p>';
                    }
                    $this->anzeigenAngebotsjahreTemplate = $this->template->render($this->anzeigenAngebotsjahreTemplate, "/{{ Angebotsjahre }}/", $Tabelle, 1);
                    $this->getHtmlMenue();
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content.$this->anzeigenAngebotsjahreTemplate, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;
#                        }
#                    }
                    break;

                case 'bearbeiten':
                    BEARBEITEN:
#echo "<br>viewEigeneAngebote->bearbeiten";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    $Options = $this->getHtmlOptionsAngebotsjahre();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsJahr();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "EigeneAngebote", $this->auswaehlenAngebotsjahr, -1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "bearbeiten", $this->auswaehlenAngebotsjahr, -1);
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
#                    $this->anzeigenAngeboteTemplate = preg_replace('/{{ option }}/', $Options, $this->anzeigenAngeboteTemplate, 1);
                    # Formular-Template mit select-Box und Button
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";
                    $Content .= $this->auswaehlenAngebotsjahr."<br>";

                    if(!empty($dataEigeneAngebote)) {
                        foreach($dataEigeneAngebote as $Index => $Angebot) {
    #echo "<br>Index = $Index - Angebot = "; print_r($Angebot);
                            $Jahr = array_key_first($Angebot);
                            $Angebot = $Angebot[$Jahr];
                            $tmp = $this->anzeigenAngeboteTemplate;
                            $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Untertitel }}/", $Angebot['Untertitel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Name }}/", $Angebot['Benutzer'], 1);
                            $tmp = $this->template->render($tmp, "/{{ eMail }}/", $Angebot['eMail'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Zeit }}/", $Angebot['Zeit'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp".$Angebot['Sternchen'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                            #$Content .= $tmp;
#echo "<br>viewEigeneangebote-bearbeiten: Jahr = $Jahr<br>"; print_r($_SESSION['dataAngebotsjahre']);
#echo "<br>_SESSION[dataAngebotsjahre][Jahr][eintragen] = ".$_SESSION['dataAngebotsjahre'][$Jahr]['eintragen'];
                            if($_SESSION['dataAngebotsjahre'][$Jahr]['eintragen'] == 0) {
                                $button = '<tr bgcolor="#ffffcc">';
                                $button .= '<td colspan="3" align="center">';
                                $button .= '<form action="/index.php?EigeneAngebote=aendern" method="post">';
                                #$button .= '<input type="button" name="aendern" value="ändern"';
                                #$button .= ' onClick="self.location.href='."'https://angeboteundtouren.dav-hagen.de/index.php?EigeneAngebote=bearbeiten'.'">'';
                                $button .= ' <input type="hidden" name="PSJahr" value="{{ Jahr }}">';
                                $button .= ' <input type="hidden" name="PSBenutzer" value="{{ PSBenutzer }}">';
                                $button .= ' <input type="hidden" name="PSAngebot" value="{{ PSAngebot }}">';
                                $button .= ' <input type="hidden" name="Formular" value="Angebot im Formular bearbeiten">';
                                $button .= ' <input type="submit" name="bearbeiten" value="Bearbeiten">';
                                $button .= ' </form></td></tr>';
    #echo "<br>viewEigeneAngebote - button:<br>$button";
                                $button = $this->template->render($button, "/{{ Jahr }}/", $Jahr, 1);
                                $button = $this->template->render($button, "/{{ PSBenutzer }}/", $Angebot['PSBenutzer'], 1);
                                $button = $this->template->render($button, "/{{ PSAngebot }}/", $Angebot['PSAngebot'], 1);
                            } else {
                                $button = '<tr bgcolor="#ffffcc">';
                                $button .= '<td colspan="3" align="center">';
                                $button .= '<p align="center"><font color="red"><b>Bearbeiten der Angebote für das Jahr '.$Jahr.' ist nicht aktiv!</b></font></p>';
                                $button .= '</td></tr>';
                            }
                            $tmp = $this->template->render($tmp, "/{{ AendernKopieren }}/", $button, 1);
                            $Content .= $tmp."<br>";
                        }
                    } else {
                        $Content .= '<br><p align="center"><b>Keine Angebote für das Jahr '.$_SESSION['PSJahr'].' vorhanden!</b></p>';
                    }

                    $this->getHtmlMenue();
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;

                    break;

                case "aendern":
#echo "<br>viewEigeneAngebote-aendern";
goto BEARBEITEN;
                    break;
            }
        }

        private function countAngeboteJahr($Jahr) {

        }

        private function getHtmlOptionsAngebotsjahre() {
            $tmp = "";
            foreach($_SESSION['Angebotsjahre'] as $i => $Jahr) {
                $tmp .= OPTION;
                $tmp = preg_replace('/{{ Angebotsjahr }}/', $Jahr, $tmp, 1);
                if($Jahr == $_SESSION['Angebotsjahr']) {
                    $tmp = preg_replace('/{{ selected }}/', "selected", $tmp, 1);
                } else {
                    $tmp = preg_replace('/{{ selected }}/', "", $tmp, 1);
                }
            }
            return $tmp;
            
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
            
