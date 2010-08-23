<?php

/*
 * $this is a wrapper for the db mongo object.
 *
 * note: get() retrieves Mongo collections;
 *       find() retrieves MongoDB databases.
 *
 * Although its a container of sorts, its a
 * container of container and therefore does not
 * implement Zupal_Mode_Container_IF.
 */

/**
 * Description of MongoDB
 *
 * @author bingomanatee
 */
class Zupal_Model_Container_MongoDB {

    /**
     * the only real property of a mongoDB is name;
     * @param string $pName
     * note: the databsae conn "lazy loads" so you can
     * call:
     *
     * $stub = new Zupal_Model_Container_Mongodb();
     * $foo_db = $stub->find(array('name' => 'mongo_foo'));
     *
     * ... to get a singleton for a database.
     */
    public function __construct($pName = NULL, $pProps = array()) {
        if (!empty($pName)) {
            if (is_array($pName)) {
                $pName = $pName['name'];
            }
            $this->_name = $pName;
        }
        $this->_props = array_merge(self::$_DEFAULT_PROPS, $pProps);
        self::$_DATABASES[$pName] = $this;
    }

    private static $_DEFAULT_PROPS = array(
        'host' => 'localhost',
        'port' => 27017,
        'persist' => 'mongo lives!!!'
    );
    private $_props = array();
    private $_name;

    public function name() {
        return $this->_name;
    }

    private $_children = array();

    /**
     * gets a Mongo collection.
     * @param string $pKey
     * @return Zupal_Model_Container_Mongo
     */
    public function get($pKey) {
        if (!array_key_exists($pKey, $this->_children)) {
            $this->_children = new Zupal_Model_Container_Mongo($this, $pKey);
        }
        return $this->_children[$pKey];
    }

    private $_db;

    /**
     *
     * @return Mongo
     */
    public function db() {
        if (!$this->_db) {
            $props = $this->_props;
            $host = $props['host'];
            $port = $props['port'];

            unset($props['host']);
            unset($props['port']);

            $header = "$host:$port";

            $mongo = new Mongo($header, $props);
            $name = $this->name();
            $this->_db = $mongo->$name;
        }
        return $this->_db;
    }

    /**
     * Databases do not accept data (I think) directly.
     * @param $pData
     */
    public function add($pData) {
        if (is_string($pData) || is_array($pData)) {
            return $this->find($pData);
        }
    }

    private static $_DATABASES = array();

    /**
     *
     * @param string $pName
     * @return Zupal_Model_Collection_Mongo
     */
    public static function instance($pName) {
        if (!array_key_exists($pName, self::$_DATABASES)) {
            self::$_DATABASES[$pName] = new self($pName); // redundant with constructor
        }
        return self::$_DATABASES[$pName];
    }

    public function gridfs() {
        return $this->db()->getGridFS();
    }

}

