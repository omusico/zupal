<?php
// set some shortcuts
if(!defined('PS')) {
	define('PS', PATH_SEPARATOR);
}

if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

defined('ROOT_DIR') 
	|| define('ROOT_DIR', dirname(dirname(__FILE__)));
	

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_DIR . DS . 'application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


if(!defined('APP_DIR')) {
	define('APP_DIR', APPLICATION_PATH);
}

if(!defined('LIB_DIR')) {
	define('LIB_DIR', ROOT_DIR . DS . 'library');
}

/** setup include path **/
set_include_path(
      LIB_DIR . PS
    . APP_DIR . PS
    . APP_DIR . DS . "models" . PS
    . APP_DIR . DS . "forms" . PS
    . get_include_path()
);


/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$application->run();