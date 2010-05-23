<?php

$user_mod = Zupal_Module_Model_Mods::instance()->mod('user');

$conf = array(
'basePath'  => $user_mod->path,
'namespace' => 'User');

$resourceLoader = new Zend_Loader_Autoloader_Resource($conf);

$resourceLoader->addResourceType('model', 'model/', 'Model');
$resourceLoader->addResourceType('view',  'view/',  'View');
