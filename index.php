<?php

define('ZF_PATH', '/Applications/Zend/library');

include_once('bootstrap.php');

$em = Zupal_Event_Manager::instance();

try {

    if (array_key_exists('muri', $_SERVER)) {
        $path = $_SERVER['muri'];
    } else {
        $path = '/';
    }
    $p = array('path' => $path);
    $route_event = $em->manage('route', $p);
    if ($route_event->get_status() == Zupal_Event_EventIF::STATUS_ERROR){
        throw new Exception($route_event->get_result());
    }
    echo $route_event->get_result();
    
} catch (Exception $ex) {
    $err_event = $em->handle('error', NULL, array('message' => 'Cannot find page', 'path' => $path));
}



