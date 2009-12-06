<?

abstract class Zupal_Switch_Handler_Abstract
{

    const RESPOND_TO_EVERYTHING = 'I Respond to everything';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ responds_to @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_responds_to = null;
    /**
     * @return class;
     */

    public function get_responds_to() { return $this->_responds_to; }

    public function set_responds_to($pValue) { $this->_responds_to = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ responds_to @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param scalar $pValue
     * @return boolean
     */
    public function responds_to ($pValue) {
        $rt = $this->get_responds_to();
        if (is_null($rt)):
            return FALSE;
        endif;

        if (is_array($rt)):
            return in_array($pValue, $rt);
        else:
            return $pValue == $rt;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ always_handle @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function always_handle () {
        $this->set_responds_to(self::RESPOND_TO_EVERYTHING);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ terminate @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_terminate = FALSE;
    /**
     * trigger in the execute method to stop switch execution.
     * NOTE: somewhat redundant with the result of the handle method
     * but it does give a secondary way to ENSURE that the handler terminates
     * upon execution -- useful for the default handler.
     *
     * @return boolean;
     */

    public function terminate() { return $this->_terminate; }

    public function set_terminate($pValue) { $this->_terminate = $pValue ? TRUE : FALSE; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ execute @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * This is the equivalent of the "body" of the case.
     * It returns true to terminate waterfalling.
     * @param <type> $pParameters = NULL
     * @return boolean
     */
    public abstract function handle ($pValue, $pParameters = NULL);


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ manager @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_manager = null;
    /**
     * @return class;
     */

    public function get_manager() { return $this->_manager; }

    public function set_manager($pValue) { $this->_manager = $pValue; }


}