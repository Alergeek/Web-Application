<?php


/**
 * 
 */
class Session {


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
     * @return int
     */
    public static function generate_barcode() {
        // Start new Session
        return 0;
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
            // Login via email and Password
            $sql = 'SELECT id, password
                    FROM users
                    WHERE eMail = ?';
            $stmt = DB::con()->prepare($sql);
            if (!$stmt) {
                throw new InternalError('Konnte Query nicht vorbereiten: '.DB::con()->error());
            }
            $stmt->bind_param('s', $login);

            if (!$stmt->execute()) {
                throw new InternalError('Konnte Query nicht ausführen: '.$stmt->error());
            }
            $stmt->bind_result($id, $hash);
            if (!$stmt->fetch() OR $password !== $hash) {
                throw new UserError('Die Logindaten sind nicht korrekt', 403);
            }
            $stmt->close();
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