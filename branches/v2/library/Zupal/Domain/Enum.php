<?

class Zupal_Domain_Enum
extends ArrayObject
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Zupal_Domain $pDomain,
 * @param string $pField
 * @return <type>
 */
    public function __construct ($pDomain, $pField) {        
        $this->set_domain($pDomain);
        $this->set_field($pField);
        $this->base = $pDomain->$pField;
        $data = split(',', $this->base);
        if ($data === FALSE) $data = array();
        parent::__construct($data);
    }
    private $base;

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ domain @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_domain = null;
    /**
     * @return class;
     */
    
    public function get_domain() { return $this->_domain; }
    
    public function set_domain($pValue) { $this->_domain = $pValue; }
    
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ field @@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_field = null;
    /**
     * @return class;
     */
    
    public function get_field() { return $this->_field; }
    
    public function set_field($pValue) { $this->_field = $pValue; }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offsetSet @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pKey, $pValue
     * @return <type>
     */
    public function offsetSet ($index, $newval) {
        parent::offsetSet($index, $newval);
        $this->refresh();
    }

    public function offsetUnset ($index) {
        parent::offsetUnset($index);
        $this->refresh();
    }

    private function refresh(){
        $values = array_unique((array) $this);
        $this->get_domain()->__set($this->get_field(), join($values, ','));
    }

}