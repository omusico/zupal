<?php

define('ZF_PATH', '/Applications/Zend/library');

include_once('bootstrap.php');

if ($nav_module = Zupal_Module_Model_Mods::instance()->mod('nav')) {
    $nav_dom = Nav_Model_Nav::instance();

    if ($_SERVER['muri']) {
        $path = $_SERVER['muri'];
    } else {
        $path = '/';
    }

    $nav = FALSE;
    $args = array();
    $page = $nav_dom->path_to_nav($path);
    extract($page);

    if ($nav){
        $event = Zupal_Event_Manager::instance()->handle('page_load', $page, $args);
    }
}

$l = Zupal_View_Page::instance();

echo $l->render();