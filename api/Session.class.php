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
     * @var Date
     */
    private $valid;


    /**
     * @return int
     */
    public static function generate_barcode() {
        // Start new Session
        return 0;
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
            $this->user = $user;

            // TODO: insert into token table
        }
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