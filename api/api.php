<?php
class API {
//    public static $s_Path = null;
//    public static $a_Declarations = array();
    public static $vars = array();
    
    /**
     * Definieren eines neuen Komponenten
     * @param $s_Method Server Request Method
     * @param $s_Comp der API Pfad des Componenten
     * @param $f_Func die auszuführende Funktion
     *          function(array of Strings)
     *          Im Array werden Variablen aus dem Pfad zur Verfügung gestellt
     */
//    public static function component($s_Method, $s_Comp, $f_Func) {
//        // Methode checken
//        if ($_SERVER['REQUEST_METHOD'] != $s_Method OR $s_Comp === null) {
//            return false;
//        }
//        $s_RegPath = $s_Comp;
//        $a_Params = array();
//        // Nach Variablen im Pfad checken
//        if (preg_match('~\{([A-Za-z]*)\}~', $s_RegPath, $a_Vars)) {
//            foreach($a_Vars as $i_Key => $s_Var) {
//                $s_Var = substr($s_Var, 1, -1);
//                // Gefundene Variablen auf Deklarationen überprüfen
//                if (!array_key_exists($s_Var, self::$a_Declarations)) {
//                    throw new Exception("Undeclared Variable '" + $s_Var + "'.");
//                }
//                $s_RegPath = preg_replace('~\{'.$s_Var.'\}~',
//                                '('.self::$a_Declarations[$s_Var].')', $s_RegPath);
//                // Variable Position im Pfad zuordnen
//                $a_Params[$s_Var] = $i_Key;
//            }
//        }
//        // Checken ob Pfad mit Url matched
//        if (preg_match('~'.$s_RegPath.'~', self::$s_Path, $a_Matches)) {
//            // Bei Post mit den Variablen füllen 
//            if ($s_Method === 'POST') {
//                $a_Req = $_POST;
//            } else {
//                $a_Req = array();
//            }
//            foreach($a_Params as $s_Var => $i_Position) {
//                
//            }
//            $f_Func($a_Req);
//            die();
//        }
//    }

    /**
     * Definiere eine neuen POST Pfad
     * @param $s_Comp der API Pfad des Componenten
     * @param $f_Func die auszuführende Funktion
     *          function(array of Strings)
     *          Im Array werden Variablen aus dem Pfad zur Verfügung gestellt
     */
    public static function post($s_Comp, $f_Func) {
        self::component('POST', $s_Comp, $f_Func);
    }


    /**
     * Definiere eine neuen GET Pfad
     * @param $s_Comp der API Pfad des Componenten
     * @param $f_Func die auszuführende Funktion
     *          function(array of Strings)
     *          Im Array werden Variablen aus dem Pfad zur Verfügung gestellt
     */
    public static function get($s_Comp, $f_Func) {
        self::component('GET', $s_Comp, $f_Func);
    }

    /**
     * Definiert eine neue Variable mit Hilfe eines regulären Ausdrucks
     * @param $s_Name Name der Variable bitte Großbuchstaben
     * @param $s_RegEx Regulärer Ausdruck, auf den die Variable matchen soll
     */
    public static function define($s_Name, $s_RegEx) {
        API::$a_Declarations[$s_Name] = $s_RegEx;
    }
    
    /**
     * Damit wird die API initialisiert. Muss gleich zu Beginn aufgerufen werden
     */
    public static function init() {
        $input_filter = constant('INPUT_' . filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        self::$vars = filter_input_array($input_filter);
        if(!method_exists('API', filter_input(INPUT_GET, 'funct'))) {
            http_response_code(404);
            die();
        } else{
            call_user_func(array('self', self::$vars['funct']));
        }
    }
    
    private static function session() {
        $user = "test@example.com";
        $pw = 'password';
        switch (filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
            case 'PUT':
                break;
            case 'POST':
                if(self::$vars['email'] == $user && self::$vars['password'] == $pw) {
                    http_response_code(); //200
                } else {
                    http_response_code(400);
                }
                break;
            case 'GET':
                break;
            default :
                http_response_code(400);
                break;
        }
    }

    /**
     * Wird für die besondere Variable {AUTH} verwendet
     * Tritt die Variable auf, wird versucht den User anzumelden
     */
//    private static function auth($sess_id) {
//        SessionLogin::get_logged_user($sess_id);
//    }
}

API::init();
//API::define('ID', '\d+');
//API::get('blog/like/{ID}/', function($a_Data) {
//    $a_Data['ID'];
//});
//API::get('blog/article/{ID}/', function($a_Data) {});