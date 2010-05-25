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
    protected abstract function container(){
        if (!$this->_container){
            $this->_container = new Zupal_Model_Container_Mongo('zupal', 'routes');
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
}

