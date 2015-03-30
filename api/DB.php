<?php
require_once './config.inc';

class DB {
    private static $_instance; //The single instance

    /*
      Get an instance of the Database
      @return Instance
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);
            
            if (mysqli_connect_error()) {
                trigger_error("Failed to connect to MySQL: " . mysql_connect_error(), E_USER_ERROR);
            }
        }
        return self::$_instance;
    }
}
