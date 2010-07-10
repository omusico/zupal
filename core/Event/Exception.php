<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of Exception
 *
 * @author bingomanatee
 */
class Zupal_Event_Exception
extends Exception
implements Zupal_Event_HandlerIF {
    /**
     *
     * @param Zupal_Event_EventIF $pEvent
     */
    public $event;
    
    public function respond(Zupal_Event_EventIF $pEvent) {
        $this->event = $pEvent;
        if ($m = $this->event->args('message')) {
            $this->message = $m;
        } else {
            $this->message = $pEvent->get_action();
        }
        $this->message .=  ': args = ' . print_r($pEvent->args()->getArrayCopy());
        throw $this;
    }

}

