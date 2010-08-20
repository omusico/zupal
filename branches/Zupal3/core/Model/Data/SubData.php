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
        extends ArrayObject
        implements Zupal_Model_Data_IF {

    function __construct($pValues, array $pOptions = array()) {
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

        $this->_load_data($pValues);

        $this->_apply_schema();
    }

    protected function _load_data($array) {

        if ($array instanceof DomNode) {
            $array = $this->_digest_xml($array);
        }

        $defaults = $this->get_schema()->defaults();
        if ($defaults) {
            $array = array_merge($defaults, $array);
        }

        parent::__construct($array);
    }

    /**
     *
     * @param DomNode $xml
     *
     * Note - this just flattens out the content; the only
     * complex action is that xml nodes are passed to newly
     * created classes in order to preserve transport of the domnode.
     * 
     * @return array
     */
    protected function _digest_xml(DomNode $xml) {
        $out = array();
        foreach ($xml->childNodes as $node) {
            $name = $node->localName;

            /* @var $field Zupal_Model_Schema_Field_IF */
            if ($field = $this->get_schema()->get_field($name)) {
                if ($field->type() == 'class') {
                    $options = array('parent' => $this,
                        'data' => $this->get_root_data());
                    if ($field['schema']){
                        $options['schema'] = $field['schema'];
                    }
                    $class = $field['class'];
                    $value = new $class($node, $options);
                } else {
                    $value = $node->textContent;
                }
                if ($field->is_serial()) {
                    if (!array_key_exists($name, $out)) {
                        $out[$name] = array($value);
                    } else {
                        $out[$name][] = $value;
                    }
                } else {
                    $out[$name] = $value;
                }
            } else {
                $value = $node->textContent;
                $out[$name] = $value;
            }
        }

        return $out;
    }

    protected function _apply_schema() {
        if (!$this->get_schema()) {
            return;
        }
        /* @var $field Zupal_Model_Schema_IF */
        $classes = array();
        foreach ($this->get_schema() as $field) {
            if ($this[$field->name()] && method_exists('post_load', $field)) {
                $field->post_load($this, $classes);
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

    public function offsetSet($index, $newval) {
        $s = $this->get_schema();
        if ($s->offsetExists($index)) {
            /* @var $field Zupal_Model_Schema_Field_IF */
            $field = $s[$index];
            if ($field->is_serial() && !is_array($newval)) {
                $newval = array($newval);
            }
        }
        return parent::offsetSet($index, $newval);
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

    public function delete() {
// not relevant to a subdoc.
    }

    public function set_key($pValue) {
        if ($key_field = $this->_key_field()) {
            $this[$key_field->name()] = $pValue;
        }
    }

    public function status($pSet) {
        // not relevant for subdocs;
    }

    public function toArray() {
        $out = $this->getArrayCopy();

        foreach ($this->get_schema() as $field) {
            $name = $field->name();
            if (!empty($out[$name])) {
                $out[$name] = $field->clean_value($this[$name]);
            }
        }
        return $out;
    }

    /**
     * executes any commands that require the data tree be fully loaded.
     */
    public function init() {
        
    }

}

