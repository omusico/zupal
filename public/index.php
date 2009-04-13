<?php
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
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('LIBRARY_PATH') || 
	define('LIBRARY_PATH', ROOT_DIR . DS . 'library');


/** setup include path **/
set_include_path(
      LIBRARY_PATH . PS
    . APPLICATION_PATH . PS
    . APPLICATION_PATH . DS . "models" . PS
    . APPLICATION_PATH . DS . "forms" . PS
    . get_include_path()
);

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$application->run();