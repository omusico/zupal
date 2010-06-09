<?php

$nav_dom = Nav_Model_Nav::instance();
$crit = array('name' => 'home');
//$nav_dom->find_and_delete($crit);

if(!$nav_dom->has($crit)):
    $crit['menu'] = 'main';
    $crit['module'] = 'home';
    $crit['uri'] = '/';
    $crit['label'] = 'Home';
    $crit['parent'] = 'root';
    $crit['weight'] = -100;
    $crit['action']  = 'homepage';
    $crit['subject'] = 'Home_Handler_Homepage';
    $nav_dom->add($crit);
endif;
