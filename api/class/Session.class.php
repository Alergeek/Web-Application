<?php
/**
 * Class for session management
 */
class Session {
    const WRONG_LOGIN = 'Die Logindaten sind nicht korrekt.';
    const WRONG_TOKEN = 'Das angegebene Token ist nicht gültig.';
    const WRONG_CODE  = 'Der angegebene Barcode ist nicht gültig.';

    const TK_ADMN_LGT = 86400; // 24 Stunden
    const TK_WEAR_LGT = 31536000; // 1 Jahr

    private $token; // string, authToken
    private $user; // object of User
    private $admin; // boolean
    private $valid; // integer, timestamp
    private $name; // string, user agent

    /**
     * restores session via auth token
     * @param string $token authToken
     * @return object Session
     * @throws UserError on invalid token
     * @throws InternalError on SQL and database errors
     */
    public static function get_by_token($token) {
        if (!self::is_token($token)) {
            throw new UserError(self::WRONG_TOKEN, 401);
        }
        $sql = 'SELECT
                    user_id,
                    name,
                    valid,
                    admin_right,
                    password,
                    email
                FROM
                    device
                JOIN
                    user
                ON
                    user_id = id
                WHERE
                    token = ? AND
                    valid > NOW()';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('s', $token);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->bind_result($user_id, $name, $valid, $admin, $password, $email);
        if (!$stmt->fetch()) {
            throw new UserError(self::WRONG_TOKEN, 401);
        }
        $stmt->close();
        $user = new User($user_id, $email, $password);
        if($admin) {
            $admin = true;
        } else {
            $admin = false;
        }
        return new Session($token, $user, $admin, strtotime($valid), $name);
    }

    /**
     * creates new session via barcode
     * @param integer $code barcode previously created
     * @return object Session
     * @throws UserError on wrong barcode
     */
    public static function get_by_barcode($code) {
        if (!is_numeric($code)) {
            throw new UserError(self::WRONG_CODE, 401);
        }
        session_id($code);
        session_start();
        if(!isset($_SESSION['userid'])) {
            throw new UserError(self::WRONG_CODE, 401);
        }
        $name = 'Wearable Device Vuzix M100';
        $user = User::get_by_id($_SESSION['userid']);
        $token = self::make_token($name, false, $user, self::TK_WEAR_LGT);
        return new Session($token, $user, false, time() + self::TK_WEAR_LGT, $name);
    }

    /**
     * creates new session via login data
     * @param string $email
     * @param string $password
     * @return object Session
     * @throws UserError on wrong barcode
     */
    public static function get_by_login($email, $password) {
        try {
            $user = User::get_by_email($email);
        } catch(UserError $u) {
            // make error anonymous
            throw new UserError(self::WRONG_LOGIN, 401);
        }
        if (!$user->check_password($password)) {
            throw new UserError(self::WRONG_LOGIN, 401);
        }

        // TODO: Android login different length/name
        $name = 'Browser: '.filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
        $valid = time() + self::TK_ADMN_LGT;
        $token = self::make_token($name, $admin, $user->get_id(), self::TK_ADMN_LGT );
        return new Session($token, $user, true, $valid, $name);
    }

    /**
     * checks if a given string matches the pattern of an auth token
     * @param string $token authToken
     * @return boolean
     */
    public static function is_token($token) {
        return preg_match('~^[0-9a-f]{20}$~', $token);
    }

    /**
     * @param string $token authToken
     * @param object $user user object of logged user
     * @param boolean $admin session with admin rights?
     * @param integer $valid timestamp till valid date
     * @param string $name name of the session
     */
    public function __construct($token, $user, $admin, $valid, $name) {
        $this->token = $token;
        $this->user = $user;
        $this->admin = $admin;
        $this->valid = $valid;
        $this->name = $name;
    }

    /**
     * @param string $device name of the device visible for the user
     * @param boolean $admin flag whether token grants admin right
     * @param integer $user id of the user in database
     * @param integer $length length of tokens valid time in seconds
     * @return string generated token
     */
    private static function make_token($device, $admin, $user, $length) {
        $chars = '1234567890abcdef'; // characters used for token
        $token = '';

        // generate random token with 20 characters
        for ($i = 0; $i < 20; ++$i) {
            $token .= substr(str_shuffle($chars), 0, 1);
        }

        // check if token is already known for some other user
        $sql = 'SELECT token FROM device WHERE token = \'' . $token . '\'';
        $result = DB::con()->query($sql);
        if (!$result) {
            throw new InternalError('Konnte Query nicht bearbeiten: <br/>'.DB::con()->error);
        }
        if($result->num_rows) {
            return make_token($device, $admin, $user, $length);
        }

        // convert admin to int for database
        if ($admin) {
            $admin = 1;
        } else {
            $admin = 0;
        }
        // make string with date in the future
        $date = date("Y-m-d H:i:s", time() + $length);

        // insert token into database
        $sql = 'INSERT INTO device (token, name, valid, admin_right, user_id)
                VALUES(?, ?, ?, ?, ?)';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('sssii', $token, $device, $date, $admin, $user);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        return $token;
    }

    /**
     * logout with this session
     * after that the session cannot be restored by self::get_by_token()
     * @return boolean
     * @throws InternalError on SQL or database error
     */
    public function destroy() {
        $sql = 'UPDATE device
                SET valid=NOW()
                WHERE token = "' . $this->token . '"';
        if(!DB::con()->query($sql)) {
            throw new InternalError("Konnte Session nicht beenden:\n".DB::con()->error);
        }
        $this->valid = time();
        return true;
    }
    /**
     * generates barcode for connecting wearable device
     * @return integer
     */
    public function generate_barcode() {
        $code = rand(0, 9999999999999);
        session_id($code);
        session_start();
        $_SESSION['userid'] = $this->user->get_id();
        return $code;
    }

    /**
     * @return object User
     */
    public function get_user() {
        return $this->user;
    }

    /**
     * @return string
     */
    public function get_token() {
        return $this->token;
    }

    /**
     * @return boolean
     */
    public function is_admin() {
        return $this->admin;
    }

    /**
     * generates a json from this session
     * @return string JSON
     */
    public function to_json() {
        return '{"authToken": "'.$this->token.'",'.
               ' "name": "'.$this->name.'",'.
               ' "email": "'.$this->user->get_email().'",'.
               ' "until": '.$this->valid.'}';
    }

    /**
     * gets all sessions by the user logged in this session
     * @return array of object of Session
     * @throws InternalError on SQL or database error
     */
    public function get_all_sessions() {
        $sessions = array();
        $sql = 'SELECT
                    token,
                    name,
                    valid,
                    admin_right
                FROM
                    device
                WHERE
                    user_id = '.$this->user->get_id().' AND
                    valid > NOW()';
        $result = DB::con()->query($sql);
        if (!$result) {
            throw new InternalError('Konnte Query nicht ausführen: '.DB::con()->error);
        }
        while ($row = $result->fetch_assoc()) {
            $admin = $row['admin_right'] == 1 ? true : false;
            $sessions[] = new Session($row['token'], $this->user, $admin,
                                strtotime($row['valid']), $row['name']);
        }
        return $sessions;
    }
}