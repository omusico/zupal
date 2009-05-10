<?php
error_reporting(E_ALL);

ini_set("display_errors", "On");

// set some shortcuts
defined('PS') 
	|| define('PS', PATH_SEPARATOR);

defined('DS')
	|| define('DS', DIRECTORY_SEPARATOR);

defined('ZUPAL_ROOT_DIR')
	|| define('ZUPAL_ROOT_DIR', dirname(dirname(__FILE__)));

defined('ZUPAL_APPLICATION_PATH')
    || define('ZUPAL_APPLICATION_PATH', ZUPAL_ROOT_DIR . DS . 'application');

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

defined('ZUPAL_LIBRARY_PATH') ||
	define('ZUPAL_LIBRARY_PATH', ZUPAL_ROOT_DIR . DS . 'library');

defined('ZUPAL_LAYOUT_PATH') ||
	define('ZUPAL_LAYOUT_PATH', ZUPAL_APPLICATION_PATH . DS . 'layouts');

defined('ZUPAL_MODULE_PATH') ||
	define('ZUPAL_MODULE_PATH', ZUPAL_APPLICATION_PATH . DS . 'modules');

/** setup include path **/
set_include_path(
      ZUPAL_LIBRARY_PATH . PS
    . ZUPAL_APPLICATION_PATH . PS
    . ZUPAL_APPLICATION_PATH . DS . 'models' . PS
    . get_include_path()
);

require_once 'Zupal/Bootstrap.php';
try {
	Zupal_Bootstrap::runMVC();
} catch(Exception $ex) {
	Zend_Debug::dump($ex->getMessage(), "Exception:");
	Zend_Debug::dump($ex->getTrace(), "Stack Trace:");
}