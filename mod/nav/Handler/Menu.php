<?php

class Nav_Handler_Menu
implements Zupal_Event_HandlerIF {

    public function respond(Zupal_Event_EventIF $pEvent) {
        switch($pEvent->get_action()) {
            case 'menu':
                $this->_action_menu($pEvent);

                break;
        }
    }

    private function _action_menu(Zupal_Event_EventIF $pEvent) {
        $nav_stub = Nav_Model_Nav::instance();

        $nav_array = $nav_stub->menu($pEvent->args('section'));

        $args = array('menu' => $nav_array);

        $filter_event = Zupal_Event_Manager::event('filter_menu', $args);

        if (!($filter_event->is_error())) {
            $menu = $filter_event->args('menu');
        }
        $pEvent->set_result(new Zend_Navigation($menu));

        $pEvent->set_status(Zupal_Event_EventIF::STATUS_DONE);
    }

}