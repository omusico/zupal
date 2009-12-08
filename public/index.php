<?php

defined('DEBUG')
    || define('DEBUG', TRUE);

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

define('PUBLIC_PATH', dirname(__FILE__));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('PS') 
	|| define('PS', PATH_SEPARATOR);

defined('DS')
	|| define('DS', DIRECTORY_SEPARATOR);

defined('ZUPAL_PUBLIC_PATH') ||
	define('ZUPAL_PUBLIC_PATH', dirname(__FILE__));

defined('ZUPAL_ROOT_DIR')
	|| define('ZUPAL_ROOT_DIR', dirname(ZUPAL_PUBLIC_PATH));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

defined('ZUPAL_LIBRARY_PATH') ||
	define('ZUPAL_LIBRARY_PATH', ZUPAL_ROOT_DIR . DS . 'library');

//error_log('ZLP: ' . ZUPAL_LIBRARY_PATH);

defined('ZUPAL_MODULE_PATH') ||
	define('ZUPAL_MODULE_PATH', APPLICATION_PATH . DS . 'modules');


defined('ZUPAL_LAYOUT_PATH') ||
	define('ZUPAL_LAYOUT_PATH', ZUPAL_PUBLIC_PATH . DS . 'layouts');

if (!defined('ZEND_FRAMEWORK_LIBRARY')):
    if (getenv('ZEND_FRAMEWORK_LIBRARY')):
        define('ZEND_FRAMEWORK_LIBRARY', getenv('ZEND_FRAMEWORK_LIBRARY'));
        // best practice -- define the ZF path in your Apache Env.
    else:
        $zend_framework_library = realpath('../../ZendFramework/library');
       // error_log('looking for ZF at ' . $zend_framework_library);
        if (is_dir($zend_framework_library)):
            define('ZEND_FRAMEWORK_LIBRARY', $zend_framework_library);
            // a "good guess" as to where the framework is.
        else:
            throw new Exception("cannot find Zend Framework");
        endif;
    endif;
endif;

// Ensure library/ is on include_path
$includes =  array(
    ZEND_FRAMEWORK_LIBRARY, 
    ZUPAL_LIBRARY_PATH,
    get_include_path()
);

set_include_path(implode(PATH_SEPARATOR,$includes));

/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();