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

// $menus = $nav_dom->find(array('menu' => 'main'));

Zupal_View_Page::instance()->getView()->placeholder('nav')->set(Nav_Model_Nav::instance()->render_menu('main'));