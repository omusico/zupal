<?php

function user_init() {

    $mod_paths = Zupal_Module_Path::instance();

    $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                    'basePath'  => $mod_paths['user'],
                    'namespace' => 'User',
    ));

    $resourceLoader->addResourceType('model', 'model/', 'Model');
    $resourceLoader->addResourceType('view',  'view/',  'View');
}

function user_register() {

}

function user_unregister() {

}