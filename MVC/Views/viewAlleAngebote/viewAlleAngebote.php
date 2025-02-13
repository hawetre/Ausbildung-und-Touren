<?php
    /**
     * Classe viewAlleAngebote für die Views
     */
    define("OPTION", "<option {{ selected }}>{{ Angebotsjahr }}</option>");
    define("HAUPTFUNKTION", "<h3>{{ Hauptfunktion }}</h3>");
    define("MENUESTART", "<ul>");
    define("FUNKTION", "<li><h3>{{ Funktion }}</h3></li>");
    define("MENUEENDE", "</ul>");

    class viewAlleAngebote {
        private Template $template;
        private array $dataAlleAngebote;
        private string $menue;
        private string $auswaehlenAngebotsjahr;
        private string $anzeigenAngeboteTemplate;
        private string $webpage;

        public function __construct() {
            $this->webpage = "";
            $this->menue = "";
            $this->dataAlleAngebote = [];
            $pathfile = "../MVC/Views/Templates/Template.php";
            include_once $pathfile;
            $this->template = new Template();
        }

        public function execute($action, $dataAlleAngebote, $error) {
            $this->dataAlleAngebote = $dataAlleAngebote;
#echo "<br>#### viewAlleAngebote - Action = $action";
            switch ($action) {
                case 'anzeigen':
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    $Options = $this->getHtmlOptionsAngebotsjahre();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsJahr();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "AlleAngebote", $this->auswaehlenAngebotsjahr, -1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "anzeigen", $this->auswaehlenAngebotsjahr, -1);
                    $this->anzeigenAngeboteTemplate = $this->template->getAnzeigenAngeboteTemplate();
#                    $this->anzeigenAngeboteTemplate = preg_replace('/{{ option }}/', $Options, $this->anzeigenAngeboteTemplate, 1);
                    # Formular-Template mit select-Box und Button
                    $Content = '<p align="center"><b>'.$_SESSION['Controler']."-".$_SESSION['Action']."</b></p>";
                    $Content .= $this->auswaehlenAngebotsjahr."<br>";

                    if(!empty($dataAlleAngebote)) {
                        foreach($dataAlleAngebote as $Index => $Angebot) {
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

                case 'csvExport':
#echo "<br>viewAlleAngebote->csvExport";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    $Options = $this->getHtmlOptionsAngebotsjahre();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsJahr();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "AlleAngebote", $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "csvExport", $this->auswaehlenAngebotsjahr, -1);
                    $this->getHtmlMenue();

                    # Datei für csv-Export öffnen
                    date_default_timezone_set('UTC');
                    $csvDatei = 'Export_Angebote-'.array_key_first($dataAlleAngebote[0]).'_'.date('Y-m-d').'.csv';
                    #$csvDatei = '../MVC/Views/AlleAngebote/Export_'.date('Y-m-d').'.csv';
#echo "<br>csvDatei = $csvDatei<br>";
#print_r($dataAlleAngebote);
#echo "<br>Jahr = "; echo array_key_first($dataAlleAngebote[0]);
                    $handle = fopen($csvDatei, "w");
                    $tmp = "Titel|Untertitel|Wo|Wann|Teilnehmer|Zeit|Kosten|Kosten Nichtmitglieder|Sternchen|Anmeldeschluss|Leiter|E-Mail|Bild|Beschreibung Text".chr(13).chr(10);
                    fwrite($handle, $tmp);
#echo "<br>".$tmp."<br><br>";
                    $Content = $this->auswaehlenAngebotsjahr."<br>";
                    foreach($dataAlleAngebote as $Index => $Angebot) {
                        #echo "<br>Index = $Index - Angebot = "; print_r($Angebot);
                        $Jahr = array_key_first($Angebot);
                        $Angebot = $Angebot[$Jahr];
                        $tmp = $Angebot['Titel']."|";
                        $tmp .= is_null($Angebot['Untertitel']) ? "" : $Angebot['Untertitel']."|";
                        $tmp .= $Angebot['Ort']."|";
                        $tmp .= $Angebot['Termin']."|";
                        $tmp .= $Angebot['Teilnehmerzahl']."|";
                        $tmp .= $Angebot['Zeit']."|";
                        $tmp .= $Angebot['Kosten_Mitglied']."|";
                        $tmp .= $Angebot['Kosten']."|";
                        $tmp .= $Angebot['Sternchen']."|";
                        $tmp .= $Angebot['Anmeldeschluss']."|";
                        $tmp .= $Angebot['Benutzer']."|";
                        $tmp .= $Angebot['eMail']."|";
                        $tmp .= "Bild|";
                        $tmp .= $Angebot['Beschreibung'];
                        $tmp = preg_replace('/[\n\x{0d}]/', '', $tmp);
                        $tmp .= chr(13).chr(10);
                        #if (fwrite($handle, utf8_encode(preg_replace('/[\n\x{0d}]/', '', $tmp))) === FALSE) {
                        if (fwrite($handle, $tmp) === FALSE) {
                            $tmp = "Fehler beim Schreiben in die Datei $csvDatei.";
                            $Content = $tmp;
                            break;
                        } else {
#echo "<br>Angebot in scvDatei geschrieben";
                            $Content .= $tmp."<br><br>";
                        }
#echo utf8_encode($tmp)."<br><br>";
                    }
                    fclose($handle);
                    $download = "csv-Datei <a href=".'"'.$csvDatei.'" download><font color="#FF0000"><b>'.$csvDatei.'</b></font></a> herunterladen';
                    $download .= "<br><br>Dokumentation: <a href=".'"csv-Export-und-Excel-Datei erstellen.pdf" download><font color="#0000FF"><b>csv-Export-und-Excel-Datei erstellen.pdf</b></font></a>';
#echo "<br>Downloadlink: $download";
                    $Content = $download.$Content;
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;
                    break;

                case 'htmlExport':
#echo "<br>#### viewAlleAngebote-html-Export";
                    $this->webpage = $this->template->getIndexTemplate();
                    $this->auswaehlenAngebotsjahr = $this->template->getAuswaehlenAngebotsjahr();
                    $Options = $this->getHtmlOptionsAngebotsjahre();
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ option }}/', $Options, $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Controler }}/', "AlleAngebote", $this->auswaehlenAngebotsjahr, 1);
                    $this->auswaehlenAngebotsjahr = preg_replace('/{{ Action }}/', "htmlExport", $this->auswaehlenAngebotsjahr, -1);
                    $this->getHtmlMenue();

                    # Datei für csv-Export öffnen
                    date_default_timezone_set('UTC');
                    $htmlDatei = 'Export_Angebote-'.array_key_first($dataAlleAngebote[0]).'_'.date('Y-m-d').'.txt';

                    $handle = fopen($htmlDatei, "w");
                    #$Content = $this->auswaehlenAngebotsjahr."<br>";
                    $Content = "";
                    foreach($dataAlleAngebote as $Index => $Angebot) {
#echo "<br>Index = $Index - Angebot = "; print_r($Angebot);
                        $Jahr = array_key_first($Angebot);
#echo "<br>#### Jahr = $Jahr - Angebot = "; print_r($Angebot);
                        $Angebot = $Angebot[$Jahr];
                        $tmp = $this->template->getHtmlExportTemplate();
                        $tmp = preg_replace('/{{ Jahr }}/', $Jahr, $tmp, 1);
                        $tmp = preg_replace('/{{ Titel }}/', $Angebot['Titel'], $tmp, 1);
                        $tmp = preg_replace('/{{ Unteritel }}/', $Angebot['Untertitel'], $tmp, 1);
                        $tmp = preg_replace('/{{ Beschreibung }}/', $Angebot['Beschreibung'], $tmp, 1);
                        $tmp = preg_replace('/{{ Ort }}/', $Angebot['Ort'], $tmp, 1);
                        $tmp = preg_replace('/{{ Termin }}/', $Angebot['Termin'], $tmp, 1);
                        $tmp = preg_replace('/{{ Teilnehmerzahl }}/', $Angebot['Teilnehmerzahl'], $tmp, 1);
                        $tmp = preg_replace('/{{ Zeit }}/', $Angebot['Zeit'], $tmp, 1);
                        $tmp = preg_replace('/{{ Kosten_Mitglied }}/', $Angebot['Kosten_Mitglied'], $tmp, 1);
                        $tmp = preg_replace('/{{ Kosten }}/', $Angebot['Kosten'], $tmp, 1);
                        $tmp = preg_replace('/{{ Anmeldeschluss }}/', $Angebot['Anmeldeschluss'], $tmp, 1);
                        if(is_null($Angebot['Web_Info']) || strlen($Angebot['Web_Info'])==0) {
                        	$tmp = preg_replace('/<h4>weitere Informationen<\/h4>/', "", $tmp, 1);
                        	$tmp = preg_replace('/{{ Web_Info }}/', "", $tmp, 1);
                        } else {
                        	$tmp = preg_replace('/{{ Web_Info }}/', $Angebot['Web_Info'], $tmp, 1);
                        }
                        $tmp = preg_replace('/{{ PersonasID }}/', $_SESSION['PersonasID'], $tmp, 1);
                        $tmp .= chr(13).chr(10).chr(13).chr(10);
                        if (fwrite($handle, $tmp) === FALSE) {
                            $tmp = "Fehler beim Schreiben in die Datei $csvDatei.";
                            $Content .= $tmp;
                            break;
                        } else {
                            $Content .= $tmp.chr(13).chr(10);
#echo "<br>#### $tmp";
                        }
                    }
                    fclose($handle);
                    $Content = $this->template->render($Content, "/</", '&lt;', -1);
                    $Content = $this->template->render($Content, "/>/", '&gt;', -1);
                    $Content = $this->template->render($Content, "[\n\x{0d}]", '<br><br>', -1);
                    $download = '<p align="center">html-Datei <a href='.'"'.$htmlDatei.'" download><font color="#FF0000"><b>'.$htmlDatei.'</b></font></a> herunterladen</p>';
                    $Content = $this->auswaehlenAngebotsjahr."<br>".$download."<br>".$Content;
                    $this->webpage = $this->template->render($this->webpage, "/{{ content }}/", $Content, 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{ menue }}/", "$this->menue", 1);
                    $this->webpage = $this->template->render($this->webpage, "/{{.*}}/", "", -1);
                    echo $this->webpage;

                    break;
            }
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
            
