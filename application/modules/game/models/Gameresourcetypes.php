<?php

class Game_Model_Gameresourcetypes extends Model_Zupalatomdomain
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Game_Model_DbTable_Gameresourcetypes';
    }

    public static function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * an extension point for new records
     */
    public function init () {
        $this->_soft_delete = TRUE;
        $this->_atom_field_map['title'] = 'name';
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ game_type @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game_type = NULL;

    function get_game_type($pReload = FALSE) {
        if ($pReload || is_null($this->_game_type)):
        // process
            $this->_game_type = Game_Model_Gametypes::getInstance()->get($this->game_type);
        endif;
        return $this->_game_type;
    }

    public function set_game_type($pValue) {
        $pValue = Zupal_Domain_Abstract::_as($pValue, 'Game_Model_Gametypes', TRUE);

        if (!$pValue):
            return;
        endif;

        $this->game_type = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ resource_class @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_resource_class = NULL;
    function get_resource_class($pReload = FALSE) {
        if ($pReload || is_null($this->_resource_class)):
            $this->_resource_class = Game_Model_Gameresourceclasses::getInstance()->get($this->resource_class);
        endif;
        return $this->_resource_class;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resource_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function resource_class () {
        return $this->get_resource_class();
    }

    public function set_resource_class($pValue) {
        $pValue = Zupal_Domain_Abstract::_as($pValue, 'Game_Model_Gameresourceclasses', TRUE);
        
        if (!$pValue):
            return;
        endif;

        $this->resource_class = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resource_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pParam
     * @return <type>
     */
    public function resource_type ($pParam) {
        return $out;
    }
}

