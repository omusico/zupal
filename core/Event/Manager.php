<?php

class Zupal_Event_Manager {

    const EVENT_SUBJECT_TYPE_ANY = 'any';
    /**
     *
     * @var Zupal_Model_Container_IF
     */
    private static $_route_source;

    /**
     *
     * @return Zupal_Event_Routes_Domain
     */
    protected static function routes() {
        if (!self::$_route_source) {
            self::$_route_source = new Zupal_Event_Routes_Domain();
        }
        return self::$_route_source;
    }

    /**
     * Adds a handler to the handlers database.
     *
     * @param string $pAction
     * @param string $pHandler
     * @param string $pSubject_if
     * @return NULL
     */
    public static function add_handler($pAction,  $pHandler = '', $pSubject_if = self::EVENT_SUBJECT_TYPE_ANY, $pWeight = 0, $pModule = '') {

        if (is_array($pAction)) {
            $q = $pAction;
        } else {
            $q = array('action' => $pAction,  'handler' => $pHandler, 'subject_type' => $pSubject_if, 'weight' => $pWeight, 'module' => $pModule);
        }

        if (empty($q['subject_type'])) {
            $q['subject_type'] = self::EVENT_SUBJECT_TYPE_ANY;
        }

        if (self::routes()->has($q)) {
            return;
        }

        $data = self::routes()->add($q);

        /**
         * clear the handler cache
         */
        if (array_key_exists($pAction, self::$_handlers)) {
            unset(self::$_handlers[$pAction]);
        }
    }

    private static $_handlers = array();

    public static function handlers(Zupal_Event_EventIF $event) {
        $pAction = $event->get_action();

        if (!array_key_exists($pAction, self::$_handlers)) {
            self::load_handlers($pAction);
        }

        $action_handlers = self::$_handlers[$pAction];

        if (array_key_exists(self::EVENT_SUBJECT_TYPE_ANY, $action_handlers)) {
            $event_handlers = $action_handlers[self::EVENT_SUBJECT_TYPE_ANY];
            unset($action_handlers[self::EVENT_SUBJECT_TYPE_ANY]);
        } else {
            $event_handlers = array();
        }

        if ($subject = $event->get_subject()) {
            $subject_handlers = array();
            foreach($action_handlers as $subject_type => $action_subject_handlers) {
                if ($subject instanceof $subject_type) {
                    $subject_handlers = array_merge($subject_handlers, $action_subject_handlers);
                }
            }
            //@TODO: sort handlers by key, instance specificity

            $event_handlers = array_merge($subject_handlers, $event_handlers);
        }

        return $event_handlers;
    }


    public static function load_handlers($pAction) {
        // find specific handlers for the data type

        if (!array_key_exists($pAction, self::$_handlers)) {
            self::$_handlers[$pAction] = array();
        }

        $q = array('action' => $pAction);
        $handlers = self::routes()->find($q, NULL, 'weight');

        foreach($handlers as $h) {

            if (!array_key_exists($h->action, self::$_handlers)) {
                self::$_handlers[$h->action] = array($h->subject_type => array($h->handler));
            } elseif (!array_key_exists($h->subject_type, self::$_handlers[$h->action])) {
                self::$_handlers[$h->action][$h->subject_type] = array($h->handler);
            } elseif(!in_array($h->handler, self::$_handlers[$h->action][$h->subject_type])) {
                self::$_handlers[$h->action][$h->subject_type][] = $h->handler;
            }
        }
    }

    public static function args($pParams) {

        if (is_array($pParams)) {
            $args = $pParams;
        } elseif($pParams instanceof ArrayObject) {
            $args = $pParams->getArrayCopy();
        } elseif($pParams instanceof Zupal_Event_HandlerIF) {
            $args = array('subject' => $pParams);
        } else {
            $args = array();
        }


        if (array_key_exists('subject', $args) && (!($args['subject'] instanceof Zupal_Event_HandlerIF))) {
            throw new exception(__METHOD__ . ': non-Zupal_Event_HandlerIF set as subject: ' . print_r($pParams['subject'], 1));
        }
        
        return $args;
    }


    /**
     * Execute an event on an object.
     * @param string $pAction
     * @param Zupal_Event_HandlerIF $pSubject
     * @param array $pParams
     * @return Zupal_Event_Item
     */
    public static function event($pAction, $pParams = array()) {

        if (!$pAction) {
            throw new Exception(__METHOD__ . ': empty action event called');
        }

        switch($pAction) {
            case 'load':
                break;
            case 'update':
                break;
            default:
                error_log(__METHOD__ . ": starting $pAction");
        }

        $args = self::args($pParams);

        $event = new Zupal_Event_Item($pAction, $args);

        /*
         * Initialize handler queue. 
         * If there are handlers for any subject type, 
         * store those in the queue. They will be pushed to the end 
         * if matching subject type handlers exist. 
        */
        $event_handlers = self::handlers($event);

        foreach($event_handlers as $handler) {
            if (class_exists($handler)) {
                $handler_obj = new $handler();
                $handler_obj->respond($event);

                switch($event->get_status()) {
                    case Zupal_Event_EventIF::STATUS_DONE:
                        return $event;
                        break;

                    case Zupal_Event_EventIF::STATUS_ERROR:
                        return $event;
                        break;

                }
            }
        }

        if ($event->get_status() == Zupal_Event_EventIF::STATUS_WORKING &&
                $event->get_subject() instanceof Zupal_Event_HandlerIF) {
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
