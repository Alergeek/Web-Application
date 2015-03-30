<?php


/**
 * 
 */
class User {

    /**
     * 
     */
    public function __construct() {
    }

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \\Ingredient[]
     */
    private $blacklist;


    /**
     * @param string $email 
     * @param string $password 
     * @return \\User
     */
    public static function create($email, $password) {
        // TODO implement here
        return null;
    }

    /**
     * @param int $id 
     * @return \\User
     */
    public static function get_by_id($id) {
        // TODO implement here
        return null;
    }

    /**
     * @param string $email 
     * @return \\User
     */
    public static function get_by_email($email) {
        // TODO implement here
        return null;
    }

    /**
     * @return int
     */
    public function get_id() {
        // TODO implement here
        return 0;
    }

    /**
     * @return string
     */
    public function get_email() {
        // TODO implement here
        return "";
    }

    /**
     * @param string $password 
     * @return bool
     */
    public function check_password($password) {
        // TODO implement here
        return null;
    }

    /**
     * @param string $email 
     * @return bool
     */
    public function set_email($email) {
        // TODO implement here
        return null;
    }

    /**
     * @param string $password 
     * @return bool
     */
    public function set_password($password) {
        // TODO implement here
        return null;
    }

    /**
     * @return \\Ingredient
     */
    public function get_blacklist() {
        // TODO implement here
        return null;
    }

    /**
     * @param int $item_id 
     * @return bool
     */
    public function add_to_blacklist($item_id) {
        // TODO implement here
        return null;
    }

    /**
     * @param int $item_id 
     * @return bool
     */
    public function rm_from_blacklist($item_id) {
        // TODO implement here
        return null;
    }

}