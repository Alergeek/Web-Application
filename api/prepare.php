<?php
set_include_path(__DIR__.'/class');

function my_autoloader($class) {
    include $class . '.class.php';
}

spl_autoload_register('my_autoloader');