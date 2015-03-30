<?php


/**
 * 
 */
class Session {


    /**
     * @var string
     */
    private $token;

    /**
     * @var \\User
     */
    private $user;

    /**
     * @var bool
     */
    private $admin;


    /**
     * @return int
     */
    public static function generate_barcode() {
        // TODO implement here
        return 0;
    }

    /**
     * revoke existing session via auth token
     * @param string $token
     */
    public function __construct($token) {
        // TODO implement here
    }

    /**
     * generate new session via  login credentials
     * @param string $email 
     * @param string $password
     */
    public function __construct($email, $password) {
        // TODO implement here
    }

    /**
     * create new session via generated barcode
     * @param int $barcode
     */
    public function __construct($barcode) {
        // TODO implement here
    }

    /**
     * @return bool
     */
    public function destroy() {
        // TODO implement here
        return null;
    }

    /**
     * @return \\User
     */
    public function get_user() {
        // TODO implement here
        return null;
    }

    /**
     * @return string
     */
    public function get_token() {
        // TODO implement here
        return "";
    }

    /**
     * @return bool
     */
    public function is_admin() {
        // TODO implement here
        return null;
    }

}