<?php
/**
 * Description of Admin
 *
 * @author bingomanatee
 */
class Admin_View_Admin
implements Zupal_Event_HandlerIF {

    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent) {
        ob_start();
        ?>
<p>Admin Page</p>
        <?php
        $pEvent->response = ob_get_clean();
    }
}