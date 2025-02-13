<?php
    /**
     * Classe viewAlleAngebote für die Views
     */
    define("OPTION", "<option {{ selected }}>{{ Angebotsjahr }}</option>");
    define("HAUPTFUNKTION", "<h3>{{ Hauptfunktion }}</h3>");
    define("MENUESTART", "<ul>");
    define("FUNKTION", "<li><h3>{{ Funktion }}</h3></li>");
    define("MENUEENDE", "</ul>");

    class viewEigeneAngebote {
        private Template $template;
        private array $dataEigeneAngebote;
        private string $menue;
        private string $auswaehlenAngebotsjahr;
        private string $anzeigenAngeboteTemplate;
        private string $neu_aendernAngebotTemplate;
        private string $neu_kopierenAngebotTemplate;
        private string $auswaehlenEigenesAngebot;
        private string $webpage;

        public function __construct() {
            $this->webpage = "";
            $this->menue = "";
            $this->dataEigeneAngebote = [];
            $pathfile = "../MVC/Views/Templates/Template.php";
            include_once $pathfile;
            $this->template = new Template();
        }

        public function execute($action, $dataEigeneAngebote, $error) {
#echo "<br>#### viewEigeneangebote->execute - dataEigeneAngebote = "; print_r($dataEigeneAngebote);
            $this->dataEigeneAngebote = $dataEigeneAngebote;
            switch ($action) {
                case 'anzeigen':
#echo "<br>viewEigeneAngebote->execute - anzeigen";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    if(isset($_POST['Angebotsjahr'])) {
                        $selectedJahr = $_POST['Angebotsjahr'];
                    } else {
                        $selectedJahr = $_SESSION['Angebotsjahre'][0];
                    }
                    $Options = $this->getHtmlOptionsAngebotsjahre($selectedJahr);
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsJahr();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "EigeneAngebote", $this->auswaehlenAngebotsjahr, -1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "anzeigen", $this->auswaehlenAngebotsjahr, -1);
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";
                    $Content .= $this->auswaehlenAngebotsjahr."<br>";

                    if(!empty($dataEigeneAngebote)) {
                        foreach($dataEigeneAngebote as $Index => $Angebot) {
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
                            if(strlen($Angebot['Kosten']) > 0) {
                                $value = $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp".$Angebot['Sternchen'];
                            } else {
                                $value = $Angebot['Kosten_Mitglied'];
                            }
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $value, 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
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

                case 'bearbeiten':
#echo "<br>viewEigeneAngebote->execute - bearbeiten";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    if(isset($_POST['Angebotsjahr'])) {
                        $selectedJahr = $_POST['Angebotsjahr'];
                    } else {
                        $selectedJahr = $_SESSION['Angebotsjahre'][0];
                    }
                    $Options = $this->getHtmlOptionsAngebotsjahre($selectedJahr);
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsJahr();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "EigeneAngebote", $this->auswaehlenAngebotsjahr, -1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "bearbeiten", $this->auswaehlenAngebotsjahr, -1);
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
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
                            if(strlen($Angebot['Kosten']) > 0) {
                                $value = $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp".$Angebot['Sternchen'];
                            } else {
                                $value = $Angebot['Kosten_Mitglied'];
                            }
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $value, 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
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
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->neu_aendernAngebotTemplate = $this->template->getNeu_AendernAngebotTemplate();
                    $this->neu_aendernAngebotTemplate = $this->template->render($this->neu_aendernAngebotTemplate, "/{{ ControlerAction }}/", "EigeneAngebote=aendern_speichern", 1);
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";

                    if(!empty($dataEigeneAngebote)) {
                        foreach($dataEigeneAngebote as $Index => $Angebot) {
                            $Jahr = array_key_first($Angebot);
                            $Angebot = $Angebot[$Jahr];
                            $_SESSION['EigenesAngebot']['Titel'] = $Angebot['Titel'];
                            $_SESSION['EigenesAngebot']['Untertitel'] = $Angebot['Untertitel'];
                            $_SESSION['EigenesAngebot']['Ort'] = $Angebot['Ort'];
                            $_SESSION['EigenesAngebot']['Termin'] = $Angebot['Termin'];
                            $_SESSION['EigenesAngebot']['Zeit'] = $Angebot['Zeit'];
                            $_SESSION['EigenesAngebot']['Teilnehmerzahl'] = $Angebot['Teilnehmerzahl'];
                            $_SESSION['EigenesAngebot']['Kosten_Mitglied'] = $Angebot['Kosten_Mitglied'];
                            $_SESSION['EigenesAngebot']['Kosten'] = $Angebot['Kosten'];
                            $_SESSION['EigenesAngebot']['Anmeldeschluss'] = $Angebot['Anmeldeschluss'];
                            $_SESSION['EigenesAngebot']['Beschreibung'] = $Angebot['Beschreibung'];
                            $_SESSION['EigenesAngebot']['Web_Info'] = (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']);
#echo "<br>viewEigeneAngebote - aendern - _SESSION[EigenesAngebot = "; print_r($_SESSION['EigenesAngebot']);
                            $tmp = $this->neu_aendernAngebotTemplate;
                            $tmp = $this->template->render($tmp, "/{{ Jahr }}/", $Jahr, -1);
                            $tmp = $this->template->render($tmp, "/{{ PSAngebot }}/", $Angebot['PSAngebot'], 1);
                            $tmp = $this->template->render($tmp, "/{{ PSBenutzer }}/", $Angebot['PSBenutzer'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ ".$Angebot['Untertitel']." }}/", 'selected', 1);
                            $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Benutzer }}/", $Angebot['Benutzer'], 1);
                            $tmp = $this->template->render($tmp, "/{{ eMail }}/", $Angebot['eMail'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Uhrzeit }}/", $Angebot['Zeit'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten_Mitglied }}/", $Angebot['Kosten_Mitglied'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
#echo "<br>dataEigeneAngebote = "; print_r($dataEigeneAngebote);
                            if(isset($dataEigeneAngebote['EigenesAngebot']['checkDataValue']['countErrors'])) {
                                if($dataEigeneAngebote['EigenesAngebot']['checkDataValue']['countErrors'] < 0) {
                                    $this->setTagsErrorMessages($dataEigeneAngebote['EigenesAngebot']['checkDataValue'], $tmp);
                                }
                            }

                            if($_SESSION['dataAngebotsjahre'][$Jahr]['eintragen'] == 0) {
                                $button = '<tr bgcolor="#ffffcc">';
                                $button .= '<td colspan="3" align="center">';
                                $button .= '<form action="/index.php?EigeneAngebote=speichern" method="post">';
                                #$button .= '<input type="button" name="aendern" value="ändern"';
                                #$button .= ' onClick="self.location.href='."'https://angeboteundtouren.dav-hagen.de/index.php?EigeneAngebote=bearbeiten'.'">'';
                                $button .= ' <input type="hidden" name="PSJahr" value="{{ Jahr }}">';
                                $button .= ' <input type="hidden" name="PSBenutzer" value="{{ PSBenutzer }}">';
                                $button .= ' <input type="hidden" name="PSAngebot" value="{{ PSAngebot }}">';
                                #$button .= ' <input type="hidden" name="Formular" value="Angebot im Formular bearbeiten">';
                                $button .= ' <input type="submit" name="speichern" value="speichern">';
                                $button .= ' </form></td></tr>';
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

                case 'aendern_speichern':
#echo "<br>viewEigeneAngebote - case aendern_speichern<br>";
#echo "<br>Formulardaten in POST = "; print_r($_POST);
                    if (is_file("../Core/bitStructAngebot.php")) {
                        include_once "../Core/bitStructAngebot.php";
                    }
#echo "<br>#### aendern_speichern dataEigeneAngebote = "; print_r($dataEigeneAngebote);
                    #if(isset($dataEigeneAngebote['EigenesAngebot']['checkDataValue'])) {
                        $bitStructAngebot = unserialize($dataEigeneAngebote['EigenesAngebot']['checkDataValue']);
                    #}
#echo "<br>## bitStructAngebot = "; print_r($bitStructAngebot);
                    $this->webpage = $this->template->getIndexTemplate();

                    if($bitStructAngebot->countErrors() < 0) {

                        $this->neu_aendernAngebotTemplate = $this->template->getNeu_AendernAngebotTemplate();
                        $this->neu_aendernAngebotTemplate = $this->template->render($this->neu_aendernAngebotTemplate, "/{{ ControlerAction }}/", "EigeneAngebote=aendern_speichern", 1);
                        $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";

                        if(!empty($dataEigeneAngebote)) {
                            foreach($dataEigeneAngebote as $Index => $Angebot) {
#echo "<br>viewEigeneangebote-ändern_speichern - Angebote = "; print_r($Angebot); echo "<br>";
#echo "<br>##viewEigensAngebot=aender_speichern - Index = $Index - dataEigeneAngebote = "; print_r($dataEigeneAngebote);
                                $tmp = $this->neu_aendernAngebotTemplate;
                                $tmp = $this->template->render($tmp, "/{{ Jahr }}/", $Angebot['PSJahr'], -1);
                                $tmp = $this->template->render($tmp, "/{{ PSAngebot }}/", $Angebot['PSAngebot'], 1);
                                $tmp = $this->template->render($tmp, "/{{ PSBenutzer }}/", $Angebot['PSBenutzer'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Benutzer }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                                $tmp = $this->template->render($tmp, "/{{ ".$Angebot['Untertitel']." }}/", 'selected', 1);
                                $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Name }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                                $tmp = $this->template->render($tmp, "/{{ eMail }}/", $_SESSION['eMail'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Uhrzeit }}/", $Angebot['Zeit'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Kosten_Mitglied }}/", $Angebot['Kosten_Mitglied'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                            }
#echo "<br>viewEigeneAngebote";
#echo "<br>aender_speichern";
#echo "<br>Angebote = "; print_r($Angebot);
#echo "<br>tmp = "; print_r($tmp); echo "<br>";
                            $this->setTagsErrorMessages($Angebot, $tmp);
                            $Content .= $tmp."<br>";
#echo "<br>### neu_aendernAngebotTemplate:<br>$Content";
                            $this->getHtmlMenue();
                            $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                            $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                            $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                            echo $this->webpage;            
                            
                        }
                    } else {
                        $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
                        $this->anzeigenAngeboteTemplate = $this->template->render($this->anzeigenAngeboteTemplate, "/{{ ControlerAction }}/", "EigeneAngebote=aendern_speichern", 1);
                        $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";
                        
                        if(!empty($dataEigeneAngebote)) {
                            foreach($dataEigeneAngebote as $Index => $Angebot) {
                            #$Jahr = array_key_first($Angebot);
                            #$Angebot = $Angebot[$Jahr];
                            $tmp = $this->anzeigenAngeboteTemplate;
                            $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Untertitel }}/", $Angebot['Untertitel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Name }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                            $tmp = $this->template->render($tmp, "/{{ eMail }}/", $_SESSION['eMail'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Zeit }}/", $Angebot['Zeit'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                            if(strlen($Angebot['Kosten']) > 0) {
                                $value = $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp(n.M.)";
                            } else {
                                $value = $Angebot['Kosten_Mitglied'];
                            }
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $value, 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                            $Content .= $tmp."<br>";
                            }

                            $this->getHtmlMenue();
                            $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                            $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                            $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                            echo $this->webpage;            
                        
                        }
                    }   
                break;

                case 'aendern_gespeichert':
#echo "<br>viewEigeneAngebote - case aendern_gespeichert<br>";
#echo "<br>#### aendern_gespeichert - dataAngebot = "; print_r($dataEigeneAngebote);
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."- aendern_gespeichert</b></p>";
                    if(!empty($dataEigeneAngebote)) {
                        foreach($dataEigeneAngebote as $Index => $Angebot) {
#echo "<br>#### Index = $Index - Angebot = "; print_r($Angebot);
                            #$Jahr = array_key_first($Angebot);
                            #$Angebot = $Angebot[$Jahr];
                            $tmp = $this->anzeigenAngeboteTemplate;
                            $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Untertitel }}/", $Angebot['Untertitel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Name }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                            $tmp = $this->template->render($tmp, "/{{ eMail }}/", $_SESSION['eMail'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Zeit }}/", $Angebot['Zeit'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp(n.M.)", 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                        }
                        $Content .= $tmp;
                    }
                    $this->getHtmlMenue();
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;

                break;

                case "neu":
#echo "<br>#### viewEigeneAngebote-neu";
                    if($error == -1) {
                        $this->webpage = $this->template->getIndexTemplate();
                        $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";
                        $Content .= '<p align="center"><font color="red"><b>Es ist kein Jahr für das Eingeben von Angeboten freigegeben!</b></font></p>';
                        $this->getHtmlMenue();
                        $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                        $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                        $this->webpage = $this->template->render($this->webpage, "/{{ .* }}/", "", -1);
                        echo $this->webpage;
                    } else {
                        $this->webpage = $this->template->getIndexTemplate();
                        $this->neu_aendernAngebotTemplate = $this->template->getNeu_AendernAngebotTemplate();
                        $this->neu_aendernAngebotTemplate = $this->template->render($this->neu_aendernAngebotTemplate, "/{{ ControlerAction }}/", "EigeneAngebote=neu_speichern", 1);
                        $this->auswaehlenEigenesAngebot = $this->template->getAuswaehlenEigenesAngebot();
                        $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";
                        $tmp = $this->neu_aendernAngebotTemplate;
                        $tmp = $this->template->render($tmp, "/{{ Jahr }}/", $dataEigeneAngebote['EigenesAngebot']['PSJahr'], -1);
                        $tmp = $this->template->render($tmp, "/{{ PSBenutzer }}/", $_SESSION['PSBenutzer'], 1);
                        $tmp = $this->template->render($tmp, "/{{ Benutzer }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                        $tmp = $this->template->render($tmp, "/{{ eMail }}/", $_SESSION['eMail'], 1);
                        $tmp = $this->template->render($tmp, "/{{ Titel }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Ort }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Termin }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Uhrzeit }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Kosten_Mitglied }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Kosten }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", "", 1);
                        $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", "", 1);

                        $Content .= $tmp;
#echo "<br>$Content";
                        $this->getHtmlMenue();
                        $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                        $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                        $this->webpage = $this->template->render($this->webpage, "/{{ .* }}/", "", -1);
                        echo $this->webpage;
                    }

                break;

                case 'neu_speichern':
#echo "<br>viewEigeneAngebote - case neu_speichern<br>";
                    if (is_file("../Core/bitStructAngebot.php")) {
                        include_once "../Core/bitStructAngebot.php";
                    }
#echo "<br>controlerEigeneAngebote - case 'neu_speichern'<br>";
#print_r($dataEigeneAngebote); echo "<br>Array-Keys(dataEigeneAngebote = ";
#print_r(array_keys($dataEigeneAngebote)); echo "<br>";
#print_r(array_keys($dataEigeneAngebote['EigenesAngebot'])); echo "<br>";
#print_r(array_keys($dataEigeneAngebote['EigenesAngebot']['checkDataValue'])); echo "<br>";
#print_r($dataEigeneAngebote['EigenesAngebot']['checkDataValue']['bitStructAngebot']);

#                    $bitStructAngebot = unserialize($dataEigeneAngebote['EigenesAngebot']['checkDataValue']);
                    $bitStructAngebot = unserialize($dataEigeneAngebote['EigenesAngebot']['checkDataValue']['bitStructAngebot']);
#echo "<br>## bitStructAngebot = "; print_r($bitStructAngebot);
                    $this->webpage = $this->template->getIndexTemplate();

                    if($bitStructAngebot->countErrors() < 0) {

                        $this->neu_aendernAngebotTemplate = $this->template->getNeu_AendernAngebotTemplate();
                        $this->neu_aendernAngebotTemplate = $this->template->render($this->neu_aendernAngebotTemplate, "/{{ ControlerAction }}/", "EigeneAngebote=neu_speichern", 1);
                        $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";

                        if(!empty($dataEigeneAngebote)) {
                            foreach($dataEigeneAngebote as $Index => $Angebot) {
#echo "<br>##viewEigensAngebot=aender_speichern - Index = $Index - dataEigeneAngebote = "; print_r($dataEigeneAngebote);
                                $tmp = $this->neu_aendernAngebotTemplate;
                                $tmp = $this->template->render($tmp, "/{{ Jahr }}/", $Angebot['PSJahr'], -1);
                                $tmp = $this->template->render($tmp, "/{{ PSAngebot }}/", $Angebot['PSAngebot'], 1);
                                $tmp = $this->template->render($tmp, "/{{ PSBenutzer }}/", $Angebot['PSBenutzer'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Benutzer }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                                $tmp = $this->template->render($tmp, "/{{ ".$Angebot['Untertitel']." }}/", 'selected', 1);
                                $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Name }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                                $tmp = $this->template->render($tmp, "/{{ eMail }}/", $_SESSION['eMail'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Uhrzeit }}/", $Angebot['Zeit'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Kosten_Mitglied }}/", $Angebot['Kosten_Mitglied'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                                $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                            }
                            $this->setTagsErrorMessages($Angebot, $tmp);
                            $Content .= $tmp."<br>";
#echo "<br>### neu_aendernAngebotTemplate:<br>$Content";
                            $this->getHtmlMenue();
                            $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                            $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                            $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                            echo $this->webpage;            
                            
                        } else {
                            goto NEUGESPEICHERT;
                        }
                    }

                break;

                case 'neu_gespeichert':
                    NEUGESPEICHERT:
#echo "<br>neu_gespeichert<br>";
                    #$nachricht = "Neues Angebot: $Angebot['PSJahr'], $Angebot['PSAngebot'], $_SESSION['Vorname']." ".$_SESSION['Nachname'], $Angebot['Titel'], $Angebot['Untertitel'], $Angebot['Ort'], $Angebot['Termin']";
                    #$nachricht = wordwrap($nachricht, 70, "\r\n");
                    #mail('hans-werner.treppmann@dav-hagen.de', 'neues angebot in AusbildungUndTouren', $nachricht);
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."- neu_gespeichert</b></p>";
                    if(!empty($dataEigeneAngebote)) {
                        foreach($dataEigeneAngebote as $Index => $Angebot) {
                            #$Jahr = array_key_first($Angebot);
                            #$Angebot = $Angebot[$Jahr];
                            $tmp = $this->anzeigenAngeboteTemplate;
                            $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Untertitel }}/", $Angebot['Untertitel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Termin }}/", $Angebot['Termin'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Name }}/", $_SESSION['Vorname']." ".$_SESSION['Nachname'], 1);
                            $tmp = $this->template->render($tmp, "/{{ eMail }}/", $_SESSION['eMail'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Zeit }}/", $Angebot['Zeit'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp(n.M.)", 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                        }
                        $Content .= $tmp;
                    }
                    $this->getHtmlMenue();
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;

                break;

                case 'kopieren':
#echo "<br>viewEigeneAngebote - case kopieren<br>";
#echo "<br>_SESSION = "; print_r($_SESSION); echo "<br>";
#echo "<br>viewEigeneAngebote->execute - kopieren";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    if(isset($_POST['Angebotsjahr'])) {
                        $selectedJahr = $_POST['Angebotsjahr'];
                    } else {
                        $selectedJahr = $_SESSION['Angebotsjahre'][0];
                    }
                    $Options = $this->getHtmlOptionsAngebotsjahre($selectedJahr);
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsJahr();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "EigeneAngebote", $this->auswaehlenAngebotsjahr, -1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "kopieren", $this->auswaehlenAngebotsjahr, -1);
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
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
                            if(strlen($Angebot['Kosten']) > 0) {
                                $value = $Angebot['Kosten_Mitglied']."<br>&nbsp&nbsp&nbsp&nbsp&nbsp".$Angebot['Kosten']."&nbsp".$Angebot['Sternchen'];
                            } else {
                                $value = $Angebot['Kosten_Mitglied'];
                            }
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $value, 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", $Angebot['Anmeldeschluss'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
                            #if($_SESSION['dataAngebotsjahre'][$Jahr]['eintragen'] == 0) {
                                $button = '<tr bgcolor="#ffffcc">';
                                $button .= '<td colspan="3" align="center">';
                                $button .= '<form action="/index.php?EigeneAngebote=neu_kopie" method="post">';
                                $button .= ' <input type="hidden" name="PSJahr" value="{{ Jahr }}">';
                                $button .= ' <input type="hidden" name="PSBenutzer" value="{{ PSBenutzer }}">';
                                $button .= ' <input type="hidden" name="PSAngebot" value="{{ PSAngebot }}">';
                                $button .= ' <input type="hidden" name="Formular" value="Angebot für NEU wählen">';
                                $button .= ' <input type="submit" name="kopieren" value="kopieren">';
                                $button .= ' </form></td></tr>';
                                $button = $this->template->render($button, "/{{ Jahr }}/", $Jahr, 1);
                                $button = $this->template->render($button, "/{{ PSBenutzer }}/", $Angebot['PSBenutzer'], 1);
                                $button = $this->template->render($button, "/{{ PSAngebot }}/", $Angebot['PSAngebot'], 1);
                            #} else {
                            #    $button = '<tr bgcolor="#ffffcc">';
                            #    $button .= '<td colspan="3" align="center">';
                            #    $button .= '<p align="center"><font color="red"><b>Kopieren der Angebote für das Jahr '.$Jahr.' ist nicht aktiv!</b></font></p>';
                            #    $button .= '</td></tr>';
                            #}
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

                case 'neu_kopie':
#echo "<br>viewEigeneAngebote - case neu_kopie<br>";
#echo "<br>_SESSION = "; print_r($_SESSION); echo "<br>";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->neu_aendernAngebotTemplate = $this->template->getNeu_KopierenAngebotTemplate();
                    $this->neu_aendernAngebotTemplate = $this->template->render($this->neu_aendernAngebotTemplate, "/{{ ControlerAction }}/", "EigeneAngebote=neu_speichern", 1);
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";

                    if(!empty($dataEigeneAngebote)) {
                        foreach($dataEigeneAngebote as $Index => $Angebot) {
                            $Jahr = array_key_first($Angebot);
                            $Angebot = $Angebot[$Jahr];
                            $_SESSION['EigenesAngebot']['Titel'] = $Angebot['Titel'];
                            $_SESSION['EigenesAngebot']['Untertitel'] = $Angebot['Untertitel'];
                            $_SESSION['EigenesAngebot']['Ort'] = $Angebot['Ort'];
                            $_SESSION['EigenesAngebot']['Termin'] = $Angebot['Termin'];
                            $_SESSION['EigenesAngebot']['Zeit'] = $Angebot['Zeit'];
                            $_SESSION['EigenesAngebot']['Teilnehmerzahl'] = $Angebot['Teilnehmerzahl'];
                            $_SESSION['EigenesAngebot']['Kosten_Mitglied'] = $Angebot['Kosten_Mitglied'];
                            $_SESSION['EigenesAngebot']['Kosten'] = $Angebot['Kosten'];
                            $_SESSION['EigenesAngebot']['Anmeldeschluss'] = $Angebot['Anmeldeschluss'];
                            $_SESSION['EigenesAngebot']['Beschreibung'] = $Angebot['Beschreibung'];
                            $_SESSION['EigenesAngebot']['Web_Info'] = is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info'];
#echo "<br>viewEigeneAngebote - aendern - _SESSION[EigenesAngebot = "; print_r($_SESSION['EigenesAngebot']);
                            $tmp = $this->neu_aendernAngebotTemplate;
                            $tmp = $this->template->render($tmp, "/{{ Jahr }}/", $_SESSION['neustesJahr'], -1);
                            $tmp = $this->template->render($tmp, "/{{ PSAngebot }}/", $Angebot['PSAngebot'], 1);
                            $tmp = $this->template->render($tmp, "/{{ PSBenutzer }}/", $Angebot['PSBenutzer'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Titel }}/", $Angebot['Titel'], 1);
                            $tmp = $this->template->render($tmp, "/{{ ".$Angebot['Untertitel']." }}/", 'selected', 1);
                            $tmp = $this->template->render($tmp, "/{{ Ort }}/", $Angebot['Ort'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Termin }}/", ".".substr($_SESSION['neustesJahr'],-2), 1);
                            $tmp = $this->template->render($tmp, "/{{ Benutzer }}/", $Angebot['Benutzer'], 1);
                            $tmp = $this->template->render($tmp, "/{{ eMail }}/", $Angebot['eMail'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Uhrzeit }}/", "", 1);
                            $tmp = $this->template->render($tmp, "/{{ Teilnehmerzahl }}/", $Angebot['Teilnehmerzahl'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten_Mitglied }}/", $Angebot['Kosten_Mitglied'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Kosten }}/", $Angebot['Kosten'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Anmeldeschluss }}/", "", 1);
                            $tmp = $this->template->render($tmp, "/{{ Beschreibung }}/", $Angebot['Beschreibung'], 1);
                            $tmp = $this->template->render($tmp, "/{{ Web-Info }}/", (is_null($Angebot['Web_Info']) ? "" : $Angebot['Web_Info']), 1);
#echo "<br>dataEigeneAngebote = "; print_r($dataEigeneAngebote);
                            if(isset($dataEigeneAngebote['EigenesAngebot']['checkDataValue']['countErrors'])) {
                                if($dataEigeneAngebote['EigenesAngebot']['checkDataValue']['countErrors'] < 0) {
                                    $this->setTagsErrorMessages($dataEigeneAngebote['EigenesAngebot']['checkDataValue'], $tmp);
                                }
                            }

                            if($_SESSION['dataAngebotsjahre'][$Jahr]['eintragen'] == 0) {
                                $button = '<tr bgcolor="#ffffcc">';
                                $button .= '<td colspan="3" align="center">';
                                $button .= '<form action="/index.php?EigeneAngebote=neu_speichern" method="post">';
                                $button .= ' <input type="hidden" name="PSJahr" value="{{ Jahr }}">';
                                $button .= ' <input type="hidden" name="PSBenutzer" value="{{ PSBenutzer }}">';
                                $button .= ' <input type="hidden" name="PSAngebot" value="{{ PSAngebot }}">';
                                $button .= ' <input type="submit" name="speichern" value="speichern">';
                                $button .= ' </form></td></tr>';
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
                    
            }
        }
    

        private function getHtmlOptionsAngebotsjahre($selectedJahr) {
            $tmp = "";
            foreach($_SESSION['Angebotsjahre'] as $i => $Jahr) {
                $tmp .= OPTION;
                $tmp = preg_replace('/{{ Angebotsjahr }}/', $Jahr, $tmp, 1);
                #if($Jahr == $_SESSION['EigenesAngebot']['PSJahr']) {
                if($Jahr == $selectedJahr) {
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
    

        private function setTagsErrorMessages($error, &$dataHome) {
            $errorTitel = '<font color="red" style="font-size:100%">Der Titel ist leer, enthält Sonderzeichen oder er ist zu lang!</font>';
            $errorOrt = '<font color="red" style="font-size:100%">Der Ort ist leer, enthält Sonderzeichen oder er ist zu lang!</font>';
            $errorTermin = '<font color="red" style="font-size:100%">Der Termin entspricht nicht der vorgegebenen Syntax!</font>';
            $errorZeit = '<font color="red" style="font-size:100%">Die Uhrzeit entspricht nicht der vorgegebenen Syntax!</font>';
            $errorTeilnehmerzahl = '<font color="red" style="font-size:100%">Die Teilnehmerzahl entspricht nicht der vorgegebenen Syntax!</font>';
            $errorKosten_Mitglied = '<font color="red" style="font-size:100%">Der Kostenwert entspricht nicht der vorgegebenen Syntax!</font>';
            $errorKosten = '<font color="red" style="font-size:100%">Der Kostenwert entspricht nicht der vorgegebenen Syntax!</font>';
            $errorAnmeldeschluss = '<font color="red" style="font-size:100%">Der Anmeldeschluss entspricht nicht der vorgegebenen Syntax!</font>';
            $errorBeschreibung = '<font color="red" style="font-size:100%">Die Beschreibung enthält keine Zeichen, Sonderzeichen oder sie ist zu lang!</font>';
            $errorWebInfo = '<font color="red" style="font-size:100%">Die Web-Info enthält Sonderzeichen oder sie ist zu lang!</font>';
#echo "<br>#### error = "; print_r($error);
            if (is_file("../Core/bitStructAngebot.php")) {
                include_once "../Core/bitStructAngebot.php";
            }
#echo "<br>setTagsErrorMessages - error-Array-Keys<br>"; print_r(array_keys($error)); echo "<br>";
            $bitStructAngebot = unserialize($error['checkDataValue']['bitStructAngebot']);

            if($bitStructAngebot->checkTitel() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Titel }}/', $errorTitel, $dataHome, 1);
            }

            if($bitStructAngebot->checkOrt() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Ort }}/', $errorOrt, $dataHome, 1);
            }

            if($bitStructAngebot->checkTermin() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Termin }}/', $errorTermin, $dataHome, 1);
            }

            if($bitStructAngebot->checkZeit() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Uhrzeit }}/', $errorZeit, $dataHome, 1);
            }

            if($bitStructAngebot->checkTeilnehmerzahl() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Teilnehmerzahl }}/', $errorTeilnehmerzahl, $dataHome, 1);
            }

            if($bitStructAngebot->checkKosten_Mitglied() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Kosten_Mitglied }}/', $errorKosten_Mitglied, $dataHome, 1);
            }

            if($bitStructAngebot->checkKosten() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Kosten }}/', $errorKosten, $dataHome, 1);
            }

            if($bitStructAngebot->checkAnmeldeschluss() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Anmeldeschluss }}/', $errorAnmeldeschluss, $dataHome, 1);
            }

            if($bitStructAngebot->checkBeschreibung() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Beschreibung }}/', $errorBeschreibung, $dataHome, 1);
            }

            if($bitStructAngebot->checkWeb_Info() < 0) {
                $dataHome = preg_replace('/{{ ERROR_Web-Info }}/', $errorWebInfo, $dataHome, 1);
            }

#echo "<br>#### setTagsErrorMessages = "; print_r($dataHome);
        }
}
            
