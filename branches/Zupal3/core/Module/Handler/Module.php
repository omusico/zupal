<?php

/**
 * Description of Module
 *
 * @author bingomanatee
 */
abstract class Zupal_Module_Handler_Module
Extends Zupal_Event_HandlerIF
{

    public function respond(Zupal_Event_EventIF $pEvent){

        switch($pEvent->get_action()){

            case 'load':
                $this->_load($pEvent);
            break;

            case 'add':
                $this->_add($pEvent);
            break;

            case 'reset':
                $this->_reset($pEvent);
            break;

            case 'remove':
                $this->_remove($pEvent);
            break;

        }

    }

    protected abstract function _load(Zupal_Event_EventIF $pEvent);

    protected abstract function _remove(Zupal_Event_EventIF $pEvent);

    protected abstract function _add(Zupal_Event_EventIF $pEvent);

    protected abstract function _reset(Zupal_Event_EventIF $pEvent);

}
