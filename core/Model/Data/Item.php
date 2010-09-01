<?php

class Zupal_Model_Data_Item
        extends Zupal_Model_ArrayObject
        implements Zupal_Model_Data_IF {

    public function __construct($pValue, $pOptions = array()) {

        foreach ($pOptions as $key => $value) {
            $key = strtolower(trim($key));

            switch ($key) {
                case 'container':
                    $this->set_container($value);
                    break;
                
                case 'schema':
                    $this->set_schema($value);
                    break;
            }
        }

        $this->_load_data($pValue);

        if ($this->get_schema()) {
            $this->_apply_schema();
        }
    }

    /**
     *
     * @var Zupal_Model_Schema_IF
     */
    private $_schema;

    /**
     *
     * @return Zupal_Model_Schema_IF
     */
    public function get_schema() {
        return $this->_schema;
    }

    public function set_schema(Zupal_Model_Schema_IF $_schema) {
        $this->_schema = $_schema;
    }

    /**
     *
     * @var Zupal_Model_Container_IF
     */
    private $_container;

    /**
     *
     * @return Zupal_Model_Container_IF
     */
    public function get_container() {
        return $this->_container;
    }

    public function set_container(Zupal_Model_Container_IF $_container) {
        $this->_container = $_container;
    }

    protected function _load_data($array) {
        if (($array instanceof DomNode) || ($array instanceof DOMNodeList)) {
            $array = Zupal_Model_Data_XMLdigester::digest($xml, $this->get_schema());
        }

        if ($this->get_schema()) {
            $array = array_merge($this->get_schema()->defaults(), $array);
        }

        parent::__construct($array);
    }

    protected function _apply_schema() {
        if (!($schema = $this->get_schema())) {
            return;
        }
        $classes = array();
        /* @var $field Zupal_Model_Schema_IF */
        foreach ($schema as $field) {
            if ($this[$field->name()] && method_exists($field, 'post_load')) {
                $field->post_load($this, $classes);
            }
        }

        foreach ($classes as $c) {
            $c->init();
        }
    }

    /* @@@@@@@@@@@@@@@@@@ DATA_IF METHODS @@@@@@@@@@@@@@@@@@@@@ */

    public function delete() {
        if ($this->get_container()) {
            return $this->get_container()->delete_data($this);
        }
    }

    /* @var $_key_field Zupal_Model_Schema_Field_IF */

    private $_key_field;

    /**
     *
     * @return Zupal_Model_Schema_Field_IF
     */
    protected function _key_field() {
        if (is_null($this->_key_field)) {

            $this->_key_field = FALSE;

            foreach ($this->get_schema() as $field) {
                if ($field['key']) {
                    $this->_key_field = $field;
                    break;
                }
            }
        }
        return $this->_key_field;
    }

    public function key($pThrow = TRUE) {
        /* @var $key Zupal_Model_Schema_Field_IF */

        if ($key_field = $this->key_field()) {
            return $this[$key_field->name()];
        } else {

        }
    }

    public function save() {
        if ($this->get_container()) {
            $this->get_container()->save_data($this);
        } else {
            throw new Exception(__METHOD__ . ': called from a containerless item');
        }
    }

    public function insert() {
        if ($this->get_container()) {
            $this->get_container()->insert_data($this);
        } else {
            throw new Exception(__METHOD__ . ': called from a containerless item');
        }
    }

    public function set_key($pValue) {
        if ($key_field = $this->_key_field()) {
            $this[$key_field->name()] = $pValue;
        }
    }

    private $_status;
    public function status($pSet = NULL) {
        if (!is_null($pSet)){
            $this->_status = $pSet;
        }
        return $this->_status;
    }

    public function toArray() {
        return Zupal_Model_Data_Hydrator::hydrate($this->getArrayCopy(), $this->get_schema());
    }

}