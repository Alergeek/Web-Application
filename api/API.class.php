<?php
class API {
    public static $s_Path = null;
    public static $a_Declarations = array();
    
    /**
     * Definieren eines neuen Komponenten
     * @param $s_Method Server Request Method
     * @param $s_Comp der API Pfad des Componenten
     * @param $f_Func die auszuführende Funktion
     *          function(array of Strings)
     *          Im Array werden Variablen aus dem Pfad zur Verfügung gestellt
     */
    public static function component($s_Method, $s_Comp, $f_Func) {
        // Methode checken
        if ($_SERVER['REQUEST_METHOD'] != $s_Method OR $s_Comp === null) {
            return false;
        }
        $s_RegPath = $s_Comp;
        $a_Params = array();
        // Nach Variablen im Pfad checken
        if (preg_match_all('~\{[A-Z]+\}~', $s_RegPath, $a_Vars)) {
            foreach($a_Vars[0] as $i_Key => $s_Var) {
                $s_Var = substr($s_Var, 1, -1);
                // Gefundene Variablen auf Deklarationen überprüfen
                if (!array_key_exists($s_Var, self::$a_Declarations)) {
                    self::make_error(500, "Undeclared Variable '" + $s_Var + "'.");
                }
                $s_RegPath = preg_replace('~\{'.$s_Var.'\}~',
                                '('.self::$a_Declarations[$s_Var].')', $s_RegPath);
                // Variable Position im Pfad zuordnen
                $a_Params[$s_Var] = $i_Key;
            }
        }
        // Checken ob Pfad mit Url matched
        if (preg_match('~^'.$s_RegPath.'$~', self::$s_Path, $a_Matches)) {
            // Bei Post mit den Variablen füllen 
            if ($s_Method === 'POST') {
                $a_Req = filter_input_array(INPUT_POST, $_POST);
            // Put und Delete Variablen
            } elseif ($s_Method === 'PUT' OR $s_Method === 'DELETE') {
                parse_str(file_get_contents("php://input"), $a_Req);
            } else {
                $a_Req = array();
            }

            // Pfadvariablen sammeln und übergeben
            foreach($a_Params as $s_Var => $i_Position) {
                $a_Req[strtolower($s_Var)] = $a_Matches[$i_Position + 1];
            }
            // User anmelden
            if (isset($s_Var['AUTH'])) {
                $a_Req['session'] = self::auth($a_Req['auth']);
            }
            $f_Func($a_Req);
            die();
        }
    }

    private static function make_error($s_ResponseCode, $s_Message) {
        http_response_code($s_ResponseCode);
        die($s_Message);
    }

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
     * Definiere eine neuen PUT Pfad
     * @param $s_Comp der API Pfad des Componenten
     * @param $f_Func die auszuführende Funktion
     *          function(array of Strings)
     *          Im Array werden Variablen aus dem Pfad zur Verfügung gestellt
     */
    public static function put($s_Comp, $f_Func) {
        self::component('PUT', $s_Comp, $f_Func);
    }

    /**
     * Definiere eine neuen DELETE Pfad
     * @param $s_Comp der API Pfad des Componenten
     * @param $f_Func die auszuführende Funktion
     *          function(array of Strings)
     *          Im Array werden Variablen aus dem Pfad zur Verfügung gestellt
     */
    public static function delete($s_Comp, $f_Func) {
        self::component('DELETE', $s_Comp, $f_Func);
    }

    /**
     * Definiert eine neue Variable mit Hilfe eines regulären Ausdrucks
     * @param $s_Name Name der Variable bitte Großbuchstaben
     * @param $s_RegEx Regulärer Ausdruck, auf den die Variable matchen soll
     */
    public static function define($s_Name, $s_RegEx) {
        self::$a_Declarations[$s_Name] = $s_RegEx;
    }
    
    /**
     * Damit wird die API initialisiert. Muss gleich zu Beginn aufgerufen werden
     * Hier wird die richtige Verwendung überprüft und die Path Variable gesetzt
     */
    public static function init() {
        if(!isset($_GET['p'])) {
            self::make_error(404);
        } else {
            self::$s_Path = $_GET['p'];
        }
    }

    /**
     * Muss ganz zum Schluss aufgerufen werden, wirft 404 Fehler.
     */
    public static function finalize() {
        $s_Message = 'Cannot '.strtolower($_SERVER['REQUEST_METHOD']).' '.self::$s_Path;
        self::make_error(404, $s_Message);
    }

    /**
     * Wird für die besondere Variable {AUTH} verwendet
     * Tritt die Variable auf, wird versucht den User anzumelden
     * @param $s_SessId Auth Token des Benutzers 
     */
    private static function auth($s_SessId) {
        try {
            return new Session($s_SessId);
        } catch (Exception $e) {
            self::make_error(403, 'Das übermittelte Token ist nicht gültig.');
        }
    }
}
?>