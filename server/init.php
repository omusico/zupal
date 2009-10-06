<?php
/**
 * The location of your zend framework will determine the config node in configs/app.ink
 */
foreach(array( 
            'production' => '/users/bingomanatee/documents/sites/ZendFramework/library',
            'cybercockroach' => '/home/bingoman/public_html/cybercockroach.com/ZendFramework/library'
        ) as $env => $lib):
    if (is_dir($lib)):
        define ('ZF_LIB', $lib);
        define ('APPLICATION_ENV', $env);
    endif;
endforeach;

if (!defined('ZF_LIB')):
    die('No Zend Framework Library');
endif;

set_include_path(ZF_LIB . PATH_SEPARATOR
    . dirname(__FILE__) . '/library' . PATH_SEPARATOR
    . get_include_path());


define('APPLICATION_PATH', dirname(__FILE__));

/** Zend_Application */
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/app.ini'
);

global $_zend_loaded;
$_zend_loaded = TRUE;

$application->setBootstrap(dirname(__FILE__) . '/Bootstrap.php')->bootstrap();