<?php


/**
 * 
 */
class Ingredient implements JsonSerializable {


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

        $mysqli = DB::con();

        $query = 'SELECT id, name FROM ingredient WHERE id = ?';

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($res_id, $res_name);

            while ($stmt->fetch()) {
                $result = new Ingredient($res_id, $res_name);
            }

            $stmt->free_result();
            $stmt->close();
        }
        return $result;
    }

    /**
     * @param string $name 
     * @return \\Ingredient[]
     */
    public static function get_by_name($name) {
        $mysqli = DB::con();

        $query = 'SELECT id, name FROM ingredient WHERE name LIKE ?';

        if ($stmt = $mysqli->prepare($query)) {
            $search_name = '%'.$name.'%';  //bind_param needs reference
            $stmt->bind_param('s', $search_name);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($res_id, $res_name);


            while ($stmt->fetch()) {

                $result[] = new Ingredient($res_id, $res_name);

            }
            $stmt->free_result();
            $stmt->close();
        }
        return $result;
    }

    /**
     * @param string $name 
     * @return \\Ingredient
     */
    public static function create($name) {

        $mysqli = DB::con();

        $query = 'INSERT INTO ingredient (name) VALUES (?)';

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('s', $name);
            $stmt->execute();

            $result = new Ingredient($mysqli->insert_id, $name);

            $stmt->free_result();
            $stmt->close();
        }
        return $result;



    }

    /**
     * @param int $id 
     * @param string $name
     */
    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function get_id() {
        return $this->id;
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

        $query = 'UPDATE ingredient SET name = ? WHERE id = '.$this->id;

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('s', $name);
            $result = $stmt->execute();

            if (!$result) {
                throw new InternalError('Fehler beim Ändern des Namens: '.DB::con()->error);
            }

            $this->name = $name;

            $stmt->free_result();
            $stmt->close();
        }
        return $result;

    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}