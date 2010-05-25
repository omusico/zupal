<?php

$admin_mod = Zupal_Module_Model_Mods::instance()->mod('admin');

$conf = array(
'basePath'  => $admin_mod->path,
'namespace' => 'Admin');

$resourceLoader = new Zend_Loader_Autoloader_Resource($conf);

$resourceLoader->addResourceType('model', 'Model/', 'Model');
$resourceLoader->addResourceType('view',  'View/',  'View');

$nav_dom = Nav_Model_Nav::instance();

$crit = array('name' => 'admin');

$nav_dom->find_and_delete($crit);

if (!$nav_dom->has($crit)){
    $crit['menu']       = 'main';
    $crit['module']     = 'nav';
    $crit['uri']        = '/admin/';
    $crit['label']      = 'Admin';
    $crit['parent']     = 'home';
    $crit['weight']     = -100;
    $crit['event']      = 'page_content_admin';
    $nav_dom->add($crit);
}

Zupal_Event_Manager::instance()->add_handler('page_content_admin', new Admin_View_Admin());