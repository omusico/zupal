<?php

class Zupal_Event_Manager {

    /**
     *
     * @var Zupal_Model_Container_IF
     */
    private $_route_source;

    public function  __construct() {
        $this->_route_source = new Zupal_Event_Routes_Domain();
    }

    public function add_handler($pEvent,  $pHandler, $pTarget = 'any') {
        $q = array('event' => $pEvent,  'handler' => $pHandler, 'target' => $pTarget);

        if ($this->_route_source->has($q)) {
            return;
        }

        $data = $this->_route_source->add($q);
    }

    public function get_handlers($pEvent, $pTarget) {

        // find specific handlers for the data type
        $q = array('event' => $pEvent, 'target' => $pTarget);
        $handlers = $this->_route_source->find($q);

        // append general handlers for the event type
        $q2 = array('event' => $pEvent, 'target' => 'any');
        $handlers += $this->_route_source->find($q2);

        return $handlers;
    }

    /**
     *
     * @param string $pEvent
     * @param object $pTarget
     * @param array $pParams
     * @return Zupal_Event_Item
     */
    public function handle($pEvent, $pTarget, $pParams = array()) {
        $event = new Zupal_Event_Item($pEvent, $pTarget, $pParams);

        foreach($this->get_handlers($pEvent, $event->target_type()) as $handler) {
            $htype = $handler->handler;
            $handler_obj = new $htype();
            $htype->handle($event);

            switch($event->status) {
                case Zupal_Event_Item::STATUS_DONE:
                    return $event;
                    break;

                case Zupal_Event_Item::STATUS_ERROR:
                    return $event;
                    break;
                
            }
        }
        
        return $event;
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Zupal_Event_Manager
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}