<?php


/**
 * 
 */
class Session {
    /**
     * @var string
     */
    private static $wrong_login = 'Die Logindaten sind nicht korrekt.';

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
     * @var integer
     */
    private $valid;

    /**
     * @var string
     */
    private $name;


    /**
     * @return int
     */
    public static function generate_barcode() {
        $code = '';
        for ($i = 0; $i < 13; $i++) {
            $code .= rand(0, 9);
        }
        session_id($code);
        session_start();
        var_dump($_SESSION);
        return $code;
    }

    /**
     * checks if a given string matches the pattern of an auth token
     * @return boolean
     */
    public static function is_token($token) {
        return preg_match('~^[0-9a-f]{20}$~', $token);
    }

    /**
     * generate new session via  login credentials
     * @param string $login ean, auth token or email in ombination with password
     * @param string $password
     */
    public function __construct($login, $password = null) {
        if (is_null($password)) {
            if (is_numeric($login) AND strlen($login) == 13) {
                // Login via barcode
            } else {
                // Login via auth token
            }
        } else {
            // Login via email and password
            try {
                $user = User::get_by_email($login);
            } catch(UserError $u) {
                // make error anonymous
                throw new UserError(self::$wrong_login, 403);
            }
            if (!$user->check_password($password)) {
                throw new UserError(self::$wrong_login, 403);
            }

            // TODO: Android login different length/name
            $this->name = 'Browser: '.filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
            $this->user = $user;
            $this->admin = true;
            $this->valid = time() + 60*60*24;
        }
        $this->token = make_token($this->name, $this->admin,
            $this->user->get_id(), $this->valid );
    }

    /**
     * @param string $device name of the device visible for the user
     * @param boolean $admin flag whether token grants admin right
     * @param integer $user id of the user in database
     * @param integer $length length of tokens valid time in seconds
     * @return string generated token
     */
    private function make_token($device, $admin, $user, $length) {
        $chars = '1234567890abcdef'; // characters used for token
        $token = '';

        // generate random token with 20 characters
        for ($i = 0; $i < 20; ++$i) {
            $token .= substr(str_shuffle($chars), 0, 1);
        }

        // check if token is already known for some other user
        $sql = 'SELECT token FROM devices WHERE token = "' + $token + '"';
        $result = DB::con()->query($sql);
        if (!$result) {
            throw new InternalError('Konnte Query nicht bearbeiten: '.DB::con()->error);
        }
        if($result->num_rows()) {
            return make_token($device, $admin, $user, $length);
        }

        // convert admin to int for database
        if ($admin) {
            $admin = 1;
        } else {
            $admin = 0;
        }
        // make string with date in the future
        $date = date('c', time() + $length);

        // insert token into database
        $sql = 'INSERT INTO devices (Token, Name, valid, admin_right, User)
                VALUES(?, ?, ?, ?, ?)';
        $stmt = DB::con()->prepare($sql);
        if (!$stmt) {
            throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error);
        }
        $stmt->bind_param('sssii', $token, $device, $date, $admin, $user);

        if (!$stmt->execute()) {
            throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error);
        }
        $stmt->bind_result($email, $hash);
        if (!$stmt->fetch()) {
            throw new UserError('Konnte keinen User mit dieser Email finden.');
        }
        return $token;
    }

    /**
     * @return bool
     */
    public function destroy() {
        $sql = 'UPDATE devices
                SET valid=NOW()
                WHERE token = "' + $this->token() + '"';
        if(!DB::con()->query($sql)) {
            throw new InternalError('Konnte Session nicht beenden.');
        }
        $this->valid = time();
        return true;
    }

    /**
     * @return \\User
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
     * @return bool
     */
    public function is_admin() {
        return $this->admin;
    }
}