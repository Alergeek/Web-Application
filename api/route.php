<?php
error_reporting(E_ALL);
require_once __DIR__ . '/prepare.php';

API::init();
API::define('AUTH', '[0-9a-f]{20}');
API::define('ID', '[1-9]\d*');
API::post('session/', function($a_Data) {
    if (isset( $a_Data['barcode'] )) {
        $session = Session::get_by_barcode($a_Data['barcode']);
        echo $session->to_json();
    } elseif (isset( $a_Data['email'],  $a_Data['password'])) {
        $session = Session::get_by_login($a_Data['email'],$a_Data['password']);
        echo $session->to_json();
    } else {
        throw new UserError('Missing POST parameters.', 400);
    }
});
API::get('session/{AUTH}/', function($a_Data) {
    $sessions = $a_Data['session']->get_all_sessions();;
    $jsons = array();
    foreach($sessions AS $session) {
        $jsons[] = $session->to_json();
    }
    echo "[ ".join(', ', $jsons)." ]";
});
API::put('session/{AUTH}/', function($a_Data) {
    $session = $a_Data['session'];
    echo '{"barcode": '.$session->generate_barcode().'}';
});
API::delete('session/{AUTH}/', function($a_Data) {
    $session = $a_Data['session'];
    echo $session->destroy() ? 'true' : 'false';
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
    if (!isset( $a_Data['email'],  $a_Data['password'])) {
        return API::make_error(400, 'Missing POST parameters.');
    }
    
    $email = $a_Data['email'];
    $password = $a_Data['password'];

    User::create($email, $password);
    $session = Session::get_by_login($email, $password);
    
    echo $session->to_json();
});
API::finalize();
?>