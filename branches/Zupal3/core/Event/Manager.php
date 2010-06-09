<?php

class Zupal_Event_Manager {

    const EVENT_SUBJECT_TYPE_ANY = 'any';
    /**
     *
     * @var Zupal_Model_Container_IF
     */
    private $_route_source;

    public function  __construct() {
        $this->_route_source = new Zupal_Event_Routes_Domain();
    }

    /**
     * Adds a handler to the handlers database.
     *
     * @param string $pAction
     * @param string $pHandler
     * @param string $pSubject_if
     * @return NULL
     */
    public function add_handler($pAction,  $pHandler, $pSubject_if = self::EVENT_SUBJECT_TYPE_ANY) {
        $q = array('action' => $pAction,  'handler' => $pHandler, 'subject_type' => $pSubject_if);

        if ($this->_route_source->has($q)) {
            return;
        }

        $data = $this->_route_source->add($q);

        /**
         * clear the handler cache
         */
        if (array_key_exists($pAction, $this->_handlers)) {
            unset($this->_handlers[$pAction]);
        }
    }

    private $_handlers = array();
    public function get_handlers($pAction) {

        if (!array_key_exists($pAction, $this->_handlers)) {
            $this->load_handlers($pAction);
        }

        return $this->_handlers[$pAction];
    }


    public function load_handlers($pAction) {
        // find specific handlers for the data type

        if (!array_key_exists($pAction, $this->_handlers)) {
            $this->_handlers[$pAction] = array();
        }

        $q = array('action' => $pAction);
        $handlers = $this->_route_source->find($q);

        foreach($handlers as $h) {

            if (!array_key_exists($h->action, $this->_handlers)) {
                $this->_handlers[$h->action] = array($h->subject_type => array($h->handler));
            } elseif (!array_key_exists($h->subject_type, $this->_handlers[$h->action])) {
                $this->_handlers[$h->action][$h->subject_type] = array($h->handler);
            } elseif(!in_array($h->handler, $this->_handlers[$h->action][$h->subject_type])) {
                $this->_handlers[$h->action][$h->subject_type][] = $h->handler;
            }
        }
    }




    /**
     *
     * @param string $pAction
     * @param object $pSubject
     * @param array $pParams
     * @return Zupal_Event_Item
     */
    public function manage($pAction, array $pParams = array()) {
        $event = new Zupal_Event_Item($pAction, $pParams);

        $handlers = $this->get_handlers($event->get_action());
        if (array_key_exists(self::EVENT_SUBJECT_TYPE_ANY, $handlers)) {
            $active_handlers = $handlers[self::EVENT_SUBJECT_TYPE_ANY];
            unset($handlers[self::EVENT_SUBJECT_TYPE_ANY]);
        } else {
            $active_handlers = array();
        }

        if ($subject = $event->get_subject()) {
            $active_handlers = array();
            foreach($handlers as $subject_type => $s_handlers) {
                if ($subject instanceof $subject_type) {
                    foreach($s_handlers as $h) {
                        array_unshift($active_handlers, $h);
                    }
                }
            }
        }

        foreach($active_handlers as $handler) {
            $handler_obj = new $handler();
            $handler_obj->respond($event);

            switch($event->get_status()) {
                case Zupal_Event_EventIF::STATUS_DONE:
                    break 2;

                case Zupal_Event_EventIF::STATUS_ERROR:
                    break 2;

            }
        }

        if ($event->get_status() == Zupal_Event_EventIF::STATUS_WORKING && $event->get_subject() instanceof Zupal_Event_HandlerIF) {
            $event->get_subject()->respond($event);
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
