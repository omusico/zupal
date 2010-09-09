<?php

/**
 * Digests a sub_set of data;
 * intended to reflect an embedded document in MongoDb
 * but suitable to any nested structure.
 *
 * This structure is also useful for anything that is governed by a schema
 * but has no internal I/0 functions.
 *
 * The parent, name_in_parent, and root fields
 *
 * @author bingomanatee
 */
class Zupal_Model_Data_SubData
        extends Zupal_Model_ArrayObject
        implements Zupal_Model_Data_IF,
        Zupal_Model_Schema_Field_ClassIF {

    public function __construct(Zupal_Model_Data_IF $pData, $pValue, array $pOptions = array()) {
        $this->set_root_data($pData);

        $pSchema = array();

        foreach ($pOptions as $key => $value) {
            $key = strtolower(trim($key));

            switch ($key) {
                case 'schema':
                    $this->set_schema($value);
                    break;

                case 'parent':
                    $this->set_parent($value);
                    break;

                case 'data':
                case 'root_data':
                    $this->set_root_data($value);
                    break;

                case 'index':
                case 'index_in_parent':
                    $this->set_index_in_parent($value);
                    break;

                case 'name':
                case 'name_in_parent':
                    $this->set_name_in_parent($value);
                    break;
            }
        }

        $this->_load_data($pValue);

        $this->_apply_schema();
    }

    protected function _load_data($array) {
        error_log(__METHOD__ . ': loading data for ' . get_class($this));
        
        if (($array instanceof DomNode) || ($array instanceof DOMNodeList)) {
            $schema = $this->get_schema();
            $array = Zupal_Model_Data_XMLdigester::digest($array, $schema);
        }

        if ($this->get_schema()) {
            $defaults = $this->get_schema()->defaults();
            if ($defaults) {
                $array = array_merge($defaults, $array);
            }
        }

        $this->exchangeArray($array);
    }

    public function load($array) {
        $this->_load_data($array);
    }

    protected function _apply_schema() {
        if (!$this->get_schema()) {
            error_log(get_class() . ': no schema.');
            return;
        }
        /* @var $field Zupal_Model_Schema_IF */
        $classes = array();
        foreach ($this->get_schema() as $field) {
            $name = $field->name();
       //     if ($name == 'headers'){
         //       error_log('headers found');
           // }
            if ($this[$field->name()] && method_exists($field, 'post_load')) {
                $field->post_load($this, $classes);
            }

            if ($field->is_serial()) {
                $av = $this->$name;
                if (!is_array($av)){
                    $av = array();
                }
                $this->$name = new Zupal_Model_Schema_Field_Serial($av);
            //  if ($name == 'headers')  error_log(var_dump($this->$name, 1));
            //    $c = count($this->$name);
            }
        }

        foreach ($classes as $c) {
            $c->init();
        }
    }

    /* @var $_key_field Zupal_Model_Schema_Field_IF */

    private $_key_field;
    private $_parent;
    private $_root_data;
    private $_name_in_parent;
    private $_index_in_parent;

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

    /**
     * The model for which this is a field.
     * May or may not be equal to root_data
     * @return Zupal_Model_Data_IF
     */
    public function get_parent() {
        return $this->_parent;
    }

    public function set_parent($_parent) {
        $this->_parent = $_parent;
    }

    /**
     *
     * @return <type>
     */
    public function get_root_data() {
        return $this->_root_data;
    }

    public function set_root_data($_root_data) {
        $this->_root_data = $_root_data;
    }

    public function get_name_in_parent() {
        return $this->_name_in_parent;
    }

    public function set_name_in_parent($_name_in_parent) {
        $this->_name_in_parent = $_name_in_parent;
    }

    private $_schema;

    /**
     *
     * @return Zupal_Model_Schema_IF
     */
    public function get_schema() {
        return $this->_schema;
    }

    /**
     *
     * @param Zupal_Model_Schema_IF $_schema
     */
    public function set_schema($_schema) {
        $this->_schema = $_schema;
    }

    public function get_index_in_parent() {
        return $this->_index_in_parent;
    }

    public function set_index_in_parent($_index_in_parent) {
        $this->_index_in_parent = $_index_in_parent;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@ DATA IF @@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function save() {
// managed by parent object
    }

    public function insert() {
        // managed by parent object
    }

    public function delete() {
// not relevant to a subdoc.
    }

    public function set_key($pValue) {
        if ($key_field = $this->_key_field()) {
            $this[$key_field->name()] = $pValue;
        }
    }

    public function status($pSet = NULL) {
        // not relevant for subdocs;
    }

    public function toArray() {
        return $this->hydrate();
    }

    public function hydrate() {
        return Zupal_Model_Data_Hydrator::hydrate($this->getArrayCopy(), $this->get_schema());
    }

    /**
     * executes any commands that require the data tree be fully loaded.
     */
    public function init() {
        
    }

    /* @@@@@@@@@@@@@@@ TO XML @@@@@@@@@@@@@@@@@@@@@@@@ */

    function to_xml(DomDocument $dom, $root = NULL) {
        if (!$root) {
            $root = $dom->createElement('data');
            $dom->appendChild($root);
        } elseif (is_string($root)){
            $root = $dom->createElement($root);
            $dom->appendChild($root);
        }

        /* @var $schema Zupal_Model_Schema_IF */
        if ($schema = $this->get_schema()) {
            $schema->as_xml($this, $dom, $root);
        } else {
            Zupal_Model_Schema_Field_Xml::array_to_node($this->getArrayCopy(), $dom, $root);
        }

        return $root;
    }
}

