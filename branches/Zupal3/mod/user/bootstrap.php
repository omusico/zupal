<?php

function user_init() {
    global $mod_paths;
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