<?php

/**
 * Description of Routes
 *
 * @author bingomanatee
 */
class Zupal_Event_Routes_Domain
extends Zupal_Model_Domain_Abstract
{
    private $_container;

    /**
     * @return Zupal_Model_Container_IF
     */
    protected function container(){
        if (!$this->_container){
            $this->_container = new Zupal_Model_Container_MongoCollection('zupal', 'routes');
            // note -- no schema in place yet
        }
        return $this->_container;
    }

    /**
     * return a blank record;
     * @return Zupal_Model_Data_IF
     */
    public function new_data($pData){
        return new self($pData);
    }


    private $_schema;
    /**
     * @return Zupal_Model_Schema_IF
     */
    public function schema(){
        if (!$this->_schema){
            $path = dirname(__FILE__) . D . 'schema.json';
            $this->_schema = Zupal_Model_Schema_Item::make_from_json($path);
        }
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Zupal_Event_Routes_Domain
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

