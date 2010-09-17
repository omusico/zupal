<?php

/**
 * represents a Mongo collection
 */
class Zupal_Model_Container_MongoCollection
        implements Zupal_Model_Container_IF {

    private $_name;
    /**
     *
     * @var Zupal_Model_Schema_Item
     */
    private $_schema;
    /**
     *
     * @var Zupal_Model_Container_MongoDB
     */
    private $_parent;

    public function __construct($pParentDB, $pColl, array $pProps = array()) {
        $this->_name = $pColl;

        if (is_string($pParentDB)) {
            $pParentDB = Zupal_Model_Container_MongoDB::instance($pParentDB);
        }
        $this->_parent = $pParentDB;

        $this->_type = 'mongo';

        foreach ($pProps as $key => $value) {
            $key = strtolower(trim($key));

            switch ($key) {
                case 'parent':
                    $this->_parent = $value;
                    break;

                case 'schema':
                    $this->schema($value);
            }
        }

        if (!$this->_schema) {
            $this->_schema = new Zupal_Model_Schema_Item();
        }
    }

    /**
     * the superior container. Assumes a single parent model.
     * @return Zupal_Model_Container_MongoDB
     */
    public function parent() {
        return $this->_parent;
    }

    /**
     *
     * @return MongoGridFS
     */
    public function gridfs() {
        return $this->parent()->gridfs();
    }

    public function name() {
        return $this->_name;
    }

    private $_coll;

    /**
     *
     * @return Mongo
     */
    public function coll() {
        if (!$this->_coll) {
            $name = $this->name();
            $this->_coll = $this->parent()->db()->$name;
        }
        return $this->_coll;
    }

    /**
     * returns a single item via its key. unlike find, accepts only scalar data.
     */
    public function get($pKey) {

        $q = array('_id' => $pKey instanceof MongoId ? $pKey : new MongoId($pKey));

        $data = $this->coll()->findOne($q);

        if (empty($data)) {
            if ($pKey instanceof MongoId) {
                $pKey = $pKey->__toString();
            }
            $q = array('_id' => $pKey);
            $data = $this->coll()->findOne($q);
            if (!$data) {
                throw new Exception(__METHOD__ . ': cannot find key ' . $pKey . ' in collection ' . $this->name());
            }
        }
        return new Zupal_Model_Data_Mongo($data, $this);
    }

    /**
     *  transforms an array of raw data into a mongo object. 
     * @param array $pData
     * @return Zupal_Model_Data_Mongo
     */
    public function new_data($pData) {
        return new Zupal_Model_Data_Mongo($pData, $this);
    }

    /**
     * Adds data to the container. Unlike new_data which just
     * creates an object that COULD be saved,
     *  this method actually saves the data.
     * @param $pData
     */
    public function add($pData) {
        if (is_array($pData)) {
            $data = $this->new_data($pData);
        } elseif ($pData instanceof Zupal_Model_Data_Mongo) {
            $data = $pData;
            //@TODO: container match test;
        } else {
            throw new Exception(__METHOD__ . ': cannot save ' . print_r($pData, 1));
        }
        $this->save($data);
        return $data;
    }

    /**
     *
     * @param <type> $pWhat
     * @param <type> $limit
     * @param <type> $sort
     * @return array
     */
    function find($pWhat = NULL, $limit = NULL, $sort = NULL) {
        if ($pWhat instanceof Zupal_Model_Query_MongoCollection) {
            $pQuery = $pWhat;
        } else {
            $pQuery = Zupal_Model_Query_MongoCollection::to_query($pWhat, $limit, $sort);
        }

        $cursor = $pQuery->get_data($this);

        $out = array();

        foreach ($cursor as $data) {
            $out[] = $this->new_data($data);
        }
        return $out;
    }

    /**
     *
     * @param string $limit
     * @param string | array $sort
     * @return array
     */
    function find_all($limit = NULL, $sort = NULL) {
        $cursor = $this->coll()->find(array())->sort((array) $sort);
        $out = array();

        foreach ($cursor as $data) {
            $out[] = $this->new_data($data);
        }
        return $out;
    }

    public function find_one($pWhat = NULL, $sort = NULL) {
        if ($pWhat instanceof Zupal_Model_Query_MongoCollection) {
            $pQuery = $pWhat;
        } else {
            $pQuery = Zupal_Model_Query_MongoCollection::to_query($pWhat, NULL, $sort);
        }

        $array = $pQuery->get_one($this);

        return $array ? $this->new_data($array) : NULL;
    }

    /**
     * gets (and/or sets) the schema for this container.
     * @return Zupal_Model_Schema_Item
     */
    public function schema(Zupal_Model_Schema_IF $pSchema = NULL) {
        if ($pSchema) {
            if (is_string($pSchema)) {
                $pSchema = Zupal_Model_Schema_Item::make_from_json($pSchema);
            }
            $this->_schema = $pSchema;
        }

        return $this->_schema;
    }

    /**
     * can be a scalar key to get, a query to find or an actual data item.
     * @param variant $pWhat
     * @return boolean
     */
    public function has($pWhat) {
        $q = Zupal_Model_Query_Mongo::to_query($pWhat);

        return $this->coll()->count($q->toArray());
    }

    /**
     * can be a scalar key to get, a query to find or an actual data item.
     * @param variant $pWhat
     * @return boolean
     * returns true if any actual data was deleted.
     */
    public function find_and_delete($pWhat) {
        $q = Zupal_Model_Query_Mongo::to_query($pWhat);
        $this->coll()->remove($q->toArray());
    }

    public function delete_data(Zupal_Model_Data_IF $pData) {
        $key = $pData->key();
        if (is_string($key)){
            $key = new MongoId($key);
        }
        $q = array('_id' => $key);
        $this->coll()->remove($q);
    }

    /**
     * inserts/updates data in the database, depending on the existince of _id.
     * @param Zupal_Model_Data_IF $pData
     */
    public function save_data(Zupal_Model_Data_IF $pData) {
        if (is_array($pData)) {
            $array = $pData;
        } elseif (is_object($pData) && method_exists($pData, 'toArray')) {
            $array = $pData->toArray();
        } else {
            throw new Exception(__METHOD__ . ': bad data passed: ' . print_r($pData, 1));
        }

        if ($this->schema()) {
            $valid = $this->schema()->validate($array);
            foreach ($array as $k => $v) {
                if (is_null($v)) {
                    $array[$k] = '';
                }
            }
            if ($valid !== TRUE) {
                throw new Zupal_Model_Schema_Exception(__METHOD__ . ': attempt to submit invalid data:', $valid);
            }
        }

        if (empty($array['_id'])) {
            //  $array['_id'] = new MongoId();
            $result = $this->coll()->save($array);
            $pData->set_key($array['_id']);
            $pData->status(Zupal_Model_Data_IF::STATUS_SAVED);
        } elseif (!$this->coll()->findOne(array('_id' => $array['_id']), array('_id'))) {
            $result = $this->coll()->insert($array);
            $pData->status(Zupal_Model_Data_IF::STATUS_SAVED);
        } else {
            $this->coll()->update(array('_id' => $array['_id']), $array);
            $pData->status(Zupal_Model_Data_IF::STATUS_UPDATED);
        }
    }

    public function insert_data(Zupal_Model_Data_IF $pData) {

        if (is_array($pData)) {
            $array = $pData;
        } elseif (is_object($pData) && method_exists($pData, 'toArray')) {
            $array = $pData->toArray();
        } else {
            throw new Exception(__METHOD__ . ': bad data passed: ' . print_r($pData, 1));
        }

        if ($this->schema()) {
            $valid = $this->schema()->validate($array);
            foreach ($array as $k => $v) {
                if (is_null($v)) {
                    $array[$k] = '';
                }
            }
            if ($valid !== TRUE) {
                throw new Zupal_Model_Schema_Exception(__METHOD__ . ': attempt to submit invalid data:', $valid);
            }
        }

        $result = $this->coll()->insert($array);
        $pData->set_key($array['_id']);

        $pData->status(Zupal_Model_Data_IF::STATUS_SAVED);
    }

    public function get_count($pQuery = NULL) {
        return $this->coll()->count($pQuery);
    }

}