<?php

/**
 * Description of Layout
 *
 * @author bingomanatee
 */
class Page_Handler_Layout
implements Zupal_Event_HandlerIF{
    public function respond(Zupal_Event_EventIF $pEvent){
        switch($pEvent->get_action()){
            case 'layout':


                $page_mod = Zupal_Module_Model_Mods::instance()->mod('page');

                break;
        }
    }
}
