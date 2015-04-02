<?php


/**
 * 
 */
class Ingredient {


    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;



    /**
     * @param int $id 
     * @return \\Ingredient
     */
    public static function get_by_id($id) {
        // TODO implement here
        return null;
    }

    /**
     * @param string $name 
     * @return \\Ingredient[]
     */
    public static function get_by_name($name) {
        // TODO implement here
        return null;
    }

    /**
     * @param string $name 
     * @return \\Ingredient
     */
    public static function create($name) {
        // TODO implement here
        return null;
    }

    /**
     * @param int $id 
     * @param string $name
     */
    public function __construct($id, $name) {
        // TODO implement here
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
    public function get_name() {
        // TODO implement here
        return "";
    }

    /**
     * @param string $name 
     * @return bool
     */
    public function set_name($name) {
        // TODO implement here
        return null;
    }

}