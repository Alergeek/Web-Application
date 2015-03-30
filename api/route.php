<?php
require_once './prepare.php';

API::init();
API::define('AUTH', '[0-9a-zA-Z]{20}');
API::define('NUMBER', '\d+');
API::define('ID', '\d+');
API::post('session/', function($a_Data) {
    echo $a_Data['test'];
});
API::get('session/', function($a_Data) {
    // check whether input is token
    $token = $a_Data['authToken'];
    if (!Session::is_token($token)) {
        echo '{"valid": false}';
        return;
    }
    try {
        new Session($token);
        echo '{"valid": true}'; // Hier könnten wir noch mehr zurückgeben
    } catch(UserError $u) {
        echo '{"valid": false}';
    }
});
API::finalize();

function startSession() {
    $session = null;
    if(!filter_input(INPUT_REQUEST, 'password')) {
        $login = filter_input(INPUT_REQUEST, 'email');
        $password = filter_input(INPUT_REQUEST, 'password');
        
        $session = new Session($login, $password);
    } elseif (!filter_input(INPUT_REQUEST, 'authToken')) {
        $authToken = filter_input(INPUT_REQUEST, 'authToken');
        
        $session = new Session($authToken);
    }
}
?>