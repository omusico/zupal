<?php

/**
 * Represents a single data element in Mongo.
 * NOTE: due to ArrayObject inheritance, array signatures usable.
 *
 * @author bingomanatee
 */
class Zupal_Model_Data_Mongo
        extends ArrayObject
        implements Zupal_Model_Data_IF {

    public $__id = NULL;

    public function __construct($array, $pContainer = NULL) {
        $this->container($pContainer);

        $this->_load_data($array);

        $this->_apply_schema();
    }

    protected function _check_id(&$array) {
        if (array_key_exists('_id', $array) && is_object($array['_id'])) {
            $this->__id = $array['_id'];
            $array['_id'] = self::deser_id($array['_id']);
        }
    }

    protected function _load_data(array $array) {
        $this->_check_id($array);
        if ($this->container()) {
            $defaults = $this->container()->schema()->defaults();
            if ($defaults) {
                $array = array_merge($defaults, $array);
            }
        }
        parent::__construct($array);
    }

    protected function _apply_schema() {
        /* @var $schema Zupal_Model_Schema_IF */
        $classes = array();
        foreach ($this->container()->schema() as $schema) {
            switch ($schema->type()) {
                case 'class':
                    $name = $schema->name();
                    $c = $schema['class'];
                    $d_value = empty($this[$name]) ? array() : (array) $this[$name];

                    /* @var $c_obj Zupal_Model_Schema_Field_ObjIF */
                    if ($schema->is_serial()) {
                        // note in this case we are not sure the series is made of objs or not.
                        foreach ($d_value as $i => $d_value_item) {
                            if (is_array($d_value_item)) {
                                $c_obj = new $c($this, $d_value_item, $name, $i);
                                $classes[] = $c_obj;
                                $d_value[$i] = $c_obj;
                            }
                        }
                        $this[$name] = $d_value;
                    } else {
                        $c_obj = new $c($this, $d_value, $name);
                        $classes[] = $c_obj;
                        $this[$name] = $c_obj;
                    }

                    break;
            }
        }

        foreach ($classes as $c) {
            $c->init();
        }
    }

    private $_status = Zupal_Model_Data_IF::STATUS_UNKNOWN;

    /**
     *
     * @param string $pSet
     * @return string
     */
    public function status($pSet) {
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
        error_log(__METHOD__ . ': result = ' . print_r($result, 1));
    }

    public function toArray() {
        $data = $this->getArrayCopy();

        /* @var $field Zupal_Model_Schema_IF */
        foreach ($this->container()->schema() as $field) {
            $name = $field->name();

            switch ($field->type()) {
                case 'class':
                    if (!empty($data[$name])) {
                        $obj = $data[$name];
                        if ($field->is_serial()) {
                            foreach ($obj as $k => $o) {
                                if (!method_exists($o, 'toArray')) {
                                    throw new Exception(__METHOD__ . ': object of type ' . get_class($o) . ' has no toArray method');
                                }
                                $data[$name][$k] = $o->toArray();
                            }
                        } else {
                            if (!method_exists($obj, 'toArray')) {
                                throw new Exception(__METHOD__ . ': object of type ' . get_class($obj) . ' has no toArray method');
                            }
                            $data[$name] = $obj->toArray();
                        }
                    }
                    break;

                case 'object':
                    $obj = $data[$name];
                    $data[$name] = (array) $obj;
                    break;
            }
        }

        return $data;
    }

}

