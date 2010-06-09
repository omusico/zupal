<?php

/**
 * Description of Content
 *
 * @author bingomanatee
 */
class Home_View_Content
implements Zupal_Event_HandlerIF {
    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent, $phase = Zupal_Event_HandlerIF::PHASE_ACTION) {
        switch ($pEvent->get_action()) {
            case 'render':
                return self::_handle_render($pEvent, $phase);
                break;

        }
    }

    protected function _handle_render() {
        switch ($phase) {
            case Zupal_Event_HandlerIF::PHASE_PRE:

                ob_start();
                ?>
<p>Home Page</p>
                <?php
                $pEvent->set_response(ob_get_clean());
                $pEvent->set_status(Zupal_Event_EventIF::STATUS_DONE);
                break;
        }

    }
}
