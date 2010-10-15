<?php

/**
 * Description of Mongo
 *
 * @author bingomanatee
 */
class Zupal_Model_Query_MongoCollection
        implements Zupal_Model_Query_IF {

    public function __construct($pProps = array(), $pContainer = NULL, $pLimit = NULL, $pSort = NULL, $pFields = NULL) {


        $this->container($pContainer);
        $this->_limit = $pLimit;
        $this->set_sort($pSort);
        $this->_fields = $pFields;

        if (is_array($pProps)) {
            foreach ($pProps as $f => $v) {
                switch ($f) {
                    case '_crit':
                    case '_fields':
                    case '_limit':
                    case '_sort':
                    case '_skip':
                        $this->$f = $v;
                        unset($pProps[$f]);
                        break; // out of switch
                }
            }

            if ($pProps && empty($this->_crit)) {
                $this->_crit = $pProps;
            }
        }
    }

    private $_crit = array();
    private $_fields = NULL;
    private $_limit = NULL;
    private $_sort = NULL;
    private $_skip = NULL;

    public function toArray() {
        return $this->_crit;
    }

    /**
     * returns an array of raw arrays. (actually an iterator thereof.)
     * @param Zupal_Model_Container_IF $container
     * return array;
     */
    public function get_data(Zupal_Model_Container_IF $container = NULL) {
        /* @var $container Zupal_Model_Container_MongoCollection */
        if (!($container = $this->container($container))) {
            throw new Exception(__METHOD__ . ': no container referenced');
        }

        /* @var $cursor MongoCursor */
        $cursor = $container->coll()->find((array) $this->_crit, (array) $this->_fields);
        if ($this->_limit) {
            $cursor = $cursor->limit($this->_limit);
        }

        if ($this->_skip) {
            $cursor = $cursor->skip((int) $this->_skip);
        }

        if ($sort = $this->get_sort()) {
            $cursor = $cursor->sort($sort);
        }

        return $cursor;
    }

    /**
     * returns a single array of raw data. 
     * @param Zupal_Model_Container_IF $container
     * return array;
     */
    public function get_one(Zupal_Model_Container_IF $container = NULL) {
        /* @var $container Zupal_Model_Container_MongoCollection */
        if (!($container = $this->container($container))) {
            throw new Exception(__METHOD__ . ': no container referenced');
        }

        /* @var $cursor MongoCursor */
        $data = $container->coll()->findOne((array) $this->_crit, (array) $this->_fields);

        return $data;
    }

    /**
     * transforms a variety of input to a query
     *
     * @param variant $pWhat
     * @return Zupal_Model_Query_MongoCollection
     */
    public static function to_query($pWhat, $pLimit = NULL, $pSort = NULL, $pFields = NULL) {
        if (is_scalar($pWhat)) {
            $q = new Zupal_Model_Query_MongoCollection(array('_id' => $pWhat));
        } elseif ($pWhat instanceof Zupal_Model_Query_MongoCollection) {
            $q = $pWhat;
        } elseif ($pWhat instanceof Zupal_Model_Query_IF) {
            throw new Exception(__METHOD__ . ': q trans not impl.');
        } elseif (is_array($pWhat)) {
            $q = new Zupal_Model_Query_MongoCollection($pWhat, NULL, $pLimit, $pSort, $pFields);
        } else {
            throw new Exception(__METHOD__ . ": cannot interpret " . print_r($pWhat, 1));
        }

        return $q;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@ CONTAINER @@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_container;

    /**
     * note -- to prevent circular logic, setting the container does not
     * actually add the data object to the container's index.
     * That must be done seperately.
     * @param Zupal_Model_Container_IF $pContainer
     * @return Zupal_Model_Container_IF
     */
    public function container(Zupal_Model_Container_MongoCollection $pContainer = NULL) {
        if ($pContainer) {
            $this->_container = $pContainer;
        }
        return $this->_container;
    }

    public function get_sort() {
        return $this->_sort;
    }

    public function set_sort($sort) {
        if ($sort) {
            if (is_string($sort)) {
                $sort = array($sort => 1);
            }
        } else {
            $sort = NULL;
        }
        $this->_sort = $sort;
    }

}

