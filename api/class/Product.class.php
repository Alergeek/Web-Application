<?php


/**
 * 
 */
class Product  implements JsonSerializable {


    /**
     * @var long
     */
    private $ean;

    /**
     * @var string
     */
    private $name;


    /**
     * @param int $ean 
     * @param string $name 
     * @return \\Product
     */
    public static function create($ean, $name) {

        $mysqli = DB::con();

        $query = 'INSERT INTO product (ean, name) VALUES (?, ?)';

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('ss', $ean, $name);
            $stmt->execute();

            $result = new Product($ean, $name);

            //$stmt->free_result();
            $stmt->close();
        }
        return $result;

    }

    /**
     * Gibt ein Produkt anhand seiner ID aus der Datenbank zurück.
     * @param int $ean
     * @return \\Product
     */
    public static function get_by_ean($ean) {
        $mysqli = DB::con();

        $query = 'SELECT ean, name FROM product WHERE ean = ?';

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('s', $ean);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($res_ean, $res_name);

            while($stmt->fetch()) {
                $result = new Product($res_ean, $res_name);
            }

            $stmt->free_result();
            $stmt->close();
        }
        
        return $result;
    }

    /**
     * @return \\Product[]
     */
    public static function get_by_name() {
        $mysqli = DB::con();

        $query = 'SELECT ean, name FROM product WHERE name LIKE ?';

        if ($stmt = $mysqli->prepare($query)) {
            $search_name = '%'.$name.'%';  //bind_param needs reference
            $stmt->bind_param('s', $search_name);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($res_ean, $res_name);


            while ($stmt->fetch()) {
                $result = new Product($res_ean, $res_name);
            }
            $stmt->free_result();
            $stmt->close();
        }
        
        return $result;
    }

    /**
     * @param int $ean
     * @param string $name
     */
    public function __construct($ean, $name) {
        $this->ean = $ean;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function get_ean() {
        return $this->ean;
    }

    /**
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * @param string $name 
     * @return bool
     * @throws InternalError
     */
    public function set_name($name) {
        $mysqli = DB::con();

        $query = 'UPDATE product SET name = ? WHERE ean = '.$this->ean;

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('s', $name);
            $result = $stmt->execute();

            if (!$result) {
                throw new InternalError('Fehler beim Ändern des Namens: '.DB::con()->error);
            }

            $this->name = $name;

            //$stmt->free_result();
            $stmt->close();
        }
        
        return $result;
    }

    /**
     * @return \\Ingredient[]
     */
    public function get_ingredients() {
        $mysqli = DB::con();

        $query = 'SELECT id, name
                  FROM ingredient i
                  JOIN product_has_ingredient phi
                  ON i.id = phi.ingredient_id
                  WHERE phi.product_ean = ?';

        $result = [];

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('s', $this->ean);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($res_id, $res_name);


            while ($stmt->fetch()) {
                array_push($result, new Ingredient($res_id, $res_name));
            }
            $stmt->free_result();
            $stmt->close();
        }
        return $result;
    }

    /**
     * @param \\Ingredient $ingredient 
     * @return bool
     * @throws InternalError
     */
    public function add_ingredient($ingredient) {
        $mysqli = DB::con();

        $query = 'INSERT INTO product_has_ingredient (product_ean, ingredient_id) VALUES (?, ?)';

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('si', $this->ean, $ingredient->get_id());
            $result = $stmt->execute();

            if (!$result) {
                throw new InternalError('Fehler: '.DB::con()->error);
            }

            //$stmt->free_result();
            $stmt->close();
        }
        
        return $result;
    }

    /**
     * @param \\Ingredient $ingredient 
     * @return bool
     * @throws InternalError
     */
    public function rm_ingredient($ingredient) {
        $mysqli = DB::con();

        $query = 'DELETE FROM product_has_ingredient WHERE product_ean = ? AND ingredient_id = ?';

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('si', $this->ean, $ingredient->get_id());
            $result = $stmt->execute();

            if (!$result) {
                throw new InternalError('Fehler: '.DB::con()->error);
            }

            //$stmt->free_result();
            $stmt->close();
        }
        
        return $result;
    }

    public function is_edible($user_id)
    {
        $mysqli = DB::con();

        $query = "SELECT CASE count(*) WHEN 0 THEN 'true' ELSE 'false' END AS edible
                  FROM product p JOIN product_has_ingredient phi ON p.ean = phi.product_ean
                  JOIN blacklist b ON phi.ingredient_id = b.ingredient_id
                  WHERE b.user_id = ?";

        $edible = "";

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($edible);

            $stmt->fetch();

            $stmt->free_result();
            $stmt->close();
        }
        return $edible;
    }

    public function jsonSerialize() {
        return [
            'ean' => $this->ean,
            'name' => $this->name,
            'ingredients' => $this->get_ingredients()
        ];
    }

}