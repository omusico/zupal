#!/usr/bin/env php
<?php

if(!defined('PS')) {
    define('PS', PATH_SEPARATOR);
}

if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if(!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(dirname(__FILE__)));
}

if(!defined('APP_DIR')) {
    define('APP_DIR', ROOT_DIR . DS . 'application');
}

if(!defined('LIB_DIR')) {
    define('LIB_DIR', ROOT_DIR . DS . 'library');
}
        
set_include_path(
      LIB_DIR . PS
    . LIB_DIR . DS . 'doctrine' . PS
    . APP_DIR . PS
    . APP_DIR . DS . "models" . PS
    . APP_DIR . DS . "models" . DS . "generated" . PS
    . APP_DIR . DS . "forms" . PS
    . get_include_path()
);

require_once("ZPress/Bootstrap.php");
try {
   Bootstrap::prepareConsole();
} catch(Exception $ex) {
   print_r($ex);
   exit();
}

require_once('PHPUnit/Util/Filter.php');
PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
require('PHPUnit/TextUI/Command.php');
?>