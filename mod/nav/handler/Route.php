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
                        $path_parts[] = array_pop($path);
                        $q['uri'] =  D . join(d, $parts);
                        $nav = $nav_stub->find_one($q);
                    }
                }

                if ($nav) {
                    $action = $nav->action;
                    $p = array(
                            'path_parts' => $path_parts,
                            'nav' => $nav
                    );

                    if ($nav->subject) {
                        $s = $nav->subject;
                        $p['subject'] = new $s();
                    }

                    $em = Zupal_Event_Manager::instance();
                    $route_event = $em->manage($action, $p);

                    if (!$route_event->get_status() == Zupal_Event_EventIF::STATUS_ERROR) {
                        $page_type = $route_event->args('page_type');

                        switch ($page_type) {
                            case 'xml':
                                break;

                            case 'json':
                                break;

                            case 'layout':
                                $params = $route_event->args();
                                $params['content'] = $route_event->get_result();
                                $route_event = $em->manage('layout', $params);
                                break;

                            case 'page':
                            default:
                            //page manages its own frame - don't layout
                        }

                    }
                    $pEvent->merge_event($route_event);

                }
                break;
        }
    }

}

