<?php
#_SESSION['Menue'] = Array ( [eigene Angebote] => Array ( [0] => Array ( [Controler] => EigeneAngebote [Action] => anzeigen [Funktion] => anzeigen [Funktionsanzeige] => anzeigen ) [1] => Array ( [Controler] => EigeneAngebote [Action] => bearbeiten [Funktion] => bearbeiten [Funktionsanzeige] => bearbeiten ) ) [alle Angebote] => Array ( [0] => Array ( [Controler] => AlleAngebote [Action] => anzeigen [Funktion] => anzeigen [Funktionsanzeige] => anzeigen ) ) [Angebotsjahre] => Array ( [0] => Array ( [Controler] => Angebotsjahre [Action] => anzeigen [Funktion] => anzeigen [Funktionsanzeige] => anzeigen ) ) )

function checkRights($controler, $action) {
#echo "<br>#### checkRights($controler, $action)";
    #$check = false;
    #if(isset($_SESSION['Menue'])) {
#echo "<br>"; print_r($_SESSION['Menue']);
        #foreach($_SESSION['Menue'] as $key => $valArray) {
        #    foreach($valArray as $key2 => $values) {
#echo "<br>####checkRights Controler = "; echo $values['Controler']; echo "  Action = "; echo $values['Action'];
       #     if($values['Controler'] == $controler && $values['Action'] == $action) {
        #        $check = true;
        #        break;
        #    }
       # }
        #}
    #}
    #return $check;
    return true;
}
?>
