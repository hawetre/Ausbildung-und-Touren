<?php

class modelRecht {
    private int $PSBenutzer;
    private string $Hauptfunktion;
    private string $Menueanzeige;
    private string $Controler;
    private string $Funktion;
    private string $Action;
    private string $Funktionsanzeige;
    #private array $Menue;

    public function __construct($PSBenutzer, $Hauptfunktion, $Menueanzeige, $Controler, $Funktion, $Action, $Funktionsanzeige) {

        $this->PSBenutzer = $PSBenutzer;
        $this->Hauptfunktion = $Hauptfunktion;
        $this->Menueanzeige = $Menueanzeige;
        $this->Controler = $Controler;
        $this->Funktion = $Funktion;
        $this->Action = $Action;
        $this->Funktionsanzeige = $Funktionsanzeige;
#echo "<br>Recht: $PSBenutzer - $Hauptfunktion - $Menueanzeige - $Controler - $Funktion - $Action - $Funktionsanzeige"; 
    }

    public function getPSBenutzer() {
        return $this->PSBenutzer;
    }

    public function getHauptfunktion() {
        return $this->Hauptfunktion;
    }

    public function getMenueanzeige() {
        return $this->Menueanzeige;
    }

    public function getControler() {
        return $this->Controler;
    }

    public function getFunktion() {
        return $this->Funktion;
    }

    public function getAction() {
        return $this->Action;
    }

    public function getFunktionsanzeige() {
        return $this->Funktionsanzeige;
    }

}