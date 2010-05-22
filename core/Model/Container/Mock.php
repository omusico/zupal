<?php

class Zupal_Model_Container_Mock
implements Zupal_Model_Container_IF {

    private $_name;

    /**
     *
     * @var array
     */
    private $_data;

    /**
     *
     * @var Zupal_Model_Schema_Item
     */
    private $_schema;

    /**
     *
     * @var Zupal_Model_Container_Abstract
     */
    private $_parent;

    /**
     * see @type();
     * @var string
     */
    private $_type;

    public function __construct(array $pProps = array()) {
        $this->_name = $pName;
        $this->_type = 'array';
        $this->_schema = array();
        $this->_data = array();

        foreach($pProps as $key => $value) {
            $key = strtolower(trim($key));

            switch ($key) {
                case 'name':
                    $this->_name = $value;
                    break;

                case 'parent':
                    $this->_parent = $value;
                    break;

                case 'type':
                    $this->_type = $value;
                    break;

                case 'schema':
                    $this->schema($value);
            }
        }
    }

    public function name() {
        return $this->_name;
    }

    /**
     * returns a single item via its key. unlike find, accepts only scalar data.
     */
    public function get($pKey) {
        if (array_key_exists($pKey, $this->_data)) {
            return $this->_data[$pKey];
        } else {
            return NULL;
        }
    }

    /**
     * Adds the data item into this container.
     * If data has no key yet, creates a key for it.
     * @param Zupal_Model_Data_Item $pData
     */
    public function add($pData) {
        throw new Exception(__METHOD__ > ': not implemented');
    }

    public function find( $pQuery, $limit = NULL, $sort = NULL) {
        throw new Exception(__METHOD__ . ': not implemented');
    }

    public function find_one( $pQuery, $sort = NULL) {
        throw new Exception(__METHOD__ . ': not implemented');
    }

    /**
     * gets (and/or sets) the schema for this container.
     * @return Zupal_Model_Schema_Item
     */
    protected function _schema(Zupal_Model_Schema_item $pSchema = NULL) {
        if ($pSchema) {
            if (is_string($pSchema)){
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
        return array_key_exists($pWhat, $this->_data);
    }

    /**
     * can be a scalar key to get, a query to find or an actual data item.
     * @param variant $pWhat
     * @return boolean
     * returns true if any actual data was deleted.
     */
    public function find_and_delete($pWhat) {
        $count = 0;
        if (is_scalar($pWhat)) {
            $data = $this->get($pWhat);
            $count += $this->delete($data);
        } elseif ($pWhat instanceof Zupal_Model_Query_item) {
            foreach($this->find($pWhat) as $data) {
                $count += $this->delete($data);
            }
        } elseif (is_array($pWhat)) {
            foreach($pWhat as $item) {
                $count += $this->delete($item);
            }
        } elseif ($pWhat instanceof Zupal_Model_Data_Item) {
            $this->_remove($pWhat);
            $pWhat->delete(FALSE);
            ++$count;
        } else {
            throw new Exception(__METHOD__ . ": cannot delete " . print_r($pWhat, 1));
        }
        return $count;
    }

    function delete_data(Zupal_Model_Data_IF $pData){
        throw new Exception(__METHOD__ . ': not implemented');
    }
    
    function save_data(Zupal_Model_Data_IF $pData){
        throw new Exception(__METHOD__ . ': not implemented');
    }

    /**
     * sub of delete; handles removal of an item from the registry
     * @param variant $pData
     */
    protected function _remove($pData) {
        if (is_scalar($pData)) {
            $key = $pData;
        } elseif ($pData instanceof Zupal_Model_Data_Item) {
            $key = $pData->key();
        }

        if (array_key_exists($key, $this->_data)) {
            unset($this->_data[$key]);
        }
    }

    public function toArray() {
        $out = array();
        foreach($this->_data as $key => $item) {
            $item_array = $item->toArray();
            //     echo __METHOD__ , " $key => ", print_r($item_array, 1), "\n";
            $out[$key] = $item_array;
        }
        return $out;
    }

    public function new_data(array $pData = array()) {
        $schema = $this->schema();

        $data = new Zupal_Model_Data_Item($pData, $schema, $this);
        if ($data->key(FALSE)) {
            $this->add($data);
        }
        return $data;
    }
}