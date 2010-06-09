<?php


/**
 *
 * @author bingomanatee
 */
interface Zupal_Event_EventIF {
    
    /**
     * @return ArrayObject
     */
    public function args($pKey = NULL);
    
    public function set_arg($pKey, $pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ ACTION @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *  Action is the one un-optional parameter of an event. 
 * @param string $pValue
 */
    public function set_action($pValue);
/**
 * the name of the action - the primary identifier for the event. 
 * @return string
 */
    public function get_action();

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ STATUS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * The state of the event. Starts as working, then becomes either done or error. 
     * @param string $pValue
     */
    public function set_status($pValue);

    const STATUS_WORKING    = 'working';
    const STATUS_DONE       = 'done';
    const STATUS_ERROR      = 'error';
    const STATUS_PHASE_DONE = 'phase_done';

    public function get_status();

    /* @@@@@@@@@@@@@@@@ RESULT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * The result of the action - optional. Some events 
     * act soley apon their subject -- some change the status of multiple records
     * but don't retain them throgh the event. 
     * 
     * @param variant $pValue 
     */
    public function set_result($pValue);

    public function get_result();

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ SUBJECT @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * an element on which the action is being performed.
     * If an action has a subject and the subject is an event handler,
     * the event will reach the subject unless it is intercepted by another handler.
     *
     * @param object $pValue
     */
    public function set_subject(Zupal_Event_HandlerIF $pValue);

    /**
     * @return Zupal_Event_HandlerIF
     */
    public function get_subject();


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ACTOR @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pValue
     */
    public function set_actor($pValue);

    /**
     * if an event is an action between two agents,
     * the actor is the assertive agent.
     * @return variant
     */
    public function get_actor();

    /**
     *
     * @param Zupal_Event_EventIF $pEvent 
     */
    public function merge_event(Zupal_Event_EventIF $pEvent);
}

