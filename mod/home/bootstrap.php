<?php

$nav_dom = Nav_Model_Nav::instance();
$crit = array('name' => 'home');
//$nav_dom->find_and_delete($crit);

if(!$nav_dom->has($crit)):

    $nav_dom->add($mod->profile['home_menu']);
endif;
