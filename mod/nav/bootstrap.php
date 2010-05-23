<?php

$nav_mod = Zupal_Module_Model_Mods::instance()->mod('nav');

$config = array(
        'basePath'  => $nav_mod->path,
        'namespace' => 'Nav',
);

$resourceLoader = new Zend_Loader_Autoloader_Resource($config);

$resourceLoader->addResourceType('model', 'model/', 'Model');
$resourceLoader->addResourceType('view',  'view/',  'View');

$nav_dom = Nav_Model_Nav::instance();
$crit = array('name' => 'home');
//$nav_dom->find_and_delete($crit);

if(!$nav_dom->has($crit)):
    $crit['menu'] = 'main';
    $crit['module'] = 'nav';
    $crit['uri'] = '/';
    $crit['label'] = 'Home';
    $crit['parent'] = 'root';
    $crit['weight'] = -100;
    $nav_dom->add($crit);
endif;

$crit = array('name' => 'admin');
if (!$nav_dom->has($crit)){
    $crit['menu'] = 'main';
    $crit['module'] = 'nav';
    $crit['uri'] = '/admin/';
    $crit['label'] = 'Admin';
    $crit['parent'] = 'home';
    $crit['weight'] = -100;
    $nav_dom->add($crit);
}

$menus = $nav_dom->find(array('menu' => 'main'));