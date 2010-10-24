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
        Zend_Paginator_Adapter_Interface,
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

        $this->_post_load();
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
    
    /* @@@@@@@@@@@@@@@@ EVENT HOOK: POST LOAD @@@@@@@@@@@@@@@@@@@@@ */

    protected function _post_load(){
        // overload for custom finishing actions
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
    //    Zupal_Event_Manager::event('deleted', array('subject' => $this));
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
        return $this->_record[$name];
    }

    public function __set($name, $value) {
        $this->_record[$name] = $value;
    }

    public function toArray($pIncludeMeta = FALSE) {
        $a = $this->_record->toArray();
        return $pIncludeMeta ?
                array_merge($this->metadata, $a) : $a;
    }

    public function status($pSet = NULL) {
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
        $this->container()->save_data($this->_record);
//        Zupal_Event_Manager::event($key ? 'update' : 'insert', array('subject' => $this));
    }

    public function copy(){
        $data = $this->_record->copy();
        return $this->new_data($data);
    }

    public function insert() {
        $this->container()->insert_data($this->_record);
      //  Zupal_Event_Manager::event('insert', array('subject' => $this));
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
            $pQuery = array();
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
     * This is a "fast track" for multi-record saving.
     * @param Zupal_Model_Data_IF $pData
     */
    public function save_data(Zupal_Model_Data_IF $pData) {
        $this->container()->save_data($pData);
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
            if ($mongo_key) {
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
        if ($schema = $this->schema()) {
            $schema->as_xml($this, $dom, $root);
        } else {
            Zupal_Model_Schema_Field_Xml::array_to_node($this->getArrayCopy(), $dom, $root);
        }

        return $root;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@ PAGINATOR INTERFACE @@@@@@@@@@@@@@@@@@@@@@@ */

    public $pagination_sort = '_id';

    public function count() {
        return $this->container()->get_count();
    }
    
    public function get_count($pQuery = NULL){
        return $this->container()->get_count($pQuery);
    }

    public function getItems($offset, $itemCountPerPage) {
        $query = array('_skip' => $offset);
        return $this->find($query, $itemCountPerPage, $this->pagination_sort);
    }

/* @@@@@@@@@@@@@@@@@@@@ CALL MANAGERS @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Call managers are plug-in functionality that extend a class.
     * They allow for compositional functionality.
     *
     * Note - to reduce unnecessary generation of objects
     * managers can be stored by class name and only instantiated
     * when needed. However allowances are made to accept
     * a pre-instantiated object when further handling is needed.
     *
     * Note 2 - no allowances are made for conflicting method names.
     * Also due to the __call treatment, methods of managers are
     * only called in the absence of a true method in the target. 
     */

    /**
     *
     * @var array
     */

    protected $_call_managers = array();

    protected function _add_call_manager($pManager_name, $pParams = NULL){
        if (is_string($pManager_name)){
            if ($pParams && is_array($pParams)){
                $manager = $pParams;
                array_unshift($manager, $pManager_name);
            } else {
                $manager = array($pManager_name);
            }
        } else { // can be an array or an actual object
            $manager = $pManager_name;
        }
        $this->_call_managers[] = $manager;
    }

    /**
     *
     * They must follow the call manager Zupal_Model_Util_CallManager_IF Interface
     * @return object
     */
    protected function _get_call_managers(){
        $out = array();

        foreach($this->_call_managers as $k => $v){
            if (is_string($v)){
                $this->_call_managers[$k] = new $v($this);
            } elseif (is_array($v)) {
                $name = array_shift($v);
                $this->_call_managers[$k] = new $name($this, $v);
            }
        }

        return $this->_call_managers;
    }

    public function  __call($name, $arguments) {
        foreach($this->_get_call_managers() as $c){
            if ($c->manages($name)){
                return call_user_func_array(array($c, $name), $arguments);
            }
        }

        throw new Exception(__METHOD__ . ': no method ' . $name);
    }
}

