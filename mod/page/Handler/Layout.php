<?php

/**
 * Description of Layout
 *
 * @author bingomanatee
 */
class Page_Handler_Layout
implements Zupal_Event_HandlerIF {
    public function respond(Zupal_Event_EventIF $pEvent) {
        switch($pEvent->get_action()) {
            case 'layout':
                $this->_action_layout($pEvent);

                break;
        }
    }

    private function _action_layout(Zupal_Event_EventIF $pEvent) {
        $linst = Page_Model_Layouts::instance();
        $route = $pEvent->args('route');
        $route_name = $route->name;

        $page_layout = $linst->find_one(array('page' => $route_name), 'weight');
        $any_page_layouts = $linst->find(array('page' => '*'), 'weight');
        
        if ((!$page_layout) && count($any_page_layouts)) {
            $page_layout = array_shift($any_page_layouts);
        }

        if ($page_layout) {
            $pEvent->set_result($page_layout->render($pEvent));
            $pEvent->set_status(Zupal_Event_EventIF::STATUS_DONE);
        }
    }
}
