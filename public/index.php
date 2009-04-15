<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");

// set some shortcuts
defined('PS') 
	|| define('PS', PATH_SEPARATOR);

defined('DS')
	|| define('DS', DIRECTORY_SEPARATOR);

defined('ROOT_DIR')  
	|| define('ROOT_DIR', dirname(dirname(__FILE__)));

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_DIR . DS . 'application');

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

defined('LIBRARY_PATH') || 
	define('LIBRARY_PATH', ROOT_DIR . DS . 'library');


/** setup include path **/
set_include_path(
      LIBRARY_PATH . PS
	. LIBRARY_PATH . DS . 'doctrine' . PS
    . APPLICATION_PATH . PS
    . APPLICATION_PATH . DS . 'models' . PS
    . APPLICATION_PATH . DS . 'models' . DS . 'generated' . PS
    . APPLICATION_PATH . DS . 'forms' . PS
    . get_include_path()
);

require_once 'Zupal/Bootstrap.php';
try {
	Zupal_Bootstrap::runMVC();
} catch(Exception $ex) {
	Zend_Debug::dump($ex->getMessage(), "Exception:");
	Zend_Debug::dump($ex->getTrace(), "Stack Trace:");
}