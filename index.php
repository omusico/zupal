<?php

define('ZF_PATH', '/Applications/Zend/library');

include_once('bootstrap.php');

include_once( ZUPAL_CORE . D . 'Event' . D . 'tests' . D . 'event_ordering.php');

try {

    if (array_key_exists('muri', $_SERVER)) {
        $path = $_SERVER['muri'];
    } else {
        $path = '/';
    }
    $p = array('path' => $path);
    $route_event = Zupal_Event_Manager::event('route', $p);
    if ($route_event->get_status() == Zupal_Event_EventIF::STATUS_ERROR){
        throw new Exception($route_event->get_result());
    }
    echo $route_event->get_result();
    
} catch (Exception $ex) {
    $err_event = Zupal_Event_Manager::event('error', array('exception' =>  new Zupal_Event_Exception($ex->getMessage()), 'message' => 'Cannot find page', 'path' => $path));
}



