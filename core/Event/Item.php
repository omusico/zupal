<?php

/**
 * Description of Item
 *
 * @author bingomanatee
 */
class Zupal_Event_Item
implements Zupal_Event_EventIF {

    private $_action;
    private $_status;
    private $_result;

    private $_subject;
    private $_subject_type;

    private $_actor;
    private $_actor_type;

    public function __construct($pAction, array $pParams = array()) {
        $this->_args = new ArrayObject();
        $this->set_action($pAction);

        foreach($pParams as $key => $value) {
            switch(strtolower(trim($key))) {

                case 'action':
                    $this->set_action($value);
                    break;

                case 'actor':
                    $this->set_actor($value);
                    break;

                case 'subject':
                    $this->set_subject($value);
                    break;

                case 'subject_type':
                    $this->_set_subject_type($value);
                    break;

                default:
                    $this->args()->offsetSet($key, $value);
            }
        }
        $this->set_status(Zupal_Event_EventIF::STATUS_WORKING);
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ACTION @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function set_action($pValue) {
        if (!is_string($pValue) || !$pValue) {
            throw new Exception(__METHOD__ . ': bad action passed: ' . print_r($pValue, 1));
        }
        $pValue = trim(strtolower($pValue));
        $this->_action = $pValue;
    }
    /**
     * the name of the action - the primary identifier for the event.
     * @return string
     */
    public function get_action() {
        return $this->_action;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ RESULT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * The result of the action - optional. Some events
     * act soley apon their subject -- some change the status of multiple records
     * but don't retain them throgh the event.
     *
     * @param variant $pValue
     */
    public function set_result($pValue) {
        $this->_result = $pValue;
    }

    public function get_result() {
        return $this->_result;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@ SUBJECT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function set_subject(Zupal_Event_HandlerIF $pValue) {
        $this->_subject = $pValue;
    }

    public function get_subject() {
        return $this->_subject;
    }

    /**
     * the interface that is expected to accept the action.
     * The subject may respond to several interfaces, but the one
     * that accepts the action as defined is the one that is listed.
     * note - all subjects must be an Zupal_Event_HandlerIF
     * so subject_type should be something more specific
     * if it is set manually
     */
    public function subject_type() {
        if ($this->_subject_type) {
            return $this->_subject_type;
        } elseif(!$this->get_subject()) {
            return NULL;
        } else {
            return get_class($this->get_subject());
        }
    }

    private function _set_subject_type($pValue) {
        if (!is_string($pValue) || !$pValue) {
            throw new Exception(__METHOD__ . ': bad subject type passed: ' . print_r($pValue, 1));
        }

        $this->_subject_type = trim(strtolower($pValue));
    }

    /**
     *
     * @param <type> $pValue
     */
    public function set_actor($pValue) {
        $this->_actor = $pValue;
    }

    /**
     * if an event is an action between two agents,
     * the actor is the assertive agent.
     * @return variant
     */
    public function get_actor() {
        return $this->_actor;
    }

    public function actor_type() {
        if ($this->_actor_type) {
            return $this->_actor_type;
        } elseif(!$this->get_actor()) {
            return NULL;
        } else {
            return get_class($this->get_actor());
        }
    }

    /* @@@@@@@@@@@@@@@@@@@@ STATUS @@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * The state of the event. Starts as working, then becomes either done or error.
     * @param string $pValue
     */
    public function set_status($pValue) {
        $this->_status = $pValue;
    }

    public function get_status() {
        return $this->_status;
    }

    public function is_error() {
        if ($this->get_status() == self::STATUS_ERROR) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_done() {
        if ($this->get_status() == self::STATUS_DONE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_working() {
        if ($this->get_status() == self::STATUS_WORKING) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ARGUMENBTS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * @var ArrayObject
     */
    private $_args;

    /**
     * @return array
     */
    public function args($pKey = NULL) {
        if (is_null($pKey)) {
            return $this->_args;
        } elseif ($this->_args->offsetExists($pKey)) {
            return $this->_args[$pKey];
        } else {
            return NULL;
        }
    }

    public function set_arg($pKey, $pValue){
        $this->_args[$pKey] = $pValue;
    }

    public function merge_event(Zupal_Event_EventIF $pEvent){
        $this->set_result($pEvent->get_result());
        $args = $this->args();

        $my_args = $this->args();
        foreach($args as $k => $v){
            $my_args[$k] = $v;
        }
    }
}

