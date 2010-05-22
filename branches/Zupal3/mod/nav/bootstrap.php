<?php

function nav_init() {
    $mod_paths = Zupal_Module_Path::instance();

    $config = array(
                    'basePath'  => $mod_paths['nav'],
                    'namespace' => 'Nav',
    );

    $resourceLoader = new Zend_Loader_Autoloader_Resource($config);

    $resourceLoader->addResourceType('model', 'model/', 'Model');
    $resourceLoader->addResourceType('view',  'view/',  'View');
}

function nav_register() {

}

function nav_unregister() {

}