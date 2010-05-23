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
Zupal_Model_Container_IF {
    const LOAD_NEW = 'new';
    /**
     *
     * @var Zupal_Model_Data_IF
     */
    protected $_record;
    public $metadata = array();

    public function __construct($pKey = NULL) {
        if ($pKey) {
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
        global $event_manager;

        if (!empty($pKey)) {
            if ($pKey == self::LOAD_NEW) {
                $this->_record = $this->container()->get_new();
            } elseif ($pKey instanceof Zupal_Model_Data_IF) {
                $this->_record = $pKey;
            } elseif ($pKey instanceof Zupal_Model_Query_IF) {
                $this->_record = $this->container()->find_one($pKey);
            } elseif (is_array($pKey)) {
                $this->_record = $this->container()->new_data($pKey);
            } else {
                $this->_record = $this->container()->get($pKey);
            }
            $event_manager->handle('load', $this);
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
        /* @var $event_manager Zupal_Event_Manager */
        global $event_manager;

        $this->_record->delete();
        $event_manager->handle('delete', $this);
    }

    public function delete_data(Zupal_Model_Data_IF $pData) {
        if ($pData instanceof Zupal_Model_Domain_Abstract) {
            $pData = $pData->record();
        }
        $this->container()->delete_data($pData);
    }

    public function get_field($name, $scope = 'r') {
        switch($scope) {
            case 'm':
                return $this->metadata[$name];
                break;

            case 'r':
                return $this->_record[$name];
                break;

            default: return $this->_get($name);
        }
    }

    public function set_field($name, $value, $scope='r') {
        switch($scope) {
            case 'm':
                $this->metadata[$name] = $value;
                break;

            case 'r':
                $this->_record[$name] = $value;
                break;

            default: $this->_set($name, $value);
        }
    }

    public function  __get($name) {
        if (array_key_exists($name, $this->_record)) {
            return $this->_record[$name];
        } elseif (array_key_exists($name, $this->metadata)) {
            return $this->metadata[$name];
        } else {
            return NULL; // throw new Exception(__METHOD__ . ": unrecorded field $name requested");
        }
    }

    public function  __set($name,  $value) {
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

    public function toArray() {
        return array_merge($this->metadata, $this->_record->toArray());
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

    public function insure_defaults(){

        foreach($this->schema()->defaults() as $field => $value){
            if (!isset($this->_record[$field])){
                $this->_record[$field] = $value;
            }
        }
    }

    public function save() {
        /* @var $event_manager Zupal_Event_Manager */
        global $event_manager;
        $this->container()->save_data($this->_record);

        $event_manager->handle('save', $this);
    }

    /* @@@@@@@@@@@@@@@@ CONATINER_IF METHODS @@@@@@@@@@ */

    /**
     * @return Zupal_Model_Container_IF
     */
    protected abstract function container();

    public function get($pKey) {
        return $this->new_data($pKey);
    }

    //public abstract function new_data($pData);

    public function add($pData) {
        $d = $this->new_data($pData);
        $d->save();
        return $d;
    }

    public function find($pQuery, $limit = NULL, $sort = NULL) {
        $out = array();

        foreach($this->container()->find($pQuery, $limit, $sort) as $record) {
            $out[] = $this->new_data($record);
        }

        return $out;
    }

    public function find_one($pQuery, $sort = NULL) {
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

        foreach($this->find($pQuery) as $domain) {
            $domain->delete();
        }
    }

    /**
     * This mesthod is intended as a bridge between
     * a data object and a container.
     * @param Zupal_Model_Data_IF $pData
     */
    public function save_data(Zupal_Model_Data_IF $pData) {
        throw new Exception(__METHOD__ . ': not relevant for Domains');
    }

}
