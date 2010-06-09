<?php


/**
 * Description of Manage
 *
 * @author bingomanatee
 */
class Home_Handler_Homepage
implements Zupal_Event_HandlerIF {

    public function respond(Zupal_Event_EventIF $pEvent) {

        switch($pEvent->get_action()) {
            case 'homepage':
                $pEvent->set_arg('page_type', 'layout');
                $pEvent->set_arg('layout_type', 'homepage');
                ob_start();
                ?>
<ul>
    <li>
        Stories
    </li>
    <li>
        Rss
    </li>
    <li>
        Nude Pics of Rush Limbaugh/Alligator sex tape!
    </li>
</ul>
                <?
                $pEvent->set_result(ob_get_clean());
                $pEvent->set_status(Zupal_Event_EventIF::STATUS_DONE);
                break;


        }

    }
}

