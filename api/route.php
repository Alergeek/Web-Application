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



//Product API
API::get('product/{EAN}/', function($a_Data) {
    // check whether input is token
    $token = $a_Data['authToken'];
    $product_ean = $a_Data['EAN'];

    $product = Product::get_by_ean($product_ean);

    if(!is_null($result)) {

        $result['EAN'] = $product->get_ean();
        $result['name'] = $product->get_name();
        $result['ingredients'] = $product->get_ingredients();

        echo json_encode($result);
    }




});


API::finalize();
?>