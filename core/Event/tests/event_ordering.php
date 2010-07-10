<?php

class Event_Test_Ordering_Alpha_Handler
implements Zupal_Event_HandlerIF {

    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent) {

        switch ($pEvent->get_action()) {

            case 'foo_action':

            case 'bar_action':

                event_test_ordering_echo_response($pEvent, get_class($this));
                break;

        }

    }

}

class Event_Test_Ordering_Beta_Handler
implements Zupal_Event_HandlerIF {

    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent) {

        switch ($pEvent->get_action()) {

            case 'foo_action':

            case 'bar_action':

                event_test_ordering_echo_response($pEvent, get_class($this));
                break;

        }

    }

}


class Event_Test_Ordering_Theta_Handler
implements Zupal_Event_HandlerIF {

    /**
     * handles event
     * @param Zupal_Event_EventIF $pEvent
     */
    public function respond(Zupal_Event_EventIF $pEvent) {

        switch ($pEvent->get_action()) {

            case 'foo_action':

                event_test_ordering_echo_response($pEvent, get_class($this));
                break;

        }

    }

}

function event_test_ordering_echo_response(Zupal_Event_EventIF $pEvent, $pRespondant){
    error_log(__FILE__ . ': action ' . $pEvent->get_action() . ' handled by ' . $pRespondant);
}

Zupal_Event_Manager::add_handler('foo_action', 'Event_Test_Ordering_Alpha_Handler', Zupal_Event_Manager::EVENT_SUBJECT_TYPE_ANY, 1, 'event');
Zupal_Event_Manager::add_handler('foo_action', 'Event_Test_Ordering_Beta_Handler', Zupal_Event_Manager::EVENT_SUBJECT_TYPE_ANY, 2, 'event');
Zupal_Event_Manager::add_handler('foo_action', 'Event_Test_Ordering_Theta_Handler', Zupal_Event_Manager::EVENT_SUBJECT_TYPE_ANY, -1, 'event');

Zupal_Event_Manager::add_handler('bar_action', 'Event_Test_Ordering_Alpha_Handler', Zupal_Event_Manager::EVENT_SUBJECT_TYPE_ANY, 1, 'event');
Zupal_Event_Manager::add_handler('bar_action', 'Event_Test_Ordering_Beta_Handler', Zupal_Event_Manager::EVENT_SUBJECT_TYPE_ANY, 0, 'event');

error_log(<<<FB
Expected Order:
    Foo Action:
        Theta
        Alpha
        Beta
    Bar Action:
        Beta
        Alpha
FB

        );
Zupal_Event_Manager::event('foo_action');
Zupal_Event_Manager::event('bar_action');