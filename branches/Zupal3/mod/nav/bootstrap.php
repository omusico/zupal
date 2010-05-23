<?php

$nav_mod = Zupal_Module_Model_Mods::instance()->mod('nav');

$config = array(
        'basePath'  => $nav_mod->path,
        'namespace' => 'Nav',
);

$resourceLoader = new Zend_Loader_Autoloader_Resource($config);

$resourceLoader->addResourceType('model', 'model/', 'Model');
$resourceLoader->addResourceType('view',  'view/',  'View');

$nav = Nav_Model_Nav::instance();
$crit = array('name' => 'home');