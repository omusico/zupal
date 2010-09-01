<?php

class Zupal_Model_Data_JSON
extends ArrayObject 
implements Zupal_Model_Data_IF {

    protected $_container;

    /**
     *
     * @var Zupal_Model_Schema_Item
     */
    private $_schema;
    public function __construct(array $pData = NULL, $pContainer = NULL) {
        if ($pContainer) {
            if ($this->key(FALSE)) {
                $pContainer->add($this); // calls $this->container($pContainer) indirectly.
            } else {
                $this->container($pContainer);
            }
        }
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@ STATUS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_status = Zupal_Model_Data_IF::STATUS_UNKNOWN;

    public function status($pSet = NULL) {
        if ($pSet) {
            $this->_status = $pSet;
        }
        return $this->_status;
    }

    /**
     *
     * @return Zupal_Model_Schema_Item
     */
    public function schema() {
        return $this->container()->schema();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@ CONTAINER @@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * note -- to prevent circular logic, setting the container does not
     * actually add the data object to the container's index.
     * That must be done seperately.
     * @param Zupal_Model_Container_Abstract $pContainer
     * @return Zupal_Model_Container_Abstract
     */
    public function container(Zupal_Model_Container_Abstract $pContainer = NULL) {
        if ($pContainer) {
            $this->_container = $pContainer;
        }
        return $this->_container;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ KEY @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * @var scalar
     */
    protected $_key = NULL;

    /**
     * Returns the key. Note -- key is cached in private variable --
     * Assumes key never changes.
     * if unset before retrieved, throws error UNLESS parameter == false.
     * @param boolean $pThrow
     * @return scalar
     */
    public function key($pThrow = TRUE) {
        if (is_null($this->_key)) {
            $kf_obj = $this->key_field(TRUE);
            $kf = $kf_obj->name();

            if(!$kf) {
                if ($pThrow) {
                    throw new Exception(__METHOD__ . ': no key in schema ');
                } else {
                    return NULL;
                }
            } elseif (array_key_exists($kf, $this) && $this[$kf]) {
                $this->_key = $this[$kf];
            } elseif ($kf_obj['generate']) {
                $this->set_key($this->gen_key());
                $this->_key = $this[$kf];
            } elseif ($pThrow) {
                $this->_container = NULL; // cleaner echo
                throw new Exception(__METHOD__ . ': missing key field '  . print_r($this, 1));
            } else {
                return NULL;
            }
        }
        return $this->_key;
    }

    protected function gen_key() {
        return md5(microtime() . '#' . rand());
    }

    public function set_key($pValue) {
        $this->_key = $pValue;
        $this[$this->schema()->key_field()] = $pValue;
        if ($this->container()) {
            $this->container()->add($this);
        }
    }

    /**
     * since there is no memory cache, $pUncollect is irellevant.
     * 
     * @param boolean $pUncollect
     */
    public function delete() {
        $this->container()->delete($this);
        $this->status(Zupal_Model_Data_IF::STATUS_DELETED);
    }

    public function save(){
        if ($this->status() == Zupal_Model_Data_IF::STATUS_DELETED){
           throw new Exception(__METHOD__ . ": attempt to save deleted record");
        }
        $this->container()->save($this);
    }

    public function toArray() {
        return $this->getArrayCopy();
    }

    public function iterator(){
        return new Zupal_Model_Data_JSONIterator($this);
    }

    public function offsetSet($index, $newval){
        if (array_key_exists($index, $this->schema())){
            $field = $this->schema()->offsetGet($index);
            $newval = $field->hydrate($newval);
        }
        parent::offsetSet($index, $newval);
    }
}