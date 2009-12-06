<?

class Zupal_Switch_Handler
extends Zupal_Switch_Handler_abstract
{
    
    public function __construct($pCallback) {
        $this->set_callback($pCallback);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ callback @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_callback = null;
    /**
     * @return class;
     */
    
    public function get_callback() { return $this->_callback; }
    
    public function set_callback($pValue) { $this->_callback = $pValue; }
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ handle @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return boolean;
     */
    public function handle ($pValue, $pParameters = NULL) {
        $fga = func_get_args();
        return call_user_func_array($this->get_callback(), $fga);
    }
    
}
