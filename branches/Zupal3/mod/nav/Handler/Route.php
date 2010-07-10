<?php

/**
 * Description of Route
 *
 * @author bingomanatee
 */
class Nav_Handler_Route
implements Zupal_Event_HandlerIF {
    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent) {
        switch($pEvent->get_action()) {
            case 'route':
                $nav_load_event = Zupal_Event_Manager::event('load_nav');
                //@TODO: error check
                return $this->_action_route($pEvent);
                break;
            case 'load_nav':
                return $this->_action_load_nav($pEvent);
                break;
        }
    }


    private function _action_load_nav(Zupal_Event_EventIF $pEvent) {
        $mod_stub = Zupal_Module_Model_Mods::instance();
        $nav_stub = Nav_Model_Nav::instance();
        $navs = $nav_stub->find_all(NULL, 'uri');

        foreach($mod_stub->find_all() as $module) {

            if (array_key_exists('nav', $module->profile)) {
                foreach($module->profile['nav'] as $nav) {
                    if (!$nav_stub->has($nav)) {
                        $new_menu = $nav_stub->add($nav);
                    }
                }
            }
        }

    }

    private function _action_route(Zupal_Event_EventIF $pEvent) {

        $path = $pEvent->args('path');
        if (!$path) {
            $path = D;
        }

        $q = array('uri' => $path);

        $path_parts = array();
        $nav_stub = Nav_Model_Nav::instance();
        $nav = $nav_stub->find_one($q);

        if (!$nav) {
            $parts = explode(D, rtrim($path, D));
            while(!$nav && count($parts)) {
                $path_parts[] = array_pop($parts);
                $q['uri'] =  D . join(D, $parts);
                $nav = $nav_stub->find_one($q);
            }
        }

        if ($nav) {
            $action = $nav->action;
            $p = array(
                    'path_parts' => $path_parts,
                    'route' => $nav
            );

            if ($nav->subject) {
                $s = $nav->subject;
                $p['subject'] = new $s();
            }

            $route_event = Zupal_Event_Manager::event($action, $p);

            if (!($route_event->get_status() == Zupal_Event_EventIF::STATUS_ERROR)) {
                $page_type = $route_event->args('page_type');

                switch ($page_type) {
                    case 'xml':
                        break;

                    case 'json':
                        break;

                    case 'layout':
                        $params = $route_event->args()->getArrayCopy();
                        $params['content'] = $route_event->get_result();
                        $params['page'] = $route_event->get_action();
                        $route_event = Zupal_Event_Manager::event('layout', $params);
                        break;

                    case 'page':
                    default:
                    //page manages its own frame - don't layout
                }

            }
            $pEvent->merge_event($route_event);

        }
    }
}

