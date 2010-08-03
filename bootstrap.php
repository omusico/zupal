<?php
defined('D') || define('D', DIRECTORY_SEPARATOR);
defined('P') || define('P', PATH_SEPARATOR);

define('ZUPAL_ROOT', __DIR__);
define('ZUPAL_MODULES', ZUPAL_ROOT . D . 'mod');
define('ZUPAL_CORE', ZUPAL_ROOT . D . 'core');

set_include_path(get_include_path() . P . ZUPAL_ROOT);

require_once 'Zend/Loader/Autoloader.php';

//error_log('Include == ' . get_include_path());

//Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);


$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                'basePath'  => ZUPAL_CORE,
                'namespace' => 'Zupal',
));

$resourceLoader->addResourceType('model', 'Model/', 'Model');
$resourceLoader->addResourceType('event', 'Event/', 'Event');
$resourceLoader->addResourceType('view',  'View/',  'View');
$resourceLoader->addResourceType('module',  'Module/',  'Module');