<?php


/**
 * 
 */
class User {

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
    private $hash;

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
        $hash = sha1($password);
        $lower_email = strtolower($email);
        $sql = 'INSERT INTO user (email, password)
                VALUE(?, ?)';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        
        $stmt->bind_param('ss', $lower_email, $hash);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $id = $stmt->insert_id;
        $stmt->close();
        return new self($id, $lower_email, $hash);
    }

    /**
     * @param int $id 
     * @return \\User
     */
    public static function get_by_id($id) {
        $sql = 'SELECT eMail, password
                FROM user
                WHERE id = ?';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $lower_id = strtolower($id);
        $stmt->bind_param('i', $lower_id);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->bind_result($email, $hash);
        if (!$stmt->fetch()) {
            throw new UserError('Konnte keinen User mit dieser ID finden.');
        }
        $stmt->close();
        return new self($lower_id, $email, $hash);
    }

    /**
     * @param string $email 
     * @return \\User
     */
    public static function get_by_email($email) {
        $lower_email = strtolower($email);
        $sql = 'SELECT id, password
                FROM user
                WHERE email = ?';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('s', $lower_email);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->bind_result($id, $hash);
        if (!$stmt->fetch()) {
            throw new UserError('Konnte keinen User mit dieser Email finden.');
        }
        $stmt->close();
        return new self($id, $lower_email, $hash);
    }

    public function __construct($id, $email, $hash) {
        $this->id = $id;
        $this->email = $email;
        $this->hash = $hash;
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
    public function get_email() {
        return $this->email;
    }

    /**
     * @param string $password 
     * @return boolean
     */
    public function check_password($password) {
        return sha1($password) === $this->hash;
    }

    /**
     * @param string $email 
     * @return boolean
     */
    public function set_email($email) {
        $sql = 'UPDATE user 
                SET email = ? 
                WHERE id = ?';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $paramemail = strtolower($email);
        $stmt->bind_param('si', $paramemail, $this->id);
        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->close();
        $this->email = $email;
        return true;
    }

    /**
     * @param string $password 
     * @return bool
     */
    public function set_password($password) {
        $sql = 'UPDATE user
                SET password = ? 
                WHERE id = ?';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $hash = sha1($password);
        $stmt->bind_param('si', $hash, $this->id);
        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->close();
        $this->hash = $hash;
        return true;
    }

    /**
     * @return \\Ingredient
     */
    public function get_blacklist() {
        $mysqli = DB::con();
        $result = array();
        $query = 'SELECT b.ingredient_id,i.name
                  FROM blacklist b
                  JOIN ingredient i
                  ON b.ingredient_id=i.id
                  WHERE b.user_id=?';

        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('i', $this->id);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        } 

        $stmt->bind_result($res_ingredient_id,$res_name);
        while ($stmt->fetch()) {
            $result[] = new Ingredient($res_ingredient_id,$res_name);
        }
        $stmt->close();

        return $result;
    }

    /**
     * @param int $item_id 
     * @return bool
     */
    public function add_to_blacklist($item_id) {
        $mysqli = DB::con();

        $query = 'INSERT INTO blacklist (user_id,ingredient_id) VALUES ( ?, ?)';

        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('ii', $this->id, $item_id);
        $result = $stmt->execute();
        if (!$result) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        } 

        $stmt->close();

        return $result;
    }

    /**
     * @param int $item_id 
     * @return bool
     */
    public function rm_from_blacklist($item_id) {
        $mysqli = DB::con();

        $query = 'DELETE FROM blacklist WHERE user_id = ? AND ingredient_id = ?';

        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('ii', $this->id, $item_id);

        $result = $stmt->execute();
        if (!$result) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        } 

        $stmt->close();

        return $result;
    }

}