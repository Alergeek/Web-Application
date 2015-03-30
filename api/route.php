<?php
require_once './prepare.php';

API::init();
API::define('AUTH', '[0-9a-zA-Z]{20}');
API::define('NUMBER', '\d+');
API::define('ID', '\d+');
API::put('blog/like/{ID}/', function($a_Data) {
    echo $a_Data['id'].' '.$a_Data['auth'];
});
API::post('session', function() {
    startSession();
});
//API::get('session', getToken($a_Data));
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

function getToken($authToken) {    
    $session = new Session($authToken);
}