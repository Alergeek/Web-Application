<?php


/**
 * 
 */
class Product {


    /**
     * @var int
     */
    private $ean;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \\Ingredient[]
     */
    private $ingredients;



    /**
     * @param int $ean 
     * @param string $name 
     * @return \\Product
     */
    public static function create($ean, $name) {
        // TODO implement here
        return null;
    }

    /**
     * Gibt ein Produkt anhand seiner ID aus der Datenbank zurück.
     * @param int $id 
     * @return \\Product
     */
    public static function get_by_id($id) {
        // TODO implement here
        return null;
    }

    /**
     * @return \\Product[]
     */
    public static function get_by_name() {
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

    /**
     * @return \\Ingredient[]
     */
    public function get_ingredients() {
        // TODO implement here
        return null;
    }

    /**
     * @param \\Ingredient $ingredient 
     * @return bool
     */
    public function add_ingredient($ingredient) {
        // TODO implement here
        return null;
    }

    /**
     * @param \\Ingredient $ingredient 
     * @return bool
     */
    public function rm_ingredient($ingredient) {
        // TODO implement here
        return null;
    }

}