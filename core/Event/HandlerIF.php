<?php
/**
 *
 * @author bingomanatee
 */
interface Zupal_Event_HandlerIF {
    
    /**
     * handles event 
     * @param Zupal_Event_EventIF $pEvent 
     */
    public function respond(Zupal_Event_EventIF $pEvent);

}
