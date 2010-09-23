<?php

/**
 * Represents a single data element in Mongo.
 * NOTE: due to ArrayObject inheritance, array signatures usable.
 *
 * @author bingomanatee
 */
class Zupal_Model_Data_Mongo
        extends Zupal_Model_ArrayObject
        implements Zupal_Model_Data_IF {

    public $__id = NULL;

    public function __construct($array, $pContainer = NULL) {
        $this->container($pContainer);

        $this->_load_data($array);

        $this->_apply_schema();
    }

    /**
     * may not be necessary as mongoIDs are being stored as passed. 
     * @param array $array
     */
    protected function _check_id(&$array) {
        if (array_key_exists('_id', $array) && is_object($array['_id'])) {
            $this->__id = $array['_id'];
            $array['_id'] = self::deser_id($array['_id']);
        }
    }

    protected function _load_data($array) {
        if ($array instanceof DomNode) {
            $array = Zupal_Model_Data_XMLdigester::digest($xml, $this->container()->schema());
        }
        $this->_check_id($array);

        if ($this->container() && $this->container()->schema()) {
            $defaults = $this->container()->schema()->defaults();
            if ($defaults) {
                $array = array_merge($defaults, $array);
            }
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
    protected function _apply_schema() {
        /* @var $field Zupal_Model_Schema_Field_IF */
        $classes = array();
        if ($schema = $this->container()->schema()) {
            foreach ($schema as $field) {
                if (is_object($field)) {
                    $name = $field->name();

                    if (method_exists($field, 'post_load')) {
                        $field->post_load($this, $classes);
                    }

                    if ($field->is_serial()) {
                        $this->$name = new Zupal_Model_Schema_Field_Serial((array) $this->$name);
                    }
                } else {
                    $e = $field;
                }
            }

            foreach ($classes as $c) {
                $c->init();
            }
        }
    }

    private $_status = Zupal_Model_Data_IF::STATUS_UNKNOWN;

    /**
     *
     * @param string $pSet
     * @return string
     */
    public function status($pSet = NULL) {
        if ($pSet) {
            $this->_status = $pSet;
        }
        return $this->_status;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@ CONTAINER @@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_container = NULL;

    /**
     * note -- to prevent circular logic, setting the container does not
     * actually add the data object to the container object.
     * it just registers the container inside the data object.
     *
     * @param Zupal_Model_Container_IF $pContainer
     * @return Zupal_Model_Container_IF
     */
    public function container(Zupal_Model_Container_IF $pContainer = NULL) {
        if ($pContainer) {
            $this->_container = $pContainer;
        }
        return $this->_container;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ KEY @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Returns the value of the key field.
     * if $pThrow, throws an exception if absent.
     *
     * @param boolean $pThrow
     * @return scalar
     */
    public function key($pThrow = TRUE) {
        if (array_key_exists('_id', $this)) {
            return $this['_id'];
        } elseif ($pThrow) {
            throw new Exception(__METHOD__ . ': no ID in ' . print_r($this, 1));
        } else {
            return NULL;
        }
    }

    /**
     * Some data types auto-key themselves. For those that do not
     * use this method to set key manually
     * @param string $pValue
     */
    public function set_key($pValue) {
        //  if (is_object($pValue)) {
        //      $pValue = self::deser_id($pValue);
        //  }
        $this['_id'] = $pValue;
    }

    public static function deser_id($id_obj) {
        $id_string = serialize($id_obj);
        $id_array = explode('{', $id_string);
        $id = rtrim($id_array[1], '}');
        return $id;
    }

    private static $_id_def;

    /**
     * initializes a database removal /filesystem erase of this record.
     * The object may still exist but status should be set to deleted.
     */
    public function delete() {
        $this->container()->delete_data($this);
    }

    public function save() {
        $result = $this->container()->save_data($this);
    //    error_log(__METHOD__ . ': result = ' . print_r($result, 1));
    }

    public function insert() {
        $result = $this->container()->insert_data($this);
     //   error_log(__METHOD__ . ': result = ' . print_r($result, 1));
    }

    /**
     *
     * @return array
     */
    public function toArray() {
        $data = $this->getArrayCopy();

        return Zupal_Model_Data_Hydrator::hydrate($data, $this->container()->schema());
    }

    /* @@@@@@@@@@@@@@@ TO XML @@@@@@@@@@@@@@@@@@@@@@@@ */

    function to_xml(DomDocument $dom, $root = NULL) {
        if (!$root) {
            $root = $dom->createElement('data');
            $dom->appendChild($root);
        } elseif (is_string($root)) {
            $root = $dom->createElement($root);
            $dom->appendChild($root);
        }

        /* @var $schema Zupal_Model_Schema_IF */
        if ($schema = $this->container()->schema()) {
            $schema->as_xml($this, $dom, $root);
        } else {
            Zupal_Model_Schema_Field_Xml::array_to_node($this->getArrayCopy(), $dom, $root);
        }

        return $root;
    }

}

