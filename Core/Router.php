<?php
//echo "<br>Router.php geladen";
/**
 * Router
 **/

 class Router  {

    /** 
     * Assoziatives Array für die Routen (Routing-Tabelle)
    **/
    private $routes = array();

    /** 
     * Get all the routes from the routing table
     * 
     * @Parameter $dbh (DB-Connect)
     * @return array
    */

    public function __construct($dbh) {
        $sql = "SELECT Controler, Action FROM router";
        if(is_object(($result = $dbh->query($sql)))) {
//            print "<br>Ergebnisse vorhanden";
        } else {
            print "KEINE Ergebnisse vorhanden";
        }
            $i = 0;
            while ($obj = $result->fetch_object()) {
                $this->routes[$i]['Controler'] = preg_replace('/\s+/', "", $obj->Controler);
                $this->routes[$i]['Action'] = preg_replace('/\s+/', "", $obj->Action);
                $i++;
            }
#echo "<br>"; var_dump($this->routes);
    }
    
    public function selectControler() {
        foreach($this->routes as $i => $route) {
            $key = array_key_first($_GET);
#echo "<br> i - key  : "; print_r($i); echo " - "; print_r($key); echo " - _GET[key] "; print_r($_GET[array_key_first($_GET)]);
#echo "<br> Controler = "; print_r($route['Controler']); echo " - Action = "; print_r($route['Action']);
#echo "<br> GET[array_key_first] "; print_r($_GET[array_key_first($_GET)]);
#echo "<br>route[Controler] = "; print_r($route['Controler']); echo " : key = "; echo $key; echo " : route[Action] = "; print_r($route['Action']); echo " : _GET[array_key_first] = "; print_r($_GET[array_key_first($_GET)]);
            if($route['Controler'] == $key && $route['Action'] == $_GET[array_key_first($_GET)]) {
#echo "<br>Route gefunden!";
                return $route;
            }
        }
        return NULL;
    }
    
    public function getRoutes() {
        return $this->routes;
    }

    public function countRoutes() {
        return count($this->routes);
    }

    function __destruct() {
        #print "<br>Zerstoere Object Router" . __CLASS__ . "\n";
    }

 }