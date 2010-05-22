<?php

class Zupal_Model_Container_JSON
extends Zupal_Model_Container_Abstract {

    private $_name;

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

    private $_root = '';

    public function __construct($pName, array $pProps = array()) {
        $this->_name = $pName;
        $this->_type = 'array';
        $this->_schema = array();

        foreach($pProps as $key => $value) {
            $key = strtolower(trim($key));

            switch ($key) {
                case 'parent':
                    $this->_parent = $value;
                    break;

                case 'type':
                    $this->_type = $value;
                    break;

                case 'schema':
                    $this->schema($value);

                case 'root':
                    $this->_root = rtrim($value, D);
            }

            if (!is_dir($this->_root)) {
                throw new Exception(__METHOD__ . ': bad dir ' . $value);
            }
        }
    }

    public function root() {
        return $this->_root;
    }


    /**
     * the superior container. Assumes a single parent model.
     * @return Zupal_Model_Container_Abstract
     */
    public function parent() {
        return $this->_parent;
    }

    /**
     * The storage type of the context: file, database, service, etc.
     * Always lowercase.
     *
     * @return string
     */
    public function type() {
        return $this->_type;
    }

    public function name() {
        return $this->_name;
    }

    /**
     * returns a single item via its key. unlike find, accepts only scalar data.
     */

    public function get($pKey) {
        $path = $this->_root . D . $pKey . '.json';
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $info = Zend_Json_Decoder::decode($json);
            $data = new Zupal_Model_Data_JSON($info, $this);
            return $data;
        }
        return NULL;
    }

    /**
     * Adds the data item into this container.
     * If data has no key yet, creates a key for it.
     * @param Zupal_Model_Data_Item $pData
     */
    public function add(Zupal_Model_Data_Item $pData) {
        $pData->container($this);
    }

    public function find( $pQuery, $limit = NULL, $sort = NULL) {
        throw new Exception(__METHOD__ . ': not implemented');
    }

    /**
     * gets (and/or sets) the schema for this container.
     * @return Zupal_Model_Schema_Item
     */
    public function schema(Zupal_Model_Schema_item $pSchema = NULL) {
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
        if (is_scalar($pWhat)) {
            $path = $this->_root . D . $pWhat . '.json';
            if (file_exists($path)) {
                return true;
            }
            return FALSE;
        } elseif ($pWhat instanceof Zupal_Model_Query_item) {
            return count($this->find($pWhat));
        } elseif($pWhat instanceof Zupal_Model_Data_IF) {
            return $this->has($pWhat->key());
        }
    }

    public function count() {
        return count($this->_data);
    }

    /**
     * can be a scalar key to get, a query to find or an actual data item.
     * @param variant $pWhat
     * @return boolean
     * returns true if any actual data was deleted.
     */
    public function delete($pWhat) {
        $count = 0;
        if (is_scalar($pWhat)) {
            $path = $this->_root . D . $pWhat . '.json';
            if (file_exists($path)) {
                unlink($path);
            }
            $path = $this->_root . D . $pWhat;
            if (file_exists($path)) {
                unlink($path);
            }
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

    public function has_index($field) {
        return FALSE;
    }

    /**
     * returns array of data.
     * @param array || $pCrit
     * @return array;
     */
    public function get_data($pCrit) {
        if (is_array($pCrit)) {
            $crit = new Zupal_Model_Query_item($pCrit);
        }

        $indexed_filters = array();
        $unindexed_filters = array();

        /* @var $filter Zupal_Model_Query_Filter */
        foreach($pCrit->filters() as $filter()) {
            $field = $filter->field;
            if ($this->has_index($field)) {
                $indexed_filters[] = $filter;
            } else {
                $unindexed_filters[] = $filter;
            }
        }


        $first_filter = array_shift($unindexed_filters);

        $candidates = $first_filter->find($this);

        foreach($unindexed_filters as $filter) {
            if (count($candidates)) {
                $candidates = array_filter($candidates, array($filter, 'test'));
            }
        }
    }

    public function new_data(array $pData = array()) {
        $data = new Zupal_model_Data_JSON($pData, $this);
        return $data;
    }

    public function save(Zupal_Model_Data_JSON $pData) {
        $json = Zend_Json::encode($pData->toArray());
        if ($id = $pData->key()) {
            file_put_contents($this->file_path($id), $json);
        } else {
            throw new Exception(__METHOD__ . ': attempt to save unkeyed data' . $json);
        }
    }
}