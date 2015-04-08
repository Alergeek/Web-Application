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
        throw new UserError('Missing POST parameters.', 400);
    }

    if(!isset($a_Data['email']) && !isset($a_Data['newPassword'])){
        throw new UserError('Missing POST parameters.', 400);
    }

    //Session is set anyway so we dont need to check it
    $session = $a_Data['session'];
    if(!$session->get_user()->check_password($a_Data['password'])){
        echo 'false';
        return;
    }
    if (isset($a_Data['email'])) {
        if($session->get_user()->set_email($a_Data['email'])){
            echo 'true';
        }
    }
    if(isset($a_Data['newPassword'])){
        if($session->get_user()->set_password($a_Data['newPassword'])){
            echo 'true';
        }
    }
    return;
});
API::put('user/', function($a_Data) {
    if (!isset( $a_Data['email'],  $a_Data['password'])) {        
        throw new UserError('Missing POST parameters.', 400);
    }
    
    $email = $a_Data['email'];
    $password = $a_Data['password'];

    User::create($email, $password);
    $session = Session::get_by_login($email, $password);
    
    echo $session->to_json();
});
API::get('blacklist/{AUTH}/', function($a_Data) {
    $user = $a_Data['session']->get_user();
    $ingredients = $user->get_blacklist();

    echo json_encode($ingredients); //"[ ".join(', ', $jsons)." ]";
});
API::put('blacklist/{ID}/{AUTH}/', function($a_Data) {
    $user = $a_Data['session']->get_user();
    Ingredient::get_by_id($a_Data['id']);
    $result = $user->add_to_blacklist($a_Data['id']);

    echo $result ? 'true' : 'false';
});
API::delete('blacklist/{ID}/{AUTH}/', function($a_Data){
    $user = $a_Data['session']->get_user();
    Ingredient::get_by_id($a_Data['id']);
    $result = $user->rm_from_blacklist($a_Data['id']);

    echo $result ? 'true' : 'false';
});
//Product API
API::get('product/{ID}/{AUTH}/', function($a_Data) {
    $product_ean = $a_Data['id'];

    $product = Product::get_by_ean($product_ean);

    $result['ean'] = $product->get_ean();
    $result['name'] = $product->get_name();
    $result['ingredients'] = $product->get_ingredients();
    $result['edible'] = $product->is_edible($a_Data['session']->get_user()->get_id());

    echo json_encode($result);

});
API::get('ingredient/', function($a_Data) {

    $ingredients = Ingredient::get_all();

    echo json_encode($ingredients);

});


API::finalize();
?>