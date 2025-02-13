<?php
    $webpage = file_get_contents("stringlaenge.html");
#echo "<br>_POST = "; print_r($_POST);
    if(isset($_POST['Stringlaenge'])) {
        $String = $_POST['Stringlaenge'];
        $Laenge = strlen($_POST['Stringlaenge']);
        if($Laenge > 840) {
            $Str1 = substr($String, 0, 840);
            $Str2 = substr($String, 840);
            $String = '<span style="color: blue;">'.$Str1.'</span><span style="color: red;">'.$Str2.'</span>'; 
            #echo "<br>$Str1";
            #echo "<br>$Str2";    
        }
    } else {
        $String = "";
        $Laenge = 0;
    }

    $webpage = preg_replace("/{{ Laenge }}/", $String."<br>".$Laenge, $webpage, 1);

    echo $webpage;  
