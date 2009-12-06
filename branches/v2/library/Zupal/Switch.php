<?

class Zupal_Switch {

    public function __construct($pValue, $pExecute = FALSE, $pDefault_handler = NULL, $pParameters = NULL) {
        if ($pDefault_handler):
            $this->set_default_handler($pDefault_handler);
        endif;

        if ($pExecute):
            $this->execute($pValue, $pParameters);
        else:
            $this->set_value($pValue);
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ execute @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * note -- throws an exception if no handler set to respond to the value passed.
     * If you don't want this behavior, ensure the presence of a default (stub) handler.
     *
     * @param scalar $pValue = NULL
     * @return Switch_Manager_Handler
     */
    public function execute ($pValue = NULL, $pParameters = NULL) {
        if (!is_null($pValue)):
            $this->set_value($pValue);
        endif;

        $handlers = $this->get_handlers();

        if ($default = $this->get_default_handler()):
            array_push($handlers, $default);
        endif;

        foreach($handlers as $key => $handler):
            $value = $this->get_value();
            /**
             * note -- this redundant call is intentional --
             * there may be situations where the handlers themselves want
             * to change the value on the fly. 
             */
            if (($key == $value) || ($handler->responds_to($value))):
                if ($handler->handle($value, $pParameters) || $handler->terminate()):
// the handler WILL run its handle scrpt
// the return value indicates whether or not you want to TERMINATE the looping.
                    return $handler;
                endif;
            endif;
        endforeach;
        throw new Exception(__METHOD__ . ': unhandled value ' . $pValue); //
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_value = null;
    /**
     * @return class;
     */

    public function get_value() { return $this->_value; }

    public function set_value($pValue) { $this->_value = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ handlers @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_handlers = array();

    public function set_handler( $pHandler, $pKey = NULL)
    {
        if (!$pHandler instanceof Zupal_Switch_Handler_Abstract):
            $pHandler = new Zupal_Switch_Handler($pHandler);
        endif;

        $pHandler->set_manager($this);
        if (is_null($pKey)):
            array_push($this->_handlers, $pHandler);
        else:
            $this->_handlers[$pKey] = $pHandler;
        endif;
    }

    public function get_handler($pID) { return $this->_handlers[$pID]; }

    public function get_handlers() { return $this->_handlers; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ default_handler @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_default_handler = null;
    /**
     * @return class;
     */

    public function get_default_handler() { return $this->_default_handler; }

    public function set_default_handler($pHandler) {
        if (!$pHandler instanceof Zupal_Switch_Handler_Abstract):
            $pHandler = new Zupal_Switch_Handler($pHandler);
        endif;
        $pHandler->always_handle();
        $pHandler->set_terminate(TRUE);
        $this->_default_handler = $pHandler;
    }

}