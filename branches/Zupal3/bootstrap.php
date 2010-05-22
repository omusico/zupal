<?php
define('D', DIRECTORY_SEPARATOR);
define('P', PATH_SEPARATOR);

define('ZUPAL_ROOT', dirname(__FILE__));
set_include_path(get_include_path() . P . ZF_PATH . P . ZUPAL_ROOT);

require_once 'Zend/Loader/Autoloader.php';

error_log('Include == ' . get_include_path());

Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);


$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                'basePath'  => ZUPAL_ROOT . D . 'core',
                'namespace' => 'Zupal',
));

$resourceLoader->addResourceType('model', 'Model/', 'Model');
$resourceLoader->addResourceType('event', 'Event/', 'Event');
$resourceLoader->addResourceType('view',  'View/',  'View');
$resourceLoader->addResourceType('module',  'Module/',  'Module');

require ZUPAL_ROOT . D . 'mod' . D . 'bootstrap.php';