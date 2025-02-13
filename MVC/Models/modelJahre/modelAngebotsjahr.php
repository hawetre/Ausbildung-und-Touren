<?php

class modelAngebotsjahr {
    private $dbh;
    private int $PSJahr;
    private int $eintragen;
    private string $Beginn;
    private string $Ende;
    private int $aktiv;

    public function __construct($dbh, $PSJahr, $eintragen, $Beginn, $Ende, $aktiv) {
        $this->dbh = $dbh;
        $this->PSJahr = $PSJahr;
        $this->eintragen = $eintragen;
        $this->Beginn = $Beginn;
        $this->Ende = $Ende;
        $this->aktiv = $aktiv;
    }

    public function setPSJahr($PSJahr) {
        $this->PSJahr = $PSJahr;
    }

    public function setEintragen($eintragen) {
        $this->eintragen = $eintragen;
    }

    public function setBeginn($Beginn) {
        $this->Beginn = $Beginn;
    }

    public function setEnde($Ende) {
        $this->Ende = $Ende;
    }

    public function setAktiv($aktiv) {
        $this->aktiv = $aktiv;
    }

    public function getPSJahr() {
        return $this->PSJahr;
    }

    public function getEintragen() {
        return $this->eintragen;
    }

    public function getBeginn() {
        return $this->Beginn;
    }

    public function getEnde() {
        return $this->Ende;
    }

    public function getAktiv() {
        return $this->aktiv;
    }

    public function loadAngebotsjahr($Jahr) {
        $sql = "SELECT * FROM `angebotsjahre` WHERE PSJahr=$Jahr AND aktiv=0";
        if(is_object(($result = $this->dbh->query($sql)))) {
            $obj = $result->fetch_object();
            $this->PSJahr = $obj->PSJahr;
            $this->eintragen = $obj->eintragen;
            $this->Beginn = $obj->Beginn;
            $this->Ende = $obj->Ende;
            $this->aktiv = $obj->aktiv;
        } else {
            $this->PSJahr = 0;
            $this->eintragen = -1;;
            $this->Beginn = "0000-00-00";
            $this->Ende = "0000-00-00";
            $this->aktiv = -1;
        }
    }

    public function getAngebotsjahr() {
        $dataAngebotsjahr = [];
        # $dataAngebotsjahr[PSJahr], ...[eintragen], ...[Beginn], ...[Ende], ...[aktiv]
        # nicht vorhanden []
        if($this->PSJahr > 0) {
            $dataAngebotsjahr["PSJahr"] = $this->PSJahr;
            $dataAngebotsjahr["eintragen"] = $this->eintragen;
            $dataAngebotsjahr["Beginn"] = $this->Beginn;
            $dataAngebotsjahr["Ende"] = $this->Ende;
            $dataAngebotsjahr["aktiv"] = $this->aktiv;
        }
        return $dataAngebotsjahr;
    }

    public function saveAngebotsjahr($PSJahr, $eintragen, $Beginn, $Ende, $aktiv) {
        $sql = "INSERT INTO angebotsjahre VALUES ('".$PSJahr."','".$eintragen."','".$Beginn."','".$Ende."','".$aktiv."')";
        return $this->dbh->query($sql);
    }

}