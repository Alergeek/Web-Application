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
        $mysqli = DB::con();
        $sql = 'SELECT id, name
                FROM ingredient
                WHERE id = ?';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('i', strtolower($id));

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->bind_result($res_id, $res_name);
        if (!$stmt->fetch()) {
            throw new UserError('Konnte keinen Inhaltsstoff mit dieser ID finden.');
        }

        $result = new Ingredient($res_ingredient_id,$res_name);

        $stmt->close();
        return $result;
    }

    /**
     * @param string $name 
     * @return \\Ingredient[]
     */
    public static function get_by_name($name) {
        $mysqli = DB::con();
        $sql = 'SELECT id, name
                FROM ingredient
                WHERE name = ?';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('s', $name);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->bind_result($res_id, $res_name);

        while($stmt->fetch){
            $result[] = new Ingredient($res_ingredient_id,$res_name);           
        }

        $stmt->close();
        return $result;
    }

    /**
     * @param string $name 
     * @return \\Ingredient
     */
    public static function create($name) {
        // TODO implement here
        $mysqli = DB::con();

        $sql = 'INSERT INTO ingredient (id, name) VALUES (?, ?)';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('s', $name);
        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }

        $result = new Ingredient($mysqli->insert_id, $name);

        $stmt->close();
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
        // TODO implement here
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_name() {
        // TODO implement here
        return $this->name;
    }

    /**
     * @param string $name 
     * @return bool
     */
    public function set_name($name) {
        // TODO implement here
        // Make SQL request to update database
        $this->name = $name;
        return null;
    }

    public function to_json() {
        return '{"id": '.$this->id.', "name": "'.$this->name.'"}';
    }

}