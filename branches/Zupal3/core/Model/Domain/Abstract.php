<?php

/**
 * The Domain exist in order to interleave events inside the data model.
 * note: domains have two sets of data: records, which exist in a datastore,
 * and metadata, which exist for the lifespan of the request.
 *
 * @author bingomanatee
 */
abstract class Zupal_Model_Domain_Abstract
        implements Zupal_Model_Data_IF,
        ArrayAccess,
        Zupal_Model_Container_IF,
        Zupal_Event_HandlerIF {
    const LOAD_NEW = 'new';
    /**
     *
     * @var Zupal_Model_Data_IF
     */
    protected $_record;
    public $metadata = array();

    public function __construct($pKey = NULL) {
        if (empty($pKey)) {
            $this->load(self::LOAD_NEW);
        } else {
            $this->load($pKey);
        }
    }

    /* @@@@@@@@@@@@@@@@@ DATA_IF METHODS @@@@@@@@@@@@@ */

    /**
     * handler for construct.
     * NOTE: do NOT load with another domain ! gets wierd.
     * @global Zupal_Event_Manager $event_manager
     * @param variant $pKey
     */
    public function load($pKey) {
        /* @var $event_manager Zupal_Event_Manager */

        if (!empty($pKey)) {
            if ($pKey == self::LOAD_NEW) {
                $this->_record = $this->container()->new_data(array());
            } elseif ($pKey instanceof Zupal_Model_Data_IF) {
                $this->_record = $pKey;
            } elseif ($pKey instanceof Zupal_Model_Query_IF) {
                $this->_record = $this->container()->find_one($pKey);
            } elseif (is_array($pKey) || $pKey instanceof DomDocument) {
                $this->_record = $this->container()->new_data($pKey);
            } else {
                $this->_record = $this->container()->get($pKey);
            }
            //  Zupal_Event_Manager::event('load', array('subject' => $this));
        } else {
            $this->_record = $this->container()->new_data(array());
        }
    }

    /**
     *
     * @return Zupal_Model_Data_IF
     */
    public function record() {
        return $this->_record;
    }

    public function delete() {
        $this->_record->delete();
        Zupal_Event_Manager::event('deleted', array('subject' => $this));
    }

    public function delete_data(Zupal_Model_Data_IF $pData) {
        if ($pData instanceof Zupal_Model_Domain_Abstract) {
            $pData = $pData->record();
        }
        $this->container()->delete_data($pData);
    }

    public function get_field($name, $scope = 'r') {
        switch ($scope) {
            case 'm':
                return empty($this->metadata[$name]) ? NULL : $this->metadata[$name];
                break;

            case 'r':
                return empty($this->_record[$name]) ? NULL : $this->_record[$name];
                break;

            default: return $this->_get($name);
        }
    }

    public function set_field($name, $value, $scope='r') {
        switch ($scope) {
            case 'm':
                $this->metadata[$name] = $value;
                break;

            case 'r':
                $this->_record[$name] = $value;
                break;

            default: $this->_set($name, $value);
        }
    }

    public function add_field($name, $value, $index = NULL, $scope='r') {
        if (!$this->schema()->get_field($name)->is_serial()) {
            throw new Exception('attempted to append ' . print_r($value, 1) . ' to non-serial field ' . $name);
        }

        $array = (array) $this->get_field($name);

        if ($index) {
            $array[$index] = $value;
        } else {
            $array[] = $value;
        }

        $this->set_field($name, $array);
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_record)) {
            return $this->_record[$name];
        } elseif (array_key_exists($name, $this->metadata)) {
            return $this->metadata[$name];
        } else {
            return NULL; // throw new Exception(__METHOD__ . ": unrecorded field $name requested");
        }
    }

    public function __set($name, $value) {
        if (array_key_exists($name, $this->_record)) {
            $this->_record[$name] = $value;
        } else {
            $schema = $this->schema();
            if (array_key_exists($name, $schema)) {
                $this->_record[$name] = $value;
            } else {
                $this->metadata[$name] = $value;
            }
        }
    }

    public function toArray($pIncludeMeta = FALSE) {
        return $pIncludeMeta ?
                array_merge($this->metadata, $this->_record->toArray()) : $this->_record->toArray();
    }

    public function status($pSet) {
        return $this->_record->status($pSet);
    }

    public function key($pThrow = TRUE) {
        return $this->_record->key($pThrow);
    }

    public function set_key($pValue) {
        throw new Exception(__METHOD__ . ": not implemented");
    }

    public function insure_defaults() {

        foreach ($this->schema()->defaults() as $field => $value) {
            if (!isset($this->_record[$field])) {
                $this->_record[$field] = $value;
            }
        }
    }

    public function save() {
        /* @var $event_manager Zupal_Event_Manager */
        $key = $this->key();
        if ($key && $key instanceof MongoId){
            $key = $key->__toString();
        }
        $this->container()->save_data($this->_record);
        Zupal_Event_Manager::event($key ? 'update' : 'insert', array('subject' => $this));
    }

    public function insert() {
        $this->container()->insert_data($this->_record);
        Zupal_Event_Manager::event('insert', array('subject' => $this));
    }

    /* @@@@@@@@@@@@@@@@ CONATINER_IF METHODS @@@@@@@@@@ */

    /**
     * @return Zupal_Model_Container_IF
     */
    protected abstract function container();

    public function get($pKey) {
        return $this->new_data($pKey);
    }

    public function add($pData) {
        $d = $this->new_data($pData);
        $d->save();
        return $d;
    }

    public function find_all($limit = NULL, $sort = NULL) {
        $out = array();

        foreach ($this->container()->find_all($limit, $sort) as $record) {
            $out[] = $this->new_data($record);
        }

        return $out;
    }

    public function find($pQuery = NULL, $limit = NULL, $sort = NULL) {
        $out = array();
        if (empty($pQuery)) {
            $pQuery = NULL;
        }
        foreach ($this->container()->find($pQuery, $limit, $sort) as $record) {
            $out[] = $this->new_data($record);
        }

        return $out;
    }

    public function find_one($pQuery = NULL, $sort = NULL) {
        $record = $this->container()->find_one($pQuery, $sort);
        if ($record) {
            return $this->new_data($record);
        } else {
            return NULL;
        }
    }

    public function has($pWhat) {
        return $this->container()->has($pWhat);
    }

    public function find_and_delete($pWhat) {
        if ($pWhat instanceof Zupal_Model_Query_IF) {
            $pQuery = $pWhat;
        } else {
            $pQuery = Zupal_Model_Query_Mongo::to_query($pWhat);
        }

        foreach ($this->find($pQuery) as $domain) {
            $domain->delete();
        }
    }

    /**
     * This method should not be called externally - use save() or insert() from items.
     * @param Zupal_Model_Data_IF $pData
     */
    public function save_data(Zupal_Model_Data_IF $pData) {
        throw new Exception(__METHOD__ . ': not relevant for Domains');
    }

    /**
     * This method should not be called externally - use save() or insert() from items. 
     * @param Zupal_Model_Data_IF $pData
     */
    public function insert_data(Zupal_Model_Data_IF $pData) {
        throw new Exception(__METHOD__ . ': not relevant for Domains');
    }

    /**
     * @return Zupal_Model_Schema_IF
     */
    public function schema() {
        return $this->container()->schema();
    }

    /* @@@@@@@@@@@@@@@@@@@@ handler IF @@@@@@@@@@@@@@@@@@@ */

    public function respond(Zupal_Event_EventIF $pEvent) {
        // does no action -- override for custom responsiveness
    }

    /* @@@@@@@@@@@@@@@@@@@@ ArrayAccess @@@@@@@@@@@@@@@@@@ */

    public function offsetExists($offset) {
        if ($record = $this->_record) {
            if ((is_array($record)) || $record instanceof ArrayAccess) {
                return array_key_exists($offset, $record);
            } else {
                throw new Exception(__METHOD__ . ': cannot execute on ' . print_r($record, 1));
            }
        } else {
            throw new Exception(__METHOD__ . ': no record to test');
        }
    }

    public function offsetGet($offset) {
        if ($record = $this->_record) {
            if ((is_array($record)) || $record instanceof ArrayAccess) {
                return $record[$offset];
            } else {
                throw new Exception(__METHOD__ . ': cannot execute on ' . print_r($record, 1));
            }
        } else {
            throw new Exception(__METHOD__ . ': no record to test');
        }
    }

    public function offsetSet($offset, $value) {
        if ($record = $this->_record) {
            if ((is_array($record)) || $record instanceof ArrayAccess) {
                return $record[$offset] = $value;
            } else {
                throw new Exception(__METHOD__ . ': cannot execute on ' . print_r($record, 1));
            }
        } else {
            throw new Exception(__METHOD__ . ': no record to test');
        }
    }

    public function offsetUnset($offset) {
        if ($record = $this->_record) {
            if ((is_array($record)) || $record instanceof ArrayAccess) {
                unset($record[$offset]);
            } else {
                throw new Exception(__METHOD__ . ': cannot execute on ' . print_r($record, 1));
            }
        } else {
            throw new Exception(__METHOD__ . ': no record to test');
        }
    }

    /**
     * adds or updates an object in an array of objects.
     * Preaumes content is Zupal_Model_Schema_Field_ClassIF elements. 
     * @TODO: apply a collection to serial fields. 
     * 
     * @param string $pField
     * @param array | Zupal_Model_Schema_Field_ClassIF $pData
     * @param string $pClass
     * @param string $pKey
     * @return Zupal_Model_Schema_Field_ClassIF 
     */
    public function add_field_serial_indexed($pField, $pData, $pClass, $pKey = 'id') {

        if ($pData instanceof $pClass) {
            $object = $pData;
        } elseif (is_array($pData)) {
            $object = new $pClass($this->record(), $pData);
        } else {
            throw new Exception(__METHOD__ . ': cannot add non ' . $pClass . ' ' . print_r($pData, 1));
        }

        $old_data = $this->get_field($pField);

        $mongo_key = FALSE;
        $set = FALSE;

        $object_key = $object->$pKey;
        if ($object_key instanceof MongoId) {
            $mongo_key = TRUE;
            $object_key = $object_key->__toString();
        }

        if ($object_key) {
            foreach ($old_data as $k => $v) {
                if (!($v instanceof $pClass)) {
                    throw new Exception(__METHOD__ . ": non $pClass found in field $pField");
                }

                $v_key = $v->$pKey;

                if ($mongo_key && $v_key instanceof MongoId) {
                    $v_key = $v_key->__toString();
                }

                if ($v_key == $object_key) {
                    $old_data[$k] = $object;
                    $set = TRUE;
                    break;
                }
            }
        }

        if (!$set) {
            $old_data[] = $object;
        }

        $this->set_field($pField, $old_data);

        return $object;
    }

    /**
     * Deletes all members of a serial field with a given key/value identity. 
     * May delete more than one member. 
     *
     * @param string $pField
     * @param scalar | MongoId $pKey
     * @param string $pKey_field 
     */
    public function delete_field_serial_indexed($pField, $pKey, $pKey_field) {
        $serial_objects = (array) $this->get_field($pField);
        $new_serial_objects = array();
        if ($pKey instanceof MongoId) {
            $key_value = (string) $pID;
            $mongo_key = TRUE;
        } else {
            $mongo_key = FALSE;
            $key_value = $pKey;
        }

        foreach ($serial_objects as $k => $serial_object) {
            $serial_id = $serial_object->$pKey_field;
            if ($mongo_key){
                $serial_id = $serial_id->__toString();
            }
            if ($serial_id != $key_value) {
                $new_serial_objects[$k] = $serial_object;
            }
        }
        $this->set_field($pField, $new_serial_objects);
    }

    public function get_field_serial_indexed($pField, $pKey, $pKey_field) {
        if ($pKey instanceof MongoId) {
            $key_value = (string) $pKey;
            $is_mongo_key = TRUE;
        } else {
            $key_value = $pKey;
            $is_mongo_key = FALSE;
        }

        foreach ((array) $this->get_field($pField) as $cond) {

            $c_key = $cond->$pKey_field;
            if ($is_mongo_key) {
                $c_key = $c_key->__toString();
            }

            if ($c_key == $key_value) {
                return $cond;
            }
        }

        return NULL;
    }

}

