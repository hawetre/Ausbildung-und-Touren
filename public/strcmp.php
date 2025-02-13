<?php

$Mail1 = "kommste_mit@dav-hagen.de";
$Mail2 = "Kommste_mit@DAV-Hagen.de";
echo "<br>Mail1 = $Mail1  -  Mail2 = $Mail2";
echo "<br>strcmp = "; echo strcmp(strtolower($Mail1), strtolower($Mail2));
if(strcmp(strtolower($Mail1), strtolower($Mail2)) === 0) {
    echo "<br>strcmp OK!";
} else {
    echo "<br>strcmp NICHT ok!";
}