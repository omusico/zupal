<?php

function nav_init() {
    global $mod_paths;
    $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                    'basePath'  => $mod_paths['nav'],
                    'namespace' => 'Nav',
    ));

    $resourceLoader->addResourceType('model', 'model/', 'Model');
    $resourceLoader->addResourceType('view',  'view/',  'View');
}

function nav_register() {

}

function nav_unregister() {

}