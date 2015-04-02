<?php
require_once './prepare.php';

API::init();
API::define('AUTH', '[0-9a-f]{20}');
API::define('ID', '\d+');
API::post('session/', function($a_Data) {
    if (!isset( $a_Data['email'],  $a_Data['password'])) {
        return API::make_error(400, 'Missing POST parameters.');
    }
    $email = $a_Data['email'];
    $password = $a_Data['password'];
    // check for isertions...
    // quick and dirty implementation
    try {
        $session = new Session($email, $password);
    } catch(UserError $u) {
        API::make_error($u->getCode(), $u->getMessage());
    }

    echo '{"authToken": "'.$session->get_token().'"}';
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
API::put('session/', function($a_Data) {

});
API::delete('session/{AUTH}/', function($a_Data) {
    $session = $a_Data['session'];

    $session->destroy();
});
API::post('user/{AUTH}/', function($a_Data) {
    if (!isset($a_Data['password'])) {
        return API::make_error(400, 'Missing POST parameters.');
    }
    //Session is set anyway so we dont need to check it
    $session = $a_Data['session'];
    if(!$session->get_user()->check_password($a_Data['password'])){
        echo false;
        return;
    }
    if (isset($a_Data['email'])) {
        if($session->get_user()->set_email($a_Data['email'])){
            echo true;
            return;
        }
    }
    elseif(isset($a_Data['newPassword'])){
        if($session->get_user()->set_password($a_Data['password'])){
            echo true;
            return;
        }
    }
    else{
        return API::make_error(400, 'Missing POST parameters.');
    }
    //you never should end up here, if you are here then "$session->get_user()->set_email()/setpassword()" has failed
    echo false;
    return;
});
API::put('user/', function($a_Data) {

});
API::finalize();
?>