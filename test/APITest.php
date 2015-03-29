<?php
/**
 * Description of apiTest
 *
 * @author Marco
 */
require_once __DIR__ . '/../api/API.php';

class APITest extends \PHPUnit_Framework_TestCase {    
    public function testWrongLogin() {
        $api = new API();
        $_SERVER['REQUEST_METHOD'] = "POST";
        $_GET['funct'] = "session";
        $_POST = array('email' => 'test@example.com', 'password' => 'password');
        
        $this->assertEquals(400, $api->getResponseCode());
    }
}
