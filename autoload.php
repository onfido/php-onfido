<?php

function onfido_autoloader($class_name) {
    if($class_name === 'Onfido\Config')
        require_once 'onfido.php';
    else if(strpos(strtolower($class_name), 'phpunit_') === false) {    //Exclue phpunit classes from this autoloader
        $class_name = str_replace("onfido\\", '', strtolower($class_name));
        include 'endpoints/' . $class_name . '.php';
    }
}

spl_autoload_register('onfido_autoloader');

?>
