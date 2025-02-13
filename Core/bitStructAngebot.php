<?php

class bitStructAngebot {
    private int $Titel;
    private int $Untertitel;
    private int $Ort;
    private int $Termin;
    private int $Zeit;
    private int $Teilnehmerzahl;
    private int $Kosten_Mitglied;
    private int $Kosten;
    private int $Anmeldeschluss;
    private int $Beschreibung;
    private int $Web_Info;

    public function __construct() {
        $this->Titel = 0;
        $this->Untertitel = 0;
        $this->Ort = 0;
        $this->Termin = 0;
        $this->Zeit = 0;
        $this->Teilnehmerzahl = 0;
        $this->Kosten_Mitglied = 0;
        $this->Kosten = 0;
        $this->Anmeldeschluss = 0;
        $this->Beschreibung = 0;
        $this->Web_Info = 0;
    }

    public function falseTitel() {
        $this->Titel = -1;
    }

    public function falseUntertitel() {
        $this->Untertitel = -1;
    }

    public function falseOrt() {
        $this->Ort = -1;
    }

    public function falseTermin() {
        $this->Termin = -1;
    }

    public function falseZeit() {
        $this->Zeit = -1;
    }

    public function falseTeilnehmerzahl() {
        $this->Teilnehmerzahl = -1;
    }

    public function falseKosten_Mitglied() {
        $this->Kosten_Mitglied = -1;
    }

    public function falseKosten() {
        $this->Kosten = -1;
    }

    public function falseAnmeldeschluss() {
        $this->Anmeldeschluss = -1;
    }

    public function falseBeschreibung() {
        $this->Beschreibung = -1;
    }

    public function falseWeb_Info() {
        $this->Web_Info = -1;
    }


    public function checkTitel() {
        return $this->Titel;
    }

    public function checkUntertitel() {
        return $this->Untertitel;
    }

    public function checkOrt() {
        return $this->Ort;
    }

    public function checkTermin() {
        return $this->Termin;
    }

    public function checkZeit() {
        return $this->Zeit;
    }

    public function checkTeilnehmerzahl() {
        return $this->Teilnehmerzahl;
    }

    public function checkKosten_Mitglied() {
        return $this->Kosten_Mitglied;
    }

    public function checkKosten() {
        return $this->Kosten;
    }

    public function checkAnmeldeschluss() {
        return $this->Anmeldeschluss;
    }

    public function checkBeschreibung() {
        return $this->Beschreibung;
    }

    public function checkWeb_Info() {
        return $this->Web_Info;
    }

    public function countErrors() {
        $i = 0;
        $i += $this->Titel;
        $i += $this->Untertitel;
        $i += $this->Ort;
        $i += $this->Termin;
        $i += $this->Zeit;
        $i += $this->Teilnehmerzahl;
        $i += $this->Kosten_Mitglied;
        $i += $this->Kosten;
        $i += $this->Anmeldeschluss;
        $i += $this->Beschreibung;
        $i += $this->Web_Info;

        return $i;
    }

}