<?php
define ('ZF_LIB', '/users/bingomanatee/documents/sites/ZendFramework/library');

set_include_path(ZF_LIB . PATH_SEPARATOR
    . dirname(__FILE__) . '/library' . PATH_SEPARATOR
    . get_include_path());

error_log(__FILE__ . ': includes = '
    . str_replace(PATH_SEPARATOR, "\n: ", get_include_path()));

define('APPLICATION_PATH', dirname(__FILE__));
define ('APPLICATION_ENV', 'production');

/** Zend_Application */
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/app.ini'
);

global $_zend_loaded;
$_zend_loaded = TRUE;

$application->setBootstrap(dirname(__FILE__) . '/Bootstrap.php')->bootstrap();