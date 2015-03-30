<?php
require_once './config.inc';

spl_autoload_extensions('.class.php');
spl_autoload_register();

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DATABASE);
if (mysqli_connect_errno()) {
    printf(
        "Can't connect to MySQL Server. Errorcode: %s\n",
        mysqli_connect_error()
    );
    exit;
}