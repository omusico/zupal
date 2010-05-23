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
        if (array_key_exists('_id', $array) && is_object($array['_id'])){
            $this->__id = $array['_id'];
            $array['_id'] = self::deser_id($array['_id']);
        }

        if ($container = $this->container($pContainer)) {
            $defaults = $container->schema()->defaults();
            if ($defaults) {
                $array = array_merge($defaults, $array);
            }
        }
        parent::__construct($array);
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
     * @param Zupal_Model_Container_Abstract $pContainer
     * @return Zupal_Model_Container_Abstract
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
        if (is_object($pValue)) {
            $pValue = self::deser_id($pValue);
        }
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
        return $this->getArrayCopy();
    }
}


