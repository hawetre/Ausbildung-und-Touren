<?php
    /**
     * Class Template
     */
    //echo "<br>Klasse Template gestartet";

    class Template {

        private string $indexTemplate;
        private string $loginFormular;
        private string $neuAngebot;
        private string $anzeigenAngebote;
        private string $aendernAngebot;
        private string $auswaehlenAngebotsjahr;
        private string $anzeigenAngeboteTemplate;
        private string $anzeigenAngebotsjahreTemplate;
        private string $anzeigenAngebotsBenutzerTemplate;
        private string $neu_aendernAngebotTemplate;
        private string $htmlExportTemplate;

        public function __construct() {
            $pathfile = '../MVC/Views/Templates/indexTemplate.html';
            $this->indexTemplate = file_get_contents($pathfile);
        }

        public function getIndexTemplate() {
            return $this->indexTemplate;
        }

        public function getLoginFormular() {
            $pathfile = '../MVC/Views/Templates/HomeAnmelden.html';
            return ($this->loginFormular = file_get_contents($pathfile));
        }

        public function getAuswaehlenAngebotsJahr() {
            $pathfile = '../MVC/Views/Templates/auswaehlenAngebotsJahr.html';
            return ($this->auswaehlenAngebotsjahr = file_get_contents($pathfile));
        }

        public function getAnzeigenAngeboteTemplate() {
            $pathfile = '../MVC/Views/Templates/anzeigenAngeboteTemplate.html';
            return ($this->anzeigenAngeboteTemplate = file_get_contents($pathfile));
        }

        public function getAngebotsjahreTemplate() {
            $pathfile = '../MVC/Views/Templates/anzeigenAngebotsjahreTemplate.html';
            return ($this->anzeigenAngebotsjahreTemplate = file_get_contents($pathfile));
        }

        public function getNeu_AendernAngebotTemplate() {
            $pathfile = '../MVC/Views/Templates/neu_aendernAngebotTemplate.html';
            return ($this->neu_aendernAngebotTemplate = file_get_contents($pathfile));
        }

        public function getAnzeigenAngebotsBenutzerTemplate() {
            $pathfile = '../MVC/Views/Templates/anzeigenAngebotsBenutzerTemplate.html';
            return ($this->anzeigenAngebotsBenutzerTemplate = file_get_contents($pathfile));
        }

        public function getHtmlExportTemplate() {
            $pathfile = '../MVC/Views/Templates/html-Export-Template.html';
            return ($this->htmlExportTemplate = file_get_contents($pathfile));
        }

        /*public function getMenueTemplate() {
            $pathfile = '../MVC/Views/Templates/MenueTemplate.html';
            return ($this->indexTemplate = file_get_contents($pathfile));
        }*/

        public function render($template, $key, $value, $param) {
            return (preg_replace($key, $value, $template, $param));
        }

    }