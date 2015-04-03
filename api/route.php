<?php
require_once './prepare.php';

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
API::finalize();
?>